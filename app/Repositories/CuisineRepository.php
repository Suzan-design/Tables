<?php
namespace App\Repositories;
use App\Repositories\Interfaces\CuisineRepositoryInterface;
use App\Models\Cuisine;
use Illuminate\Http\Request;
class CuisineRepository implements CuisineRepositoryInterface
{
    public function index()
    {
        return Cuisine::all();
    }

    public function store($request)
    {

        Cuisine::create(['name'=>$request->name,'description'=>$request->description]);
    }

    public function show($id) {}
    public function edit($id) {}

    public function update($request,$id)
    {
   
    }
    public function destroy($id)
    {
        Cuisine::where('id',$id)->delete();
    }

}
