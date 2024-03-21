<?php

namespace App\Settings;

use Carbon\Carbon;
use Spatie\LaravelSettings\Settings;
use Spatie\LaravelSettings\SettingsCasts\DateTimeInterfaceCast;

class SmsSettings extends Settings
{
    public bool $active;
    public bool $customer_training_reminder;
    public string $customer_training_reminder_text;
    public null | int $customer_training_reminder_hours;
    public bool $customer_notify_about_expiration;
    public string $customer_notify_about_expiration_text;
    public $notification_time;

    public static function group(): string
    {
        return 'sms';
    }
}