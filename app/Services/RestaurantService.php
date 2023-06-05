<?php

namespace App\Services;

use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class RestaurantService implements RestaurantServiceInterface
{
    public function list($request) {
        $query = Restaurant::select('id', 'name', 'address', 'total_star');
        if (isset($request->name)) {
            $query->where('name', 'like', $request->name);
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
            $services = explode(',', $request->service);
            $query->whereHas('services', function ($query) use ($services) {
                $query->whereIn('name', $services);
            });
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
            $query->select('id', 'name', 'address', 'total_star', DB::raw("6371 * 2 * ASIN(SQRT(POWER(SIN(({$user->longtitude} - latitude) * pi()/180 / 2), 2) + COS({$user->latitude} * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(({$user->longtitude} - longitude) * pi()/180 / 2), 2))) AS distance"))
                  ->orderBy('distance');
        }
        $data = $query->get()->toArray();
        return $data;
    }
}
