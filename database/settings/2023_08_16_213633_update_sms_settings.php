<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('sms.customer_training_reminder_text', 'Напоминание: Вы записаны на {{ date }}');
        $this->migrator->add('sms.customer_notify_about_expiration_text', 'Срок действия вашего абонемента на исходе');
    }
};
