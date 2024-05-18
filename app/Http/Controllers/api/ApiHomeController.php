<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\BaseController;
use App\Models\Table;
use Illuminate\Support\Facades\DB;
use Hash;
use App\Notifications\New_Reservation;
use Carbon\Carbon;
use App\Models\images_offers;
use App\Models\Cuisine;
use App\Models\offer;
use App\Models\Menu;
use App\Models\Image;
use App\Models\Reservation;
use App\Models\Reviews;
use App\Models\Customer;
use App\Models\Invitation;
use App\Models\Promocode;
use App\Models\Restaurant;
use App\Models\res_prompcodes;
use App\Repositories\Interfaces\ApiHomeRepositoryInterface;

class ApiHomeController extends BaseController
{
    protected $ApiHomeRepository;

    public function __construct(ApiHomeRepositoryInterface $ApiHomeRepository)
    {
        $this->ApiHomeRepository = $ApiHomeRepository;
    }

    public function map_res(Request $request)
    {
        $map_res = $this->ApiHomeRepository->map_res($request);
        return $this->sendResponse($map_res, 'map_res');
    }

    public function details_offer($id)
    {
        $offer = $this->ApiHomeRepository->details_offer($id);

        $pathMain = null;
        $filteredImages = [];

        foreach ($offer->images as $image) {
            if ($image->type === 'main') {
                $pathMain = $image->filename;
            } elseif ($image->type === 'others') {
                $filteredImages[] = $image;
            }
        }
        $modifiedOffer = [
            'id' => $offer->id,
            'Restaurant_name' => $offer->Restaurant->name,
            'Restaurant_id' => $offer->Restaurant_id,
            'price_old' => $offer->price_old,
            'price_new' => $offer->price_new,
            'description' => $offer->description,
            'name' => $offer->name,
            'ar_description' => $offer->ar_description,
            'ar_name' => $offer->ar_name,
            'type' => $offer->type,
            'start_date' => $offer->start_date,
            'status' => $offer->status,
            'featured' => $offer->featured,
            'ar_featured' => $offer->ar_featured,
            'path_main' => $pathMain,
            'created_at' => $offer->created_at,
            'updated_at' => $offer->updated_at,
            'images' => $filteredImages,
        ];
        return $this->sendResponse($modifiedOffer, 'details_offer');
    }
    public function details_category($id)
    {
        $category_restaurants = $this->ApiHomeRepository->details_category($id);
        return $this->sendResponse($category_restaurants, 'category_restaurants');
    }
    public function current_coupons()
    {
        $customer = Customer::where('id', Auth::guard('customer-api')->id())->first();

        // التحقق من وجود promocodes وأنها ليست فارغة
        $promocodes = [];
        if (!empty($customer->promocodes)) {
            $promocodes = Promocode::whereIn('id', $customer->promocodes)
                ->select('id', 'description', 'code', 'discount', 'limit', 'start_date', 'end_date', 'image', 'type')
                ->get();
        }

        $invitations = Invitation::select('customerinvitations.id', 'customerinvitations.discount', 'customerinvitations.title', 'customerinvitations.description', 'customerinvitations.coupons', 'customerinvitations.target', 'customerinvitations.type')
        ->leftJoin('customers', function ($join) {
            $join->on('customerinvitations.target', '=', 'customers.id');
        })
        ->get()
        ->map(function ($invitation) use ($customer)  {
            switch ($invitation->type)  {

                case 'invitations':
                    $invitation->user_count = $customer->numberOfInvitations ?? 0;
                    break;
                case 'reviews':
                    $invitation->user_count = $customer->numberOfReviews ?? 0;
                    break;
                case 'reservations':
                    $invitation->user_count = $customer->numberOfReservations ?? 0;
                    break;
            }

            return $invitation;
        });
        return $this->sendResponse(['Coupons' => $promocodes, 'Achievments' => $invitations], 'promocodes and invitations');
    }

    public function achievements()
    {
        $invitations = Invitation::select('id', 'discount', 'title', 'description', 'coupons')->get();
        return $this->sendResponse($invitations, 'invitations');
    }
    public function accept_invities()
    {
        $customer = Customer::where('id', Auth::guard('customer-api')->id())->first();
        $invitiers=Customer::where('id', $customer->userOfInvitations)->select('firstname','lastname','profilePicture')->get();
        return $this->sendResponse($invitiers, 'invitiers');
    }

}
