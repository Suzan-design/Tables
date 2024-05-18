<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User\MobileUser;
use App\Models\User\Organizer;
use App\Models\User\OrganizerAlbum;
use App\Traits\FileStorageTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrganizerController extends Controller
{
    use FileStorageTrait ;
    public function become_organizer(Request $request)
    {
        DB::beginTransaction();

        try {

            $user = Auth::user();

            $albumsData = $this->extractAlbumData($request);

            if ($user->type == 'organizer') {
                return response()->json([
                    'status' => false,
                    'message' => 'you are already organizer'
                ]);
            }
            if ($user->type == 'service_provider') {
                return response()->json([
                    'status' => false,
                    'message' => 'you are Service Provider'
                ]);
            }
            $user->update([
                'type' => 'organizer'
            ]);

            $data = $request->only('name', 'bio', 'services', 'other_category','state', 'category_ids');

            if ($request->hasFile('profile') && $request->file('profile')->isValid()) {
                $data['profile'] = $this->storefile($request->file('profile'), 'OrganizerProfile');
            }
            if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
                $data['cover'] = $this->storefile($request->file('cover'), 'OrganizerCover');
            }

            // Create Organizer
            $organizer = Organizer::create([
                'mobile_user_id' => Auth::id(),
                'name' => $data['name'],
                'bio' => $data['bio'],
                'services' => $data['services'],
                'other_category' => $data['other_category'],
                'state' => $data['state'],
                'profile' => $data['profile'] ?? null, // Use null if profile is not set
                'cover' => $data['cover'] ?? null,   // Use null if cover is not set
                'type' => 'pending'
            ]);

            if (!empty($data['category_ids'])) {
                $organizer->categories()->attach($data['category_ids']);
            }
            foreach ($albumsData as $albumData) {
                $album = new OrganizerAlbum();
                $album->name = $albumData['name'];
                $album->organizer_id = $organizer->id;

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

    public function organizer_followers($id)
    {
        $followers = MobileUser::with(['followers:id,first_name,last_name'])
            ->find($id);
        return response()->json([
            'status' => true ,
            'followers' => $followers
        ]);
    }

    public function organizer_profile($id)
    {
        $user = MobileUser::with(['organizerInfo', 'organizedEvents', 'organizerInfo.albums' , 'organizerInfo.categories'])
            ->withCount(['following', 'followers', 'organizedEvents'])
            ->find($id);

        if ($user) {
            $response = [
                'id' => $user->id,
                'following_count' => $user->following_count,
                'followers_count' => $user->followers_count,
                'organized_events_count' => $user->organized_events_count,
                'organizer_info' => $user->organizerInfo,
                'organized_events'=> $user->organizedEvents
            ];

            return response()->json([
                'status' => true,
                'organizer' => $response
            ]);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }



}
