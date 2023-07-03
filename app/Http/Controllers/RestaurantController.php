<?php

namespace App\Http\Controllers;

use App\Services\RestaurantServiceInterface;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function __construct(protected RestaurantServiceInterface $restaurantService)
    {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json($this->restaurantService->list($request));
    }

    public function createReview(Request $request, $restaurant_id) {
        return response()->json($this->restaurantService->createReview($request, $restaurant_id));
    }

    public function getReview(Request $request, $restaurant_id) {
        return response()->json($this->restaurantService->listReview($request, $restaurant_id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->json($this->restaurantService->createStore($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json($this->restaurantService->detail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
