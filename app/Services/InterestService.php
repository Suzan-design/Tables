<?php

namespace App\Services;

use App\Models\Common\Interest;
use Illuminate\Support\Facades\Storage;

class InterestService
{
    public function getAllInterests()
    {
        return Interest::all();
    }

    public function storeInterest($request, $fileStorageTrait)
    {
        $path = $fileStorageTrait->storefile($request->file('icon'), 'AmenityImages');
        return Interest::create([
            'title' => $request['title'],
            'title_ar' => $request['title_ar'],
            'icon' => $path
        ]);
    }

    public function updateInterest($request, $fileStorageTrait ,$id)
    {
        $interest = Interest::findOrFail($id);

        $interest->title = $request->title;
        $interest->title_ar = $request->title_ar;

        if ($request->hasFile('icon')) {
            Storage::disk('public')->delete($interest->icon);
            $path = $fileStorageTrait->storefile($request->file('icon') , 'AmenityImages') ;
            $interest->icon = $path ;
        }

        $interest->save();
    }

    public function deleteInterest(Interest $interest)
    {
        $interest->delete();
    }
}
