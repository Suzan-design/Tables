<?php

namespace App\Repositories;

use App\Models\Invitation;
use App\Repositories\Interfaces\InvitationRepositoryInterface;

class InvitationRepository implements InvitationRepositoryInterface
{
    public function getAll()
    {
        return Invitation::all();
    }
    public function findById($id)
    {
        return Invitation::findOrFail($id);
    }
    public function create($data)
    {
        return Invitation::create($data);
    }
    public function update($data, $id)
    {
        $invitation = $this->findById($id);
        $invitation->update($data);
        return $invitation;
    }
    public function delete($id)
    {
        $invitation = $this->findById($id);
        $invitation->delete();
    }
}
