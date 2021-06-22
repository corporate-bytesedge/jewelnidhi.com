<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserActivationEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Mail\Auth\ActivationEmail;

class SendActivationEmail
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
     * @param  UserActivationEmail  $event
     * @return void
     */
    public function handle(UserActivationEmail $event)
    {
        if($event->user->verified) {
            return;
        }

        // Send Activation Email
        
        try {
            $emailDataArray = array();
             

              
            // Mail::to($event->user->email)->send(new ActivationEmail($event->user));
        } catch (\Exception $e) {
            dd('-----'.$e->getMessage());
            return $e->getMessage();
        }
    }
}
