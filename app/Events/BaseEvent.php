<?php

namespace App\Events;

use Request;
use App\Events\Event;
use App\Model\ActivityLog;
use App\Model\Notification\Notification;

abstract class BaseEvent extends Event
{

    /**
     * @author Sandeep Kumar
     * @function: addActivityLog
     * @param: $log
     * @desc: add activity log.
     */
    public static function addActivityLog($log)
    {
        $arrActivity['user_id']        = isset($log['user_id']) ? $log['user_id'] : null;
        $arrActivity['device_id']     = isset($log['device_id']) ? $log['device_id'] : null;
        $arrActivity['activity_id']    = isset($log['activity_id']) ? $log['activity_id'] : null;
        $arrActivity['message']       = isset($log['message']) ? $log['message'] : null;
        $arrActivity['method']        = isset($log['method']) ? $log['method'] : null;
        $arrActivity['request_data']  = isset($log['request_data']) ? $log['request_data'] : null;
        $arrActivity['response_data'] = isset($log['response_data']) ? $log['response_data'] : null;
        $arrActivity['ip_address']    = isset($log['ip_address']) ? $log['ip_address'] : null;

        if (!isset($arrActivity['ip_address'])) {
            $arrActivity['ip_address'] = Request::getClientIp();
        }

        //$arrActivity['source'] = Request::server('HTTP_REFERER');
        //$arrActivity['browser_info'] = Request::server('HTTP_USER_AGENT');

        $save_activity = new ActivityLog($arrActivity);
        $saved         = $save_activity->save();

        return $saved;
    }
}