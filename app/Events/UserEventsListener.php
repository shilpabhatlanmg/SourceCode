<?php

namespace App\Events;

use App\Events\BaseEvent;
use Illuminate\Queue\SerializesModels;

class UserEventsListener extends BaseEvent {

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Event that would be fired on a user login
     * 
     * @param object $user Logged in user object
     * 
     * @since 0.1
     */
    public function createLog($user) {
        $user_data = unserialize($user);
        self::addActivityLog($user_data);
    }

    /**
     * Event subscribers
     * 
     * @param mixed $events
     */
    public function subscribe($events) {        
        
    }

}
