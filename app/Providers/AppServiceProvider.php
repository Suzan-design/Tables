<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\CuisineRepositoryInterface;
use App\Repositories\CuisineRepository;

use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\AdminRepository;

use App\Repositories\Interfaces\ApiAuthRepositoryInterface;
use App\Repositories\ApiAuthRepository;

use App\Repositories\Interfaces\OfferRepositoryInterface;
use App\Repositories\OfferRepository;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\RoleRepository;

use App\Repositories\Interfaces\PromocodeRepositoryInterface;
use App\Repositories\PromocodeRepository;

use App\Repositories\Interfaces\InvitationRepositoryInterface;
use App\Repositories\InvitationRepository;

use App\Repositories\Interfaces\ApiRestaurantRepositoryInterface;
use App\Repositories\ApiRestaurantRepository;

use App\Repositories\Interfaces\TableRepositoryInterface;
use App\Repositories\TableRepository;


use App\Repositories\Interfaces\ApiHomeRepositoryInterface;
use App\Repositories\ApiHomeRepository;


use App\Repositories\Interfaces\ApiReservationsRepositoryInterface;
use App\Repositories\ApiReservationsRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CuisineRepositoryInterface::class, CuisineRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(ApiAuthRepositoryInterface::class, ApiAuthRepository::class);
        $this->app->bind(OfferRepositoryInterface::class, OfferRepository::class);
        $this->app->bind(UserRepositoryInterface::class, userRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, roleRepository::class);
        $this->app->bind(PromocodeRepositoryInterface::class, promocodeRepository::class);
        $this->app->bind(InvitationRepositoryInterface::class, invitationRepository::class);
        $this->app->bind(ApiRestaurantRepositoryInterface::class, ApiRestaurantRepository::class);
        $this->app->bind(TableRepositoryInterface::class, tableRepository::class);
        $this->app->bind(ApiHomeRepositoryInterface::class, ApiHomeRepository::class);
        $this->app->bind(ApiReservationsRepositoryInterface::class, ApiReservationsRepository::class);


    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
