<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.title', '');
        $this->migrator->add('general.address', '');
        $this->migrator->add('general.trainer_notification', false);
        $this->migrator->add('general.allow_registration', false);
        $this->migrator->add('general.meta_title', '');
        $this->migrator->add('general.meta_description', '');
        $this->migrator->add('general.contact_email', '');
        $this->migrator->add('general.phone', '');
        $this->migrator->add('general.instagram', '');
        $this->migrator->add('general.facebook', '');
        $this->migrator->add('general.vk', '');
        $this->migrator->add('general.working_hours', '');
    }
}
