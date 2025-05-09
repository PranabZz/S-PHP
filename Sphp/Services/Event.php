<?php

namespace Sphp\Services;

class Event
{
    private static $listeners = [];

    // Method to handle events
    public static function handle($event, $callback, $priority = 1)
    {
        self::$listeners[$event][] = ['callback' => $callback, 'priority' => $priority];

        usort(self::$listeners[$event], function ($a, $b) {
            return $b['priority'] - $a['priority'];
        });
    }

    // Method to dispatch events
    public static function dispatch($event, $job = null)
    {
        if (isset(self::$listeners[$event]) && count(self::$listeners[$event]) > 0) {
            echo "Dispatching event: $event\n";
            foreach (self::$listeners[$event] as $listener) {
                echo "Executing callback with priority {$listener['priority']}\n";
                if ($job) {
                    // If the event involves a job, execute it
                    if ($job instanceof Job) {
                        echo "Executing job: {$job->getTaskName()}\n";
                        $job->execute();  // Execute the job
                    }
                }
                call_user_func($listener['callback']);
            }
        } else {
            echo "No listeners found for event: $event\n";
        }
    }
}
