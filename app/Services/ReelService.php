<?php

namespace App\Services;

use App\Models\Common\Reel;
use App\Traits\FileStorageTrait;

class ReelService
{
    use FileStorageTrait;

    public function getReels()
    {
        return Reel::whereHas('event')
            ->orWhereHas('user')
            ->orWhereHas('venue')
            ->select('id', 'venue_id', 'user_id', 'event_id')
            ->paginate(15);
    }

    public function searchReels($type, $id)
    {
        $columnName = $type . '_id';
        return Reel::where($columnName, $id)->paginate(15);
    }

    public function storeReel($data, $imageFiles, $videoFiles)
    {
        $imagePaths =null ; $videoPaths = null ;

        if ($imageFiles)
            $imagePaths = $this->handleFiles($imageFiles, 'ReelImages');

        if($videoFiles)
            $videoPaths = $this->handleFiles($videoFiles, 'ReelVideo');

        if ($imagePaths) {
            $data['images'] = json_encode($imagePaths);
        }
        if ($videoPaths) {
            $data['videos'] = json_encode($videoPaths);
        }

        return Reel::create($data);
    }

    public function updateReel(Reel $reel, $data)
    {
        $reel->update($data);
    }

    public function deleteReel(Reel $reel)
    {
        $reel->delete();
    }

    protected function handleFiles($files, $folder)
    {
        $paths = [];
        if ($files) {
            foreach ($files as $file) {
                $path = $this->storefile($file, $folder);
                $paths[] = $path;
            }
        }
        return $paths;
    }
}
