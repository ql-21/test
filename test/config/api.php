<?php
    return [
        'rate_limits'=>[
            'access'=>env('API_RATE_LIMITS_ACCES','60,1'),
            'sign'=>env('API_RATE_LIMITS_SIGN','1,1'),
        ],
    ];