<?php

namespace App\Http\Controllers\Admin;

use App\Models\offer;
use Illuminate\Support\Str;
use App\Models\images_offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Interfaces\OfferRepositoryInterface;

class OfferController extends Controller
{
    private $offerRepository;

    public function __construct(OfferRepositoryInterface $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function index(Request $request)
    {

if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $offers = offer::where(['Restaurant_id' => $request->res_id, 'type' => 'offer'])->with('images')->get();
        $new_opening = offer::where(['Restaurant_id' => $request->res_id, 'type' => 'new_opening'])->with('images')->get();
        $res_id = $request->res_id;
        return view('Admin.Offers.index', compact('offers', 'new_opening', 'res_id'));
    }
    public function store(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
            DB::beginTransaction();
            $offer = Offer::create([
                'price_old' => $request->price_old ?? '-',
                'price_new' => $request->price_new ?? '-',
                'description' => $request->description,
                'ar_description' => $request->ar_description,
                'name' => $request->name,
                'ar_name' => $request->ar_name,
                'featured' => $request->featured ?? '-',
                'ar_featured' => $request->ar_featured ?? '-',
                'Restaurant_id' => $request->Restaurant_id,
                'type' => $request->type,
                'start_date' => $request->start_date ?? '-',
            ]);
            
            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments/Offers/covers/'), $filename);
                
                images_offer::create([
                    'filename' => 'attachments/Offers/covers/' . $filename,
                    'type' => 'cover',
                    'imageable_id' => $offer->id,
                    'imageable_type' => Offer::class
                ]);
            }
            if ($request->hasFile('main')) {
                $file = $request->file('main');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments/Offers/main/'), $filename);
                
                images_offer::create([
                    'filename' => 'attachments/Offers/main/' . $filename,
                    'type' => 'main',
                    'imageable_id' => $offer->id,
                    'imageable_type' => Offer::class
                ]);
            }
            if ($request->hasfile('others')) {
                foreach ($request->file('others') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments/Offers/others/'), $filename);
                
                    images_offer::create([
                        'filename' => 'attachments/Offers/others/' . $filename,
                        'type' => 'others',
                        'imageable_id' => $offer->id,
                        'imageable_type' => Offer::class
                    ]);
                }
            }
            DB::commit();
            return redirect()->back();
        
    }

    public function act_inact__offer($id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        try {
            DB::beginTransaction();
            $offer = Offer::find($id);
            $newStatus = $offer->status === 'active' ? 'inactive' : 'active';
            $offer->update(['status' => $newStatus]);
            DB::commit();
            return redirect()->back()->with('success', 'Offer status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'error');
        }
    }
    public function offer_update(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $offer = Offer::where('id', $request->offer_id)->first();
        try {
            DB::beginTransaction();
            $offer->update([
                'price_old' => $request->price_old ?? '-',
                'price_new' => $request->price_new ?? '-',
                'description' => $request->description,
                'name' => $request->name,
                'featured' => $request->featured ?? '-',
                'ar_featured' => $request->ar_featured ?? '-',
                'type' => $request->type,
                'start_date' => $request->start_date ?? '-',
            ]);
            if ($request->hasFile('cover')) {
                $oldImage = $offer->images()->where('type', 'cover')->first();
                Storage::delete($oldImage->filename);
                $oldImage->delete();

                $file = $request->file('cover');
               $file = $request->file('cover');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments/Offers/covers/'), $filename);
                
                images_offer::create([
                    'filename' => 'attachments/Offers/covers/' . $filename,
                    'type' => 'cover',
                    'imageable_id' => $offer->id,
                    'imageable_type' => Offer::class
                ]);
            }
            if ($request->hasFile('main')) {
                $oldImage = $offer->images()->where('type', 'main')->first();
                Storage::delete($oldImage->filename);
                $oldImage->delete();
                 $file = $request->file('main');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments/Offers/main/'), $filename);
                
                images_offer::create([
                    'filename' => 'attachments/Offers/main/' . $filename,
                    'type' => 'main',
                    'imageable_id' => $offer->id,
                    'imageable_type' => Offer::class
                ]);
            }
            if ($request->hasfile('others')) {
                $oldImages = $offer->images()->where('type', 'others')->get();
                foreach ($oldImages as $image) {
                    Storage::delete($image->filename);
                    $image->delete();
                }
                foreach ($request->file('others') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('attachments/Offers/others/'), $filename);
                
                    images_offer::create([
                        'filename' => 'attachments/Offers/others/' . $filename,
                        'type' => 'others',
                        'imageable_id' => $offer->id,
                        'imageable_type' => Offer::class
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('offers_index', $offer->Restaurant_id);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'error');
        }
    }
    public function show($id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $offer = Offer::where('id', $id)->with('images')->first();
        if ($offer->type == "offer") {
            return view('Admin.Offers.show', compact('offer'));
        } else {
            return view('Admin.Offers.new_opening', compact('offer'));
        }
    }
    
    public function destroy($id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $offer = Offer::where('id', $id)->delete();
        return redirect()->back();
        
    }
}
