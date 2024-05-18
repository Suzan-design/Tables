<?php
namespace App\Services;

use App\Models\ServiceProvider\ServiceProvider;
use App\Models\ServiceProvider\ServiceProvidersAlbums;
use App\Models\User\MobileUser;
use App\Traits\FileStorageTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ServiceProviderService
{
    use FileStorageTrait;

    public function getAllServiceProviders()
    {
        return ServiceProvider::select('id', 'user_id', 'category_id', 'location_work_governorate', 'address' , 'address_ar', 'start_work', 'end_work')->paginate(15);
    }

    public function createServiceProvider($data, $albums)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            $user = MobileUser::find($data['user_id']);

            $user->update([
               'type' => 'service_provider'
            ]);

            $data['profile'] = $this->storefile($data['profile'], 'ServiceProviderProfileImages');
            $data['cover'] = $this->storefile($data['cover'], 'ServiceProviderProfileImages');
            $data['type'] = 'Approved' ;
            $serviceProvider = ServiceProvider::create($data);

            foreach ($albums as $albumData) {
                $album = new ServiceProvidersAlbums();
                $album->name = $albumData['name'];
                $album->service_provider_id = $serviceProvider->id;

                if (isset($albumData['imageFiles']))
                    $album->images = json_encode($this->handleFiles($albumData['imageFiles'], 'ServiceProviderImages'));

                if (isset($albumData['videoFiles']))
                    $album->videos = json_encode($this->handleFiles($albumData['videoFiles'], 'ServiceProviderVideos'));

                $album->save();
            }

            // Commit transaction
            DB::commit();
            return $serviceProvider;

        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollback();
            throw $e;
        }
    }

    public function updateServiceProvider(ServiceProvider $serviceProvider, $data)
    {
        $serviceProvider->update($data);
    }

    public function deleteServiceProvider(ServiceProvider $serviceProvider)
    {
        $serviceProvider->delete();
    }

    protected function handleFiles($files, $folder)
    {
        $paths = [];
        foreach ($files as $file) {
            $paths[] = $this->storefile($file, $folder);
        }
        return $paths;
    }
}
