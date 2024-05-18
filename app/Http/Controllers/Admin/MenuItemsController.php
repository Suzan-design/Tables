<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\Type;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MenuItemsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $types = Type::get();
        $restaurant_id = $request->res_id;

        $items = Menu::where('restaurant_id', $restaurant_id)->get();
        return view('Admin.Restaurants.menuItems', compact('items', 'types', 'restaurant_id'));
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
            $validatedData = $request->validateWithBag('add', [
                'name' => 'required|max:255|unique:menuitems,name',
                'ar_name' => 'required|max:255|unique:menuitems,ar_name',
                'type_id' => 'required',
                'icon' => 'required',
            ]);
             $file = $request->file('icon');
             $filename = time() . '_' . $file->getClientOriginalName();
             $file->move(public_path('attachments/Restaurants/MenusItems/icons/'), $filename);
            
            Menu::create([
                'restaurant_id' => $request->restaurant_id,
                'type_id' => $request->type_id,
                'name' => $request->name,
                'ar_name' => $request->ar_name,
                'icon' => 'attachments/Restaurants/MenusItems/icons/'. $filename,
            ]);
            DB::commit();
            return redirect()->back()->with('modalOpen', true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
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
            $menu = Menu::where('id', $id)->first();
            $menu->update([
                'type_id' => $request->type_id,
                'name' => $request->name,
                'ar_name' => $request->ar_name,
            ]);
            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
             $filename = time() . '_' . $file->getClientOriginalName();
             $file->move(public_path('attachments/Restaurants/MenusItems/icons/'), $filename);
                $menu->update(['icon' => 'attachments/Restaurants/MenusItems/icons/'. $filename]);
            } 
            DB::commit();

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'error');
        }
    }
    public function item_update(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $id = $request->item_id;
        try {
            $validatedData = $request->validateWithBag('edit', [
                'name' => 'required|unique:menuitems,name,' . $id,
            ]);
            DB::beginTransaction();
            $menu = Menu::where('id', $id)->first();
            $menu->update([
                'type_id' => $request->type_id,
                'name' => $request->name,
                'ar_name' => $request->ar_name,
            ]);
            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
             $filename = time() . '_' . $file->getClientOriginalName();
             $file->move(public_path('attachments/Restaurants/MenusItems/icons/'), $filename);
                $menu->update(['icon' => 'attachments/Restaurants/MenusItems/icons/'. $filename]);
            }
            DB::commit();
            return redirect()->back();
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
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
        Menu::where('id', $id)->delete();
        return redirect()->back();
    }
}
