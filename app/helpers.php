<?php

use App\Services\SettingService;

function setting($key, $default = null)
{
    static $service;
    if (!$service) {
        $service = app(SettingService::class);
    }
    return $service->get($key, $default);
}
