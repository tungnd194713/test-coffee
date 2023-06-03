<?php

namespace App\Services;

use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class RestaurantService implements RestaurantServiceInterface
{
    public function list($request) {
        $query = Restaurant::select('name', 'address', 'total_star');
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
            $query->whereIn('district', $request->district);
        }
        if (isset($request->service)) {
            $service = $request->service;
            $query->whereHas('services', function ($query) use ($service) {
                $query->whereIn('name', $service);
            });
        }
        if (isset($request->star_rating)) {
            $query->whereIn('total_star', $request->star_rating);
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
            $query->select('name', 'address', 'total_star', DB::raw("6371 * 2 * ASIN(SQRT(POWER(SIN(({$user->longtitude} - latitude) * pi()/180 / 2), 2) + COS({$user->latitude} * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(({$user->longtitude} - longitude) * pi()/180 / 2), 2))) AS distance"))
                  ->orderBy('distance');
        }
        $data = $query->get()->toArray();
        return $data;
    }
}
