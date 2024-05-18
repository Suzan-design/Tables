<?php

namespace App\Repositories\Interfaces;

interface ApiReservationsRepositoryInterface
{
    public function reversation($request,$id);
    public function my_reservations();
    public function reversation_cancel($id);
    public function available_reservations($request,$id);
    public function available_capacity($request,$id);
    public function available_times_res($request,$id);
}
