<?php

namespace App\Http\Controllers\Admin;

use App\Models\icon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;




class ServicesContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $icons = icon::get();
        return view('Admin.icons', compact('icons'));
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
    DB::beginTransaction();
    
    try {
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $randomName = Str::random(10); // ????? ??? ?????? ?????
        $name = $randomName . '.' . $extension;
        $directoryPath = 'public/icons/images/'; // ???? ??? ?????? ??? ???? storage/app/public
        $filePath = $file->storeAs($directoryPath, $name); // ??? ??????

        $filePath = Storage::url($filePath); // ?????? ??? ??? URL ????? ??????

        icon::create([
            'name' => $request->name,
            'ar_name' => $request->ar_name,
            'image' => $filePath, // ??? ???? ?????? ?? ????? ????????
        ]);

        DB::commit();
        return redirect()->back()->with('success', 'Icon has been successfully added.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', $e->getMessage())->withInput();
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
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $randomName = Str::random(10); // ????? ??? ?????? ?????
                $name = $randomName . '.' . $extension;
                $directoryPath = 'public/icons/images/'; // ???? ??? ?????? ??? ???? storage/app/public
                $filePath = $file->storeAs($directoryPath, $name); // ??? ??????
        
                $filePath = Storage::url($filePath); // ?????? ??? ??? URL ????? ??????
                Type::where('id', $id)->update([
                'image' => $filePath,
            ]);
            }
            Type::where('id', $id)->update([
                'name' => $request->name,
                'ar_name' => $request->ar_name,
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
    public function icon_update(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $id = $request->icon_id;
        
        try {
            DB::beginTransaction();
            $menu = icon::where('id', $request->icon_id)->first();
          
            $menu->update([
                'name' => $request->name,
                'ar_name' => $request->ar_name,
            ]);
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $randomName = Str::random(10); // ????? ??? ?????? ?????
                $name = $randomName . '.' . $extension;
                $directoryPath = 'public/icons/images/'; // ???? ??? ?????? ??? ???? storage/app/public
                $filePath = $file->storeAs($directoryPath, $name); // ??? ??????
        
                $filePath = Storage::url($filePath); // ?????? ??? ??? URL ????? ??????
                $menu->update([
                    'image' => $filePath,
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
        icon::where('id', $id)->delete();
        return redirect()->back();
    }
}
