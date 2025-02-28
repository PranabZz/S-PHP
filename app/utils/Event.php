<?php

// What we want is every event is stored in jobs and is dispatched once the job tells it to for sorting if multiple events
// need to be dispatched at the same time
class Event
{
    private static $listeners = [];

    // Method to handle events
    public static function handle($event, $callback, $priority = 1)
    {
        // Store listeners for the event, with the associated callback and priority
        self::$listeners[$event][] = ['callback' => $callback, 'priority' => $priority];

        // Sort the listeners array by priority, from high to low
        usort(self::$listeners[$event], function ($a, $b) {
            return $b['priority'] - $a['priority'];
        });
    }

    // Method to dispatch events
    public static function dispatch($event)
    {
        // Check if there are listeners for the event
        if (isset(self::$listeners[$event])) {
            echo "Dispatching event: $event\n";
            // Iterate through each listener and execute the callback
            foreach (self::$listeners[$event] as $listener) {
                echo "Executing: {$listener['callback']} with priority {$listener['priority']}\n";
            }
        }
    }
}

// Example usage: Adding listeners to different events with priorities
Event::handle('new', 'task_new', 3);
Event::handle('important', 'task_critical', 190);
Event::handle('old', 'task_old', 5);

// Dispatching events
Event::dispatch('new');
Event::dispatch('old');
Event::dispatch('important');
