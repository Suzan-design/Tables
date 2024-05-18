<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\restaurnats_categories;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $categories = restaurnats_categories::get();
        return view('Admin.Categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        try {
            DB::beginTransaction();
            $validatedData = $request->validateWithBag('add',[
                'name' => 'required|unique:restaurnats_categories,name|max:25',
                'icon' => 'required',
                'description' => 'required|max:255',
                'ar_name' => 'required|unique:restaurnats_categories,ar_name|max:25',
                'ar_description' => 'required|max:255',
            ]);
            $filePath = '';
            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments/Restaurants/Categories/icons/'), $filename);
                
            }
            restaurnats_categories::create([
                'name' => $request->name,
                'icon' => 'attachments/Restaurants/Categories/icons/'. $filename,
                'description' => $request->description,
                'ar_name' => $request->ar_name,
                'ar_description' => $request->ar_description,
            ]);
            DB::commit();
            return redirect()->back()->with('modalOpen', true);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            $request->session()->flash('add_error', true);
            return redirect()->back()->withErrors($e->validator, 'add')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        try {
            DB::beginTransaction();
            $category = restaurnats_categories::where('id', $id)->first();
            $validatedData = $request->validateWithBag('edit',[
                'name' => 'required|unique:restaurnats_categories,name|max:25',
                'icon' => 'required',
                'description' => 'required|max:255',
                'ar_name' => 'required|unique:restaurnats_categories,ar_name|max:25',
                'ar_description' => 'required|max:255',
            ]);
            $category->update($validatedData);
            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments/Restaurants/Categories/icons/'), $filename);
                $category->update([
                    'icon' => 'attachments/Restaurants/Categories/icons/'. $filename,
                ]);
            }
            DB::commit();
            return redirect()->back();
        }   catch (\Illuminate\Validation\ValidationException $e) {
            $request->session()->flash('edit_error', true);
            $request->session()->flash('edit_id', $id);
            return redirect()->back()->withErrors($e->validator, 'edit')->withInput();
        }
    }
    public function category_update(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $id=$request->category_id;
        
        try {
            DB::beginTransaction();
            $category = restaurnats_categories::where('id', $id)->first();
            $validatedData = $request->validateWithBag('edit',[
                'name' => 'required|max:25',
                'description' => 'required|max:255',
                'ar_name' => 'required|max:25',
                'ar_description' => 'required|max:255',
            ]);
            $category->update($validatedData);
            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments/Restaurants/Categories/icons/'), $filename);
                $category->update([
                    'icon' => 'attachments/Restaurants/Categories/icons/'. $filename,
                ]);
            }
            DB::commit();
            return redirect()->back();
        }   catch (\Illuminate\Validation\ValidationException $e) {
            $request->session()->flash('edit_error', true);
            $request->session()->flash('edit_id', $id);
            return redirect()->back()->withErrors($e->validator, 'edit')->withInput();
        }

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        restaurnats_categories::where('id', $id)->delete();
        return redirect()->back();
    }
}
