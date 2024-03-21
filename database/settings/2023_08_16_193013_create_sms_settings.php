<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('sms.active', false);
        $this->migrator->add('sms.customer_training_reminder', false);
        $this->migrator->add('sms.customer_training_reminder_hours', 1);
        $this->migrator->add('sms.customer_notify_about_expiration', false);
        $this->migrator->add('sms.notification_time', null);
    }
};
