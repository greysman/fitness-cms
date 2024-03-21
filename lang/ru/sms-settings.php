<?php

return [
    'modelLabel' => 'Настройка',
    'pluralLabel' => 'Настройки SMS',
    'fieldsets' => [
        'customer_training_reminder' => [
            'label' => 'Уведомлять клиента о предстоящей тренировке'
        ],
        'customer_notify_about_expiration' => [
            'label' => 'Уведомлять клиента о истекающем абонементе',
        ],
    ],
    'fields' => [
        'active' => [
            'label' => 'Включено SMS информирование',
        ],
        'customer_training_reminder' => [
            'label' => 'Включено',
        ],
        'customer_training_reminder_text' => [
            'label' => 'Текст сообщения',
        ],
        'customer_training_reminder_hours' => [
            'label' => 'До тренировки',
            'suffix' => 'Часов',
        ],
        'customer_notify_about_expiration' => [
            'label' => 'Включено',
        ],
        'customer_notify_about_expiration_text' => [
            'label' => 'Текст сообщения',
        ],
        'notification_time' => [
            'label' => 'В какое время отправлять уведомления?',
            'helperText' => 'Не распространяется на "Уведомлять клиента о предстоящей тренировке"'
        ],
    ],
];