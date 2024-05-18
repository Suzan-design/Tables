<?php
namespace App\Services;

use App\Models\Venue\Venue;
use App\Models\Venue\VenueAlbum;
use App\Traits\FileStorageTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VenueService
{
    use FileStorageTrait;

    public function getAllVenues()
    {
        return Venue::select('id', 'name' , 'name_ar', 'capacity', 'governorate', 'contact_number')->paginate(10);
    }

    public function createVenue($data, $albums)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            $image = $this->storefile($data['profile'], 'VenueProfileImages');
            $data['profile'] = $image;

            $venue = Venue::create($data);

            foreach ($albums as $albumData) {
                $album = new VenueAlbum();
                $album->name = $albumData['name'];
                $album->venue_id = $venue->id;

                if (isset($albumData['imageFiles']))
                    $album->images = json_encode($this->handleFiles($albumData['imageFiles'], 'VenueImages'));

                if (isset($albumData['videoFiles']))
                    $album->videos = json_encode($this->handleFiles($albumData['videoFiles'], 'VenueVideos'));

                $album->save();
            }

            // Commit transaction
            DB::commit();
            return $venue;
        }catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollback();
            throw $e;
        }

    }

    public function updateVenue(Venue $venue, $data)
    {
        $venue->update($data);
    }

    public function deleteVenue(Venue $venue)
    {
        $venue->delete();
    }


}
