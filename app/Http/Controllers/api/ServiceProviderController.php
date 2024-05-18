<?php

namespace App\Http\Controllers\api;
use App\Models\ServiceProvider\ServiceProvider;
use App\Models\ServiceProvider\ServiceProvidersAlbums;
use App\Models\ServiceProvider\ServicesCategory;
use App\Traits\FileStorageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceProviderController extends Controller
{
    use FileStorageTrait ;
    public function become_service_provider(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $albumsData = $this->extractAlbumData($request);

            if ($user->type == 'service_provider') {
                return response()->json([
                    'status' => true,
                    'message' => 'you are already service provider'
                ]);
            }
            $user->update([
                'type' => 'service_provider'
            ]);

            $data = $request->only('name', 'bio', 'location_work_governorate' ,'category_id' , 'description' , 'profile' , 'cover','latitude', 'longitude');

            if ($request->hasFile('profile') && $request->file('profile')->isValid()) {
                $data['profile'] = $this->storefile($request->file('profile'), 'ServiceProviderProfile');
            }
            if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
                $data['cover'] = $this->storefile($request->file('cover'), 'ServiceProviderCover');
            }

            // Create Organizer
            $service_provider = ServiceProvider::create([
                'user_id' => Auth::id(),
                'name' => $data['name'],
                'name_ar' => $data['name'],
                'bio' => $data['bio'],
                'bio_ar' => $data['bio'],
                'location_work_governorate' => $data['location_work_governorate'],
                'profile' => $data['profile'] ?? null, // Use null if profile is not set
                'cover' => $data['cover'] ?? null,   // Use null if cover is not set
                'description' => $data['description'] ,
                'description_ar' => $data['description'] ,
                'address' => $data['location_work_governorate'] ,
                'address_ar' => $data['location_work_governorate'] ,
                'category_id'=> $data['category_id'],
                'latitude'=> $data['latitude'],
                'longitude'=> $data['longitude'],
                'type' => 'pending'
            ]);

            foreach ($albumsData as $albumData) {
                $album = new ServiceProvidersAlbums();
                $album->name = $albumData['name'];
                $album->service_provider_id = $service_provider->id;

                if (isset($albumData['imageFiles']))
                    $album->images = json_encode($this->handleFiles($albumData['imageFiles'], 'OrganizerImages'));

                if (isset($albumData['videoFiles']))
                    $album->videos = json_encode($this->handleFiles($albumData['videoFiles'], 'OrganizerVideos'));

                $album->save();
            }
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'created successfully'
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
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

    public function show($id)
    {
        $service_provider = ServiceProvider::with('albums')->find($id) ;
        if ($service_provider)
            return response()->json([
               'status'=> true ,
               'message'=> $service_provider
            ]);
        else
            return response()->json([
                'status'=> false ,
                'message'=> 'not found'
            ]);
    }

    public function service_category()
    {
        return response()->json([
           'status' =>true ,
           'category'  => ServicesCategory::all()
        ]);
    }

    public function serviceProviderAccordingCategory($id)
    {
        $service_provider = ServiceProvider::with(['user:id,first_name,last_name,phone_number', 'albums'])
            ->where('category_id', $id)
            ->get();
        return response()->json([
           'status'=>true ,
           'service_provider' => $service_provider
        ]);
    }
}
