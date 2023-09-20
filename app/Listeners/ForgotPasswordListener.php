<?php

namespace App\Listeners;

use App\Events\ForgotPasswordEvent;
use App\Jobs\ForgotPasswordJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ForgotPasswordListener implements ShouldQueue
{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ForgotPasswordEvent $event): void
    {

        dispatch(new ForgotPasswordJob($event->url));
    }
}
