<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\Services\ServiceProviderRequest;
use App\Models\ServiceProvider\ServiceProvider;
use App\Models\ServiceProvider\ServicesCategory;
use App\Services\ServiceProviderService;


class ServiceProviderController extends Controller
{
    protected $serviceProviderService;

    public function __construct(ServiceProviderService $serviceProviderService)
    {
        $this->serviceProviderService = $serviceProviderService;
    }

    public function index()
    {
        $serviceProviders = $this->serviceProviderService->getAllServiceProviders();
        return view('service_providers.index', compact('serviceProviders'));
    }

    public function create()
    {
        $categories = ServicesCategory::all();
        return view('service_providers.create', compact('categories'));
    }

    public function store(ServiceProviderRequest $request)
    {
        $albumData = $this->extractAlbumData($request);
        $serviceProviderData = $request->all();

        // Assuming extractAlbumData returns an array of album information
        $this->serviceProviderService->createServiceProvider($serviceProviderData, $albumData);

        return redirect()->route('service-providers.index')->with('success', 'ServiceProvider created successfully.');
    }

    private function extractAlbumData($request) {
        $albums = [];

        // Assuming each album's data is prefixed with 'album-', like 'album-1-name', 'album-1-images', etc.
        foreach ($request->all() as $key => $value) {
            if (preg_match('/album-\d+-name/', $key)) {
                $albumIndex = explode('-', $key)[1];
                $albumName = $value;
                $imageFiles = $request->file("album-$albumIndex-images");
                $videoFiles = $request->file("album-$albumIndex-videos");

                $albums[] = [
                    'name' => $albumName,
                    'imageFiles' => $imageFiles,
                    'videoFiles' => $videoFiles
                ];
            }
        }

        return $albums;
    }


    public function show(ServiceProvider $serviceProvider)
    {
        $categories = ServicesCategory::all();
        return view('service_providers.show', compact('serviceProvider', 'categories'));
    }

    public function edit(ServiceProvider $serviceProvider)
    {
        $categories = ServicesCategory::all();
        return view('service_providers.edit', compact('serviceProvider', 'categories'));
    }

    public function update(ServiceProviderRequest $request, ServiceProvider $serviceProvider)
    {
        $this->serviceProviderService->updateServiceProvider($serviceProvider, $request->all());
        return redirect()->route('service-providers.index')->with('success', 'ServiceProvider updated successfully.');
    }

    public function destroy(ServiceProvider $serviceProvider)
    {
        $this->serviceProviderService->deleteServiceProvider($serviceProvider);
        return redirect()->route('service-providers.index')->with('success', 'ServiceProvider deleted successfully.');
    }
}
