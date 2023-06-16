<?php

namespace App\Services;

use App\Helpers\S3Helper;
use App\Models\Comment;
use App\Models\Restaurant;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class RestaurantService implements RestaurantServiceInterface
{
    public function detail($id) {
        return ['data' => Restaurant::with('services')->findOrFail($id), 'status' => 200];
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

        $hashedToken = $request->bearerToken();
        if(isset($hashedToken)) {
            $token = PersonalAccessToken::findToken($hashedToken);
            $user = $token->tokenable;
            $query->select('id', 'name', 'address', 'total_star', 'logo', DB::raw("6371 * 2 * ASIN(SQRT(POWER(SIN(({$user->longtitude} - latitude) * pi()/180 / 2), 2) + COS({$user->latitude} * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(({$user->longtitude} - longitude) * pi()/180 / 2), 2))) AS distance"))
                  ->orderBy('distance');
        }
        $data = $query->get()->toArray();
        return $data;
    }

    public function listReview($request, $restaurant_id) {
        $restaurant = Restaurant::findOrFail($restaurant_id);

        $listReview = $restaurant->comments()->with('user:id,username,avatar')->get()->toArray();
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
}
