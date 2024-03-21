<?php 

return [
    'label' => 'Запрос',
    'modelLabel' => 'Запроса',
    'pluralLabel' => 'Запросы',
    'actions' => [
        'explore' => [
            'label' => 'Исследование',
        ],
        'offer' => [
            'label' => 'Предложение',
        ],
        'win' => [
            'label' => 'Завершен',
        ],
        'lost' => [
            'label' => 'Потерян',
            'reason' => 'Причина потери',
        ],
    ],
    'stages' => [
        'new' => 'Новый',
        'explore' => 'Исследование',
        'offer' => 'Предложение',
        'win' => 'Завершен',
        'lost' => 'Потерян',
    ],
    'statuses' => [
        'new' => 'Новый',
        'progress' => 'В работе',
        'overdue' => 'Просрочен',
        'finished' => 'Завершен',
        'archived' => 'Архив',
    ],
    'sources' => [
        'website' => 'Сайт',
        'call' => 'Звонок',
        'email' => 'E-mail',
        'other' => 'Другое',
    ],
    'form' => [
        'title' => [
            'label' => 'Заголовок',
        ],
        'comment' => [
            'label' => 'Комментарий',
        ],
        'contact' => [
            'label' => 'Контакт',
        ],
        'stage' => [
            'label' => 'Этап',
        ],
        'source' => [
            'label' => 'Источник',
        ],
        'responsible' => [
            'label' => 'Ответственный',
        ],
        'lost_reason' => [
            'label' => 'Причина потери',
        ],
        'expected_profit' => [
            'label' => 'Ожидаемая прибыль',
            'suffix' => '₽',
        ],
        'expected_close_date' => [
            'label' => 'Ожидаемая дата закрытия',
        ],
        'gym' => [
            'label' => 'Зал',
        ],
        'products' => [
            'label' => 'Товары',
        ],

    ],
    'table' => [
        'title' => 'Заголовок',
        'comment' => 'Комментарий',
        'contact' => 'Клиент',
        'responsible' => 'Ответственный',
        'status' => 'Статус',
        'source' => 'Источник',
        'author' => 'Автор',
        'editor' => 'Редактор',
        'expected_close_date' => 'Ожидаемая дата закрытия',
        'gym' => 'Зал',
        'source' => 'Источник',
        'lost_reason' => 'Причина потери',
        'expected_profit' => [
            'label' => 'Ожидаемая прибыль',
            'suffix' => '₽',
        ],
        'stage' => 'Этап',
        'closed_at' => 'Закрыт',
        'created_at' => 'Создан',
        'updated_at' => 'Изменен',
        'deleted_at' => 'Удален',
    ],
    'filters' => [
        'status' => [
            'label' => 'Cтатус',
        ],
        'responsible' => [
            'label' => 'Ответственный',
            'value' => 'Закреплённые за мной',
        ],
        'gym' => [
            'label' => 'Зал',
        ],
        'source' => [
            'label' => 'Источник',
        ],
    ],
    'widgets' => [
        'overdue' => [
            'tableHeading' => 'Просроченные запросы',
        ],
        'new' => [
            'tableHeading' => 'Новые запросы',
        ]
    ],
    'relations' => [
        'events' => [
            'label' => 'Активность',
            'pluralLabel' => 'Активности',
        ],
        'offers' => [
            'actions' => [
                'activate' => 'Активировать',
                'cancel' => 'Отменить',
                'send_sms' => 'Отправить СМС'
            ],
            'label' => 'Предложение',
            'pluralLabel' => 'Предложения',
            'amount' => [
                'label' => 'Итого',
            ],
            'discount' => [
                'label' => 'Скидка',
                'type' => 'Тип',
                'options' => [
                    'percent' => 'Процент',
                    'value' => 'Значение',
                ]
            ],
            'status' => [
                'label' => 'Статус',
                'options' => [
                    'draft' => 'Черновик',
                    'active' => 'Активно',
                    'approved' => 'Подтверждено',
                    'canceled' => 'Отменено',
                ]
            ]
        ]
    ]
];