<?php

namespace app\Settings;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\LaravelSettings\Settings;

class AppSettings extends Settings
{
    public null | string $title;
    public null | string $address;

    public bool $trainer_notification;
    public bool $allow_registration;

    public null | string $meta_title;
    public null | string $meta_description;

    public null | string $contact_email;
    public null | string $phone_number;

    public null | string $instagram;
    public null | string $facebook;
    public null | string $vk;

    public null | string $working_hours;

    public static function group(): string
    {
        return 'general';
    }

    public function phone()
    {
        if(preg_match('/^\+([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{2})([0-9]{2})$/', $this->phone_number, $value)) {

            $code = ($value[1] == 7) ? '+' . $value[1] : $value[1];
            $format = $code . ' (' . $value[2] . ') ' . $value[3] . '-' . $value[4] . '-' . $value[5];
            
            return [
                'label' => $format,
                'value' => $this->phone_number
            ];
        } else {
            return [
                'label' => '',
                'value' => '',
            ];
        }
    }
}