<?php

namespace App\Repositories\Interfaces;

interface AdminRepositoryInterface
{
    public function statistics();
    public function update_profile_admin($request); 
    public function admin_profile();
    public function all_notifications();
    public function getNotifications();
    public function markAsRead($request);
    public function filterRestaurantsAndUsers($request);
}
