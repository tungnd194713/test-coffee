<?php

namespace App\Services;

interface RestaurantServiceInterface
{
    public function detail($id);

    public function list($request);

    public function createReview($request, $restaurant_id);

    public function listReview($request, $restaurant_id);

    public function createStore($request);
}
