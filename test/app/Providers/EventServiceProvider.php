<?php

namespace App\Providers;

use App\Events\AddRead;
use App\Listeners\AddReadNumber;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // add your listeners (aka providers) here
            'SocialiteProviders\\Weixin\\WeixinExtendSocialite@handle',
        ],

        //注册事件
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        //邮件事件
        Verified::class=>[
            \App\Listeners\EmailVerified::class,
        ],
        AddRead::class=>[
            AddReadNumber::class,
        ]
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
