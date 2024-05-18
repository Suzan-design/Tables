<?php

namespace App\Http\Controllers\Admin;

use App\Models\Invitation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\InvitationRepositoryInterface;

class InvitationController extends Controller
{
    protected $invitationRepository;

    public function __construct(InvitationRepositoryInterface $invitationRepository)
    {
        $this->invitationRepository = $invitationRepository;
    }

    public function index()
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $invitations = $this->invitationRepository->getAll();
        return view('Admin.Invitations.index', compact('invitations'));
    }

    public function create()
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        return view('Admin.Invitations.create');
    }

    public function store(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $validatedData = $request->validate([
            // 'number_inv' => 'required|unique:customerinvitations,number_inv',
        ]);


        try {
            DB::beginTransaction();
            $Invitation = Invitation::create([
                'expire'=>$request->expire,
                'discount'=>$request->discount,
                'title'=>$request->title,
                'type'=>$request->type,
                'target'=>$request->target,
                'description'=>$request->description,
                'coupons'=>$request->coupons,
                'limit'=>$request->limit,
            ]);
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                 $extension = $file->getClientOriginalExtension();
                $randomName = Str::random(6);
                $name = $randomName . '.' . $extension;
                $directoryPath = 'attachments/Invitations/image/' . $request->limit . '/';
                $filePath = $file->storeAs($directoryPath, $name, 'upload_images');
                $Invitation->update([
                    'image' => $filePath,
                ]);
                DB::commit();
                return $request->input('action') === 'more_add'
                    ? redirect()->route('invitations.create')
                    : redirect()->route('invitations.index');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'error');
        }
    }
    public function show(string $id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $invitation = $this->invitationRepository->findById($id);
        return view('Admin.Invitations.show', compact('invitation'));
    }
    public function edit(string $id)
    {
    }
    public function update(Request $request, string $id)
    {
    }
    public function destroy(string $id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $this->invitationRepository->delete($id);
        return redirect()->route('invitations.index');
    }
}
