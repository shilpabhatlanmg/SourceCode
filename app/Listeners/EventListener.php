<?php

namespace App\Listeners;

use App\Events\SomeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(Event $event)
    {
        //
    }

    public function subscribe($events)
    {        
        $events->listen('sendEmailByTemplate', '\App\Events\SomeEvent@sendEmailByTemplate');
        $events->listen('sendEmail', '\App\Events\SomeEvent@sendEmail');
    }
}
