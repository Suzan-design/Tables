<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\Services\ServicesCategoryRequest;
use App\Models\ServiceProvider\ServicesCategory;
use App\Services\ServicesCategoryService;

class ServicesCategoryController extends Controller
{
    protected $servicesCategoryService;

    public function __construct(ServicesCategoryService $servicesCategoryService)
    {
        $this->servicesCategoryService = $servicesCategoryService;
    }

    public function index()
    {
        $ServicesCategory = $this->servicesCategoryService->getAllServiceCategories();
        return view('service_categories.index', compact('ServicesCategory'));
    }

    public function create()
    {
        return view('service_categories.create');
    }

    public function store(ServicesCategoryRequest $request)
    {
        $this->servicesCategoryService->createServiceCategory($request->all());
        return redirect()->route('services-categories.index')->with('success', 'ServicesCategory created successfully.');
    }

    public function show(ServicesCategory $ServicesCategory)
    {
        return view('service_categories.show', compact('ServicesCategory'));
    }

    public function edit(ServicesCategory $ServicesCategory)
    {
        return view('service_categories.edit', compact('ServicesCategory'));
    }
    public function update(ServicesCategoryRequest $request, $id)
    {
        $this->servicesCategoryService->updateServiceCategory($request ,$id);
        return redirect()->route('services-categories.index')->with('success', 'ServicesCategory updated successfully');
    }

    public function destroy(ServicesCategory $servicesCategory)
    {
        $this->servicesCategoryService->deleteServiceCategory($servicesCategory);
        return redirect()->route('services-categories.index')->with('success', 'ServiceCategory deleted successfully.');
    }
}
