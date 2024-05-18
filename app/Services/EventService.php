<?php
namespace App\Services;

use App\Models\Event\Event;
use App\Models\Event\CategoriesEvent;
use App\Models\Event\InterestEvent;
use App\Models\Event\EventClass;
use App\Models\Event\EventTrip;
use App\Traits\FileStorageTrait;

class EventService
{
    use FileStorageTrait ;
    public function getAllEvents()
    {
        return Event::withoutGlobalScopes()->get();
    }

    public function createEvent($data , $imageFiles, $videoFiles)
    {
        $imagePaths =null ; $videoPaths = null ;

        if ($imageFiles)
            $imagePaths = $this->handleFiles($imageFiles, 'ReelImages');

        if($videoFiles)
            $videoPaths = $this->handleFiles($videoFiles, 'ReelVideo');

        if ($imagePaths) {
            $data['images'] = json_encode($imagePaths);
        }
        if ($videoPaths) {
            $data['videos'] = json_encode($videoPaths);
        }

        $event = Event::create($data) ;
        if (isset($data['category_ids'])&&$data['category_ids'])
            $this->attachCategoriesToEvent($event, $data['category_ids']);

        if (isset($data['interest'])&&$data['interest'])
            $this->attachInterestToEvent($event, $data['interest']);

        if (isset($data['classes'])&&$data['classes'])
            $this->createEventClasses($event, $data['classes']);

        if (isset($data['service_providers']) && $data['service_providers'])
            $event->serviceProviders()->attach($data['service_providers']);

        if (isset($data['event_trips'])&&$data['event_trips'])
            $this->createEventTrips($event, $data['event_trips']);

        return $event;
    }

    private function attachCategoriesToEvent($event, $categoryIds)
    {
        foreach ($categoryIds as $categoryId) {
            CategoriesEvent::create([
                'category_id' => $categoryId,
                'event_id' => $event->id,
            ]);
        }
    }

    private function attachInterestToEvent($event, $interest)
    {
        foreach ($interest as $interestId => $interestData) {
            InterestEvent::create([
                'event_id' => $event->id,
                'interest_id' => $interestId,
                'price' => $interestData['price'],
                'description' => $interestData['description'],
                'description_ar' => $interestData['description_ar'],
            ]);
        }
    }

    private function createEventClasses($event, $classes)
    {
        foreach ($classes as $classData) {
            $class = EventClass::create([
                'event_id' => $event->id,
                'code' => $classData['code'],
                'ticket_price' => $classData['ticket_price'],
                'ticket_number' => $classData['ticket_number']
            ]);
            $class->interests()->sync($classData['interest_ids']);
        }
    }

    private function createEventTrips($event, $eventTrips)
    {
        foreach ($eventTrips as $eventTripData) {
            EventTrip::create([
                'event_id' => $event->id,
                'start_date' => $eventTripData['start_date'],
                'end_date' => $eventTripData['end_date'],
                'description' => $eventTripData['description'],
                'description_ar' => $eventTripData['description_ar'],
            ]);
        }
    }

    public function updateEvent(Event $event, $request)
    {
        $data = $request->except(['_token', '_method', 'classes', 'service_providers', 'event_trips']);

        // Update the event itself
        $event->update($data);

        if($request->has('interest')) {
            foreach($request->interest as $interestId => $details) {
                // Assuming you have a method to handle the update or creation of interest details
                $this->updateInterestDetails($event, $interestId, $details);
            }
        }

        // Update classes
        $event->classes()->delete(); // Assuming you want to replace existing classes
        if($request->has('classes')) {
            foreach ($request->classes as $classData) {
                $class = $event->classes()->create([
                    'code' => $classData['code'],
                    'ticket_price' => $classData['ticket_price'],
                    'ticket_number' => $classData['ticket_number'],
                ]);
                if(isset($classData['interest_ids'])) {
                    $class->interests()->sync($classData['interest_ids']);
                }
            }
        }

        // Update service providers
        $event->serviceProviders()->sync($request->input('service_providers', []));

        // Update event trips
        $event->eventTrips()->delete(); // Assuming you want to replace existing event trips
        if($request->has('event_trips')) {
            foreach ($request->event_trips as $tripData) {
                $event->eventTrips()->create([
                    'start_date' => $tripData['start_date'],
                    'end_date' => $tripData['end_date'],
                    'description' => $tripData['description'],
                    'description_ar' => $tripData['description_ar'],
                ]);
            }
        }

    }

    protected function updateInterestDetails($event, $interestId, $details) {
        // Assuming you have an InterestEvent model to link events with interests
        $interest = InterestEvent::updateOrCreate(
            ['event_id' => $event->id, 'interest_id' => $interestId],
            ['description' => $details['description'], 'description_ar' => $details['description_ar'], 'price' => $details['price']]
        );
    }

    public function deleteEvent(Event $event)
    {
        $event->delete();
    }
}
