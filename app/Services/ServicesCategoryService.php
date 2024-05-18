<?php

namespace App\Services;

use App\Models\ServiceProvider\ServicesCategory;
use App\Traits\FileStorageTrait;
use Illuminate\Support\Facades\Storage;

class ServicesCategoryService
{
    use FileStorageTrait;

    public function getAllServiceCategories()
    {
        return ServicesCategory::all();
    }

    public function createServiceCategory($data)
    {
        $path = $this->storefile($data['icon'], 'ServiceCategoryImages');
        $data['icon'] = $path;
        return ServicesCategory::create($data);
    }

    public function updateServiceCategory($request ,$id)
    {
        $ServicesCategory = ServicesCategory::findOrFail($id);

        $ServicesCategory->title = $request->title;
        $ServicesCategory->title_ar = $request->title_ar;
        $ServicesCategory->description = $request->description;
        $ServicesCategory->description_ar = $request->description_ar;

        if ($request->hasFile('icon')) {
            Storage::disk('public')->delete($ServicesCategory->icon);
            $path = $this->storefile($request->file('icon') , 'ServiceCategoryImages') ;
            $ServicesCategory->icon = $path ;
        }

        $ServicesCategory->save();
    }

    public function deleteServiceCategory(ServicesCategory $servicesCategory)
    {
        $servicesCategory->delete();
    }
}
