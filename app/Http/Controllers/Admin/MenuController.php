<?php

namespace App\Http\Controllers\Admin;

use App\Models\Type;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $menus = Type::get();
        return view('Admin.menus', compact('menus'));
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
                'name' => 'required|max:255|unique:types,name',
                'ar_name' => 'required|max:255|unique:types,ar_name',
                'symbol' => 'required',
            ]);
            $file = $request->file('symbol');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('attachments/Restaurants/Menus/symbols/'), $filename);
            
            Type::create([
                'name' => $request->name,
                'ar_name' => $request->ar_name,
                'symbol' => 'attachments/Restaurants/Menus/symbols/'. $filename,
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
            if ($request->hasFile('symbol')) {
                $file = $request->file('symbol');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('attachments/Restaurants/Menus/symbols/'), $filename);
            }
            Type::where('id', $id)->update([
                'name' => $request->name,
                'ar_name' => $request->ar_name,
                'symbol' => 'attachments/Restaurants/Menus/symbols/'. $filename,
            ]);
            DB::commit();
            return redirect()->back();
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            $request->session()->flash('edit_error', true);
            $request->session()->flash('edit_id', $id);
            return redirect()->back()->withErrors($e->validator, 'edit')->withInput();
        }
    }
    public function menu_update(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $id = $request->menu_id;
        try {
            DB::beginTransaction();
            $menu = Type::where('id', $request->menu_id)->first();
            $validatedData = $request->validateWithBag('edit', [
                'name' => 'required|string|unique:types,name,' . $id,
                'ar_name' => 'required|string|unique:types,name,' . $id,
            ]);
            $menu->update([
                'name' => $request->name,
                'ar_name' => $request->ar_name,
            ]);
            if ($request->hasFile('symbol')) {
            $file = $request->file('symbol');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('attachments/Restaurants/Menus/symbols/'), $filename);
                $menu->update([
                    'symbol' => 'attachments/Restaurants/Menus/symbols/'. $filename,
                ]);
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
        Type::where('id', $id)->delete();
        return redirect()->back();
    }
}
