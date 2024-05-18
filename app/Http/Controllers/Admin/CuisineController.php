<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cuisine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CuisineRequest;
use App\Repositories\Interfaces\CuisineRepositoryInterface;

class CuisineController extends Controller
{
    private $cuisineRepository;

    public function __construct(CuisineRepositoryInterface $cuisineRepository)
    {
        $this->cuisineRepository = $cuisineRepository;
    }

    public function index()
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $cuisines = $this->cuisineRepository->index();
        return view('Admin.cuisines', compact('cuisines'));
    }

    public function store(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        try {
            DB::beginTransaction();
            $validatedData = $request->validateWithBag('add', [
                'name' => 'required|unique:cuisines,name|max:25',
                'description' => 'required|max:255',
                'ar_name' => 'required|unique:cuisines,name|max:25',
                'ar_description' => 'required|max:255',
            ]);
            Cuisine::create(['name' => $request->name, 'description' => $request->description,'ar_name' => $request->ar_name, 'ar_description' => $request->ar_description]);
            DB::commit();
            return redirect()->back()->with('modalOpen', true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            $request->session()->flash('add_error', true);
            return redirect()->back()->withErrors($e->validator, 'add')->withInput();
        }
    }
    public function show(string $id)
    {
    }
    public function edit(string $id)
    {
    }
    public function update(Request $request, string $id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        return $id;
        try {
            DB::beginTransaction();

            $validatedData = $request->validateWithBag('edit', [

                'name' => 'required|max:25',
                'description' => 'required|max:255',
                'ar_name' => 'required|max:25',
                'ar_description' => 'required|max:255',
            ]);
            Cuisine::where('id', $id)->update([
                'name'=>$request->name,
                'description'=>$request->description,
                'ar_name'=>$request->ar_name,
                'ar_description'=>$request->ar_description,
            ]);
            DB::commit();
            return redirect()->back()->with('modalOpen', true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            $request->session()->flash('edit_error', true);
            $request->session()->flash('edit_id', $id);
            return redirect()->back()->withErrors($e->validator, 'edit')->withInput();
        }
    }
    public function cuisine_update(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $id=$request->cuisine_id;
        try {
            DB::beginTransaction();

            $validatedData = $request->validateWithBag('edit', [

                'name' => 'required|max:25',
                'description' => 'required|max:255',
                'ar_name' => 'required|max:25',
                'ar_description' => 'required|max:255',
            ]);
            Cuisine::where('id', $id)->update([
                'name'=>$request->name,
                'description'=>$request->description,
                'ar_name'=>$request->ar_name,
                'ar_description'=>$request->ar_description,
            ]);
            DB::commit();
            return redirect()->back()->with('modalOpen', true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            $request->session()->flash('edit_error', true);
            $request->session()->flash('edit_id', $id);
            return redirect()->back()->withErrors($e->validator, 'edit')->withInput();
        }
    }
    public function destroy(string $id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $this->cuisineRepository->destroy($id);
        return  redirect()->route('cuisines.index');
    }
}
