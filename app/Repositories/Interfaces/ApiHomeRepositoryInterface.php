<?php

namespace App\Repositories\Interfaces;

interface ApiHomeRepositoryInterface
{
    public function map_res($request);
    public function details_offer($id);
    public function details_category($id);
    
}
