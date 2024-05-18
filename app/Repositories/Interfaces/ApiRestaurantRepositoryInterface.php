<?php

namespace App\Repositories\Interfaces;

interface ApiRestaurantRepositoryInterface
{
    public function proposal_Restaurants($request);
    public function details($id);
    public function list_rec_follow();
    public function followUnfollowRestaurant($id);

    public function search($request);
    public function advansearch($request);
    public function filtersearch($request);
    public function review($request,$id,);
    public function reviews($id);
    public function nearest_Restaurants();
    public function cuisine_Restaurants($id);
}
