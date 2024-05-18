<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventCategoryRequest;
use App\Models\Common\Interest;
use App\Models\Event\EventsCategory;
use App\Services\EventsCategoryService;

class EventsCategoryController extends Controller
{
    protected $eventsCategoryService;

    public function __construct(EventsCategoryService $eventsCategoryService)
    {
        $this->eventsCategoryService = $eventsCategoryService;
    }

    public function index()
    {
        $eventsCategory = $this->eventsCategoryService->getAllEventCategories();
        return view('categories.index', compact('eventsCategory'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(EventCategoryRequest $request)
    {
        $this->eventsCategoryService->createEventCategory($request->all());
        return redirect()->route('events-categories.index')->with('success', 'EventsCategory created successfully.');
    }

    public function show(EventsCategory $events_category)
    {
        return view('categories.show', compact('events_category'));
    }


    public function edit(EventsCategory $interest)
    {
        return view('categories.edit', compact('interest'));
    }

    public function update(EventCategoryRequest $request, $id)
    {
        $this->eventsCategoryService->updateEventCategory($request ,$id);
        return redirect()->route('events-categories.index')->with('success', 'EventsCategory updated successfully');
    }

    public function destroy(EventsCategory $events_category)
    {
        $this->eventsCategoryService->deleteEventCategory($events_category);
        return redirect()->route('events-categories.index')->with('success', 'EventsCategory deleted successfully.');
    }
}
