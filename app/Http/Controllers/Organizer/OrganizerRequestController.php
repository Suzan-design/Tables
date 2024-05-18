<?php

namespace App\Http\Controllers\Organizer;

use App\Events\NotificationEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Event\RequestedEvent\RequestedEventUpdateRequest;
use App\Models\Event\EventRequest;
use App\Models\User\MobileUser;
use App\Models\User\Organizer;
use App\Scopes\ExcludeAttributeScope;
use App\Services\OrganizerRequestService;
use Illuminate\Http\Request;

class OrganizerRequestController extends Controller
{
    protected $organizerRequestService;

    public function __construct(OrganizerRequestService $organizerRequestService)
    {
        $this->organizerRequestService = $organizerRequestService;
    }

    public function index()
    {
        $organizers = $this->organizerRequestService->getAllOrganizerRequests();
        return view('organizer_request.index', compact('organizers'));
    }

    public function show($id)
    {
        $organizer =  Organizer::withoutGlobalScope(ExcludeAttributeScope::class)->with(['mobileUser' , 'categories' ,'albums'])->find($id);
        return view('organizer_request.show', compact('organizer'));
    }

    public function update(Request $request,$id)
    {
        $this->organizerRequestService->updateOrganizerRequest($id, $request->all());
        return redirect()->route('organizer-requests.index');
    }

    public function destroy($id)
    {
        $this->organizerRequestService->deleteOrganizerRequest($id);
        return redirect()->route('organizer-requests.index')->with('success', 'Organizer Request deleted successfully.');
    }
}
