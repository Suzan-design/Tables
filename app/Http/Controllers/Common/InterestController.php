<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\InterestRequest;
use App\Models\Common\Interest;
use App\Services\InterestService;
use App\Traits\FileStorageTrait;

class InterestController extends Controller
{
    use FileStorageTrait;

    protected $interestService;

    public function __construct(InterestService $interestService)
    {
        $this->interestService = $interestService;
    }

    public function index()
    {
        $interests = $this->interestService->getAllInterests();
        return view('interest.index', compact('interests'));
    }

    public function create()
    {
        return view('interest.create');
    }

    public function store(InterestRequest $request)
    {
        $this->interestService->storeInterest($request, $this);
        return redirect()->route('interest.index')->with('success', 'interest created successfully.');
    }

    public function show(Interest $interest)
    {
        return view('interest.show', compact('interest'));
    }

    public function edit(Interest $interest)
    {
        return view('interest.edit', compact('interest'));
    }


    public function update(InterestRequest $request, $id)
    {
        $this->interestService->updateInterest($request, $this ,$id);
        return redirect()->route('interest.index')->with('success', 'Interest updated successfully');
    }

    public function destroy(Interest $interest)
    {
        $this->interestService->deleteInterest($interest);
        return redirect()->route('interest.index')->with('success', 'interest deleted successfully.');
    }
}
