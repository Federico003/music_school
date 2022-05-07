<?php

namespace App\Services;

use Spatie\GoogleCalendar\Event;

use Log;

class GoogleCalendarEvent 
{
    private $event;

    public function __construct()
    {
        $this->event = new Event; 
    }

    public function saveEvent($name, $description, $startTime, $time)
    {
        $this->event->name = $name;
        $this->event->description = $description;
        $this->event->startDateTime = $startTime;
        $this->event->endDateTime = $startTime->addMinutes(30);
        $this->event->save();
    }

}
