<?php
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\ReelRequest;
use App\Models\Common\Reel;
use App\Models\Event\Event;
use App\Models\Venue\Venue;
use App\Services\ReelService;
use Illuminate\Http\Request;

class ReelController extends Controller
{
    protected $reelService;

    public function __construct(ReelService $reelService)
    {
        $this->reelService = $reelService;
    }

    public function index()
    {
        $reels = $this->reelService->getReels();
        return view('reels.index', compact('reels'));
    }

    public function create()
    {
        $events = Event::select('id','title')->get() ;
        $venues = Venue::select('id','name')->get() ;

        return view('reels.create', compact('events','venues'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:user,venue,event',
            'id' => 'required|integer'
        ]);

        $reels = $this->reelService->searchReels($request->input('type'), $request->input('id'));
        return view('reels.index', compact('reels'));
    }

    public function store(ReelRequest $request)
    {
        $this->reelService->storeReel($request->all(), $request->file('images'), $request->file('videos'));
        return redirect()->route('reels.index')->with('success', 'Reel created successfully.');
    }

    public function show(Reel $reel)
    {
        return view('reels.show', compact('reel'));
    }

    public function edit(Reel $reel)
    {
        return view('reels.edit', compact('reel'));
    }

    public function update(ReelRequest $request, Reel $reel)
    {
        $this->reelService->updateReel($reel, $request->all());
        return redirect()->route('reels.index')->with('success', 'Reel updated successfully.');
    }

    public function destroy(Reel $reel)
    {
        $this->reelService->deleteReel($reel);
        return redirect()->route('reels.index')->with('success', 'Reel deleted successfully.');
    }
}
