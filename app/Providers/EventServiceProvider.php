<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Newsletter\SubscriptionConfirmationEmail' => [
            'App\Listeners\Newsletter\SubscriptionActivationEmail',
        ],
        'App\Events\Auth\UserActivationEmail' => [
            'App\Listeners\Auth\SendActivationEmail',
        ],
        'App\Events\Order\OrderPlacedEvent' => [
            'App\Listeners\SMS\OrderPlacedSMS',
            'App\Listeners\DeliveryService\OrderPlacedDelivery',
        ],
        'App\Events\Order\OrderProcessedEvent' => [
            'App\Listeners\SMS\OrderProcessedSMS',
            'App\Listeners\Cashback\OrderProcessedCashback',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
