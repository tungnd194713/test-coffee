<?php

namespace App\Services;

use App\Helpers\S3Helper;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Restaurant;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

class RestaurantService implements RestaurantServiceInterface
{
    public function detail($id) {
        return ['data' => Restaurant::with('services')->with('items')->findOrFail($id), 'status' => 200];
    }

    public function list($request) {
        $query = Restaurant::select('id', 'name', 'address', 'total_star', 'logo');
        if (isset($request->name)) {
            $query->where('name', 'like', '%'.$request->name.'%');
            $user = auth('sanctum')->user();
            if ($user) {
                $history = json_decode($user->search_history);
                if (!$history || count($history) == 0) {
                    $history = [];
                }
                $key = array_search($request->name, $history);
                if ($key === false) {
                    array_push($history, $request->name);
                }
                $user->search_history = json_encode($history);
                $user->save();
            }
        }
        if (isset($request->district)) {
            $districts = explode(',', $request->district);
            $query->whereIn('district', $districts);
        }
        if (isset($request->service)) {
            $services = array_filter(explode(',', $request->service));
            $query->whereHas('services', function ($query) use ($services) {
                $query->whereIn('services.id', $services);
            }, '=', count($services));
        }
        if (isset($request->star_rating)) {
            $star_ratings = explode(',', $request->star_rating);
            $query->whereIn('total_star', $star_ratings);
        }
        if (isset($request->is_crowded)) {
            $current_time = date('H:i:s');
            $compare = $request->is_crowded ? '<=' : '>';
            $query->where('crowded_time', $compare, $current_time);
        }

        if($request->user()) {
            $query->select('id', 'name', 'address', 'total_star', 'logo', DB::raw("6371 * 2 * ASIN(SQRT(POWER(SIN(({$user->longtitude} - latitude) * pi()/180 / 2), 2) + COS({$user->latitude} * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(({$user->longtitude} - longitude) * pi()/180 / 2), 2))) AS distance"))
                  ->orderBy('distance');
        }
        $data = $query->offset(($request->current_page - 1) * $request->per_page)->limit($request->per_page)->get()->toArray();
        return $data;
    }

    public function listReview($request, $restaurant_id) {
        $restaurant = Restaurant::findOrFail($restaurant_id);

        $listReview = Comment::where('restaurant_id', $restaurant->id)->with('user:id,username,avatar')->offset(($request->current_page - 1) * $request->per_page)->limit($request->per_page)->get()->toArray();
        return ['data' => $listReview, 'status' => 200];
    }

    public function createReview($request, $restaurant_id) {
        $request->validate([
            'star_rating' => 'required|numeric|min:1|max:5',
            'content' => 'string',
            'image' => 'nullable|image|max:2048', // Assuming max file size of 2MB
        ]);

        // Find the restaurant
        $restaurant = Restaurant::with('comments')->findOrFail($restaurant_id);
        $starValue = $restaurant->comments->pluck('star_rating')->toArray();
        $starValue[] = $request->input('star_rating');
        $restaurant->total_star = array_reduce($starValue, function($carried, $value) use ($starValue) {
            return ($carried === null ? 0 : $carried) + $value / count($starValue);
        }, null);
        $restaurant->save();


        // Create the review
        $review = new Comment();
        $review->star_rating = $request->input('star_rating');
        $review->content = $request->input('content');
        $review->restaurant()->associate($restaurant);
        $review->user()->associate($request->user());
        $review->save();

        // Handle the image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = S3Helper::uploadToS3($image, 'review_images');

            // Store the image path in the review model
            $review->image = $imagePath;
            $review->save();
        }

        return ['message' => 'Create review successfully', 'status' => 200];
    }

    public function createStore($request) {
        DB::beginTransaction();
        try {
            $restaurant = Restaurant::create([
                'address' => $request->address,
                'district' => rand(1, 10),
                'name' => $request->name,
                'logo' => '',
                'view' => 0,
                'total_star' => 0,
                'crowded_time' => $request->crowded_time,
                'end_crowded_time' => $request->end_crowded_time,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            if ($request->has('logo')) {
                $avatar = $request->logo; //your base64 encoded data
                $avatarPath = S3Helper::uploadToS3($avatar, 'restaurant_logo');
                $restaurant->logo = $avatarPath;
                $restaurant->save();
            }

            if ($restaurant) {
                $request->user()->restaurants()->attach($restaurant->id);

                $items = array_map(function($item) use ($restaurant) {
                    return [
                        'restaurant_id' => $restaurant->id,
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'price' => $item['price'],
                    ];
                }, $request->menu);
                if (count($items)) {
                    $restaurant->items()->insert($items);
                }

                $services = $request->services;
                if (count($services)) {
                    foreach($services as $service) {
                        $restaurant->services()->attach($service);
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            dd($e->getMessage());
            abort(500, $e->getMessage());
            DB::rollBack();
        }

        return ['data' => $restaurant, 'status' => 200];
    }

    public function listOwnedStore($request) {
        $owner = $request->user();
        if ($request->has('per_page') && $request->has('current_page')) {
            return $owner->restaurants()->select('id', 'name', 'address', 'total_star', 'logo')->offset(($request->current_page - 1) * $request->per_page)->limit($request->per_page)->get()->toArray();
        }

        return $owner->restaurants()->select('id', 'name', 'address', 'total_star', 'logo')->get()->toArray();
    }

    public function updateStore($request, $storeId) {
        $store = Restaurant::findOrFail($storeId);
        DB::beginTransaction();
        try {
            $store->name = $request?->name;
            $store->address = $request?->address;
            $store->district = $request?->district;
            $store->crowded_time = $request?->crowded_time;
            $store->end_crowded_time = $request?->end_crowded_time;
            $store->latitude = $request?->latitude;
            $store->longitude = $request?->longitude;
            $store->save();

            if ($request->has('logo')) {
                $avatar = $request->logo; //your base64 encoded data
                $avatarPath = S3Helper::uploadToS3($avatar, 'restaurant_logo');
                $oldLogo = $store->logo;
                $store->logo = $avatarPath;
                $store->save();
                if ($oldLogo) {
                    S3Helper::deleteFromS3($oldLogo, 'restaurant_logo');
                }
            }

            if ($request->has('menu')) {
                Item::where('restaurant_id', $storeId)->delete();
                $items = array_map(function($item) use ($storeId) {
                    return [
                        'restaurant_id' => $storeId,
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'price' => $item['price'],
                    ];
                }, $request->menu);
                if (count($items)) {
                    $store->items()->insert($items);
                }
            }

            if ($request->has('services')) {
                $store->services()->detach();
                foreach($request->services as $service) {
                    $store->services()->attach($service);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e);
        }

        return ['data' => $store, 'status' => 200];
    }
}
