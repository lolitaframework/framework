<?php
namespace lolita\LolitaFramework\Configuration\Modules;

use \lolita\LolitaFramework\Configuration\Configuration;
use \lolita\LolitaFramework\Configuration\IModule;
use \Exception;

class Schedules implements IModule
{
    /**
     * Schedule list
     */
    const SCHEDULE_LIST = 'schedules';

    /**
     * Event list
     */
    const EVENT_LIST = 'events';

    /**
     * Callabel object
     */
    const CALLABLE_O = 'callable';

    /**
     * Start time, default: time()
     */
    const STARTTIME  = 'starttime';

    /**
     * Hook name
     */
    const HOOK_NAME = 'hook_name';


    /**
     * How often the event should reoccur. Default values:
     *
     * hourly
     * twicedaily
     * daily
     */
    const RECURRENCE = 'recurrence';

    /**
     * Images class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param array $data engine data.
     */
    public function __construct($data = null)
    {
        $this->data = (array) $data;
        $this->init();
    }

    /**
     * Initialize module
     *
     * @return Schedule instance
     */
    public function init()
    {
        if ($this->hasScheduleList()) {
            add_filter('cron_schedules', array(&$this, 'schedules'), 10, 1);
        }

        if ($this->hasEventList()) {
            foreach ($this->eventList() as $index => $event) {
                if (!array_key_exists(self::CALLABLE_O, $event)) {
                    throw new Exception('The event must have callable object. Event: ' . json_encode($event));
                }

                if (!array_key_exists(self::RECURRENCE, $event)) {
                    throw new Exception('The event must have recurrence option. Event: ' . json_encode($event));
                }
                if (!array_key_exists(self::STARTTIME, $event)) {
                    $starttime = time();
                } else {
                    if (is_callable($event[ self::STARTTIME ])) {
                        $starttime = $event[ self::STARTTIME ]();
                    } else {
                        $starttime = $event[ self::STARTTIME ];
                    }
                }

                $callable   = $event[self::CALLABLE_O];
                $recurrence = $event[self::RECURRENCE];
                if (is_callable($callable)) {
                    if (array_key_exists(self::HOOK_NAME, $event)) {
                        $handler = $event[ self::HOOK_NAME ];
                    } else {
                        $handler = 'schedule_event_hook_' . $index;
                    }

                    add_action($handler, $callable);
                    if (!wp_next_scheduled($handler)) {
                        wp_schedule_event($starttime, $recurrence, $handler);
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Add schedule type to list
     *
     * @param  array $list
     * @return array
     */
    public function schedules($list)
    {
        return array_merge($list, $this->scheduleList());
    }

    /**
     * Schedule list
     *
     * @return array
     */
    private function scheduleList()
    {
        if ($this->hasScheduleList()) {
            return (array) $this->data[self::SCHEDULE_LIST];
        }
        return [];
    }

    /**
     * Event list
     *
     * @return array
     */
    private function eventList()
    {
        if ($this->hasEventList()) {
            return (array) $this->data[self::EVENT_LIST];
        }
        return [];
    }

    /**
     * Has data schedule list?
     *
     * @return boolean
     */
    private function hasScheduleList()
    {
        return array_key_exists(self::SCHEDULE_LIST, $this->data);
    }

    /**
     * Has data event list?
     *
     * @return boolean
     */
    private function hasEventList()
    {
        return array_key_exists(self::EVENT_LIST, $this->data);
    }

    /**
     * Module priority
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return [int] priority, the smaller number the faster boot.
     */
    public static function getPriority()
    {
        return Configuration::DEFAULT_PRIORITY;
    }
}
