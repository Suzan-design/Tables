<?php

namespace App\Services;

use App\Models\User\MobileUser;
use App\Models\User\Organizer;
use App\Scopes\ExcludeAttributeScope;

class OrganizerRequestService
{
    public function getAllOrganizerRequests()
    {
        $results = Organizer::withoutGlobalScope(ExcludeAttributeScope::class)
            ->with(['mobileUser' , 'categories'])
            ->where('type', 'pending')
            ->get();
        return $results ;
    }

    public function updateOrganizerRequest($id, $data)
    {
        $organizer = Organizer::withoutGlobalScope(ExcludeAttributeScope::class)->find($id);
        $organizer->update($data);
        return $organizer;
    }

    public function deleteOrganizerRequest($id)
    {
        $organizer = Organizer::withoutGlobalScope(ExcludeAttributeScope::class)
            ->with('mobileUser')
            ->find($id);

        $user = MobileUser::find($organizer->mobileUser->id);

        $user->update([
            'type' => 'normal'
        ]);

        $organizer->delete();
    }
}
