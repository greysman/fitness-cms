<?php

return [
    'modelLabel' => 'Расписание',
    'pluralLabel' => 'Расписание',
    'form' => [
        'date' => [
            'label' => 'Дата',
            'hintText' => 'Начало',
        ],
        'date_end' => [
            'label' => 'Дата',
            'hintText' => 'Окончание',
        ],
        'title' => [
            'label' => 'Заголовок',
        ],
        'description' => [
            'label' => 'Описание',
        ],
        'gym' => [
            'label' => 'Зал',
        ],
        'trainer' => [
            'label' => 'Тренер',
        ],
        'active' => [
            'label' => 'Активен',
        ]
    ],
    'table' => [
        'filters' => [
            'gym' => [
                'label' => 'Зал'
            ],
            'trainer' => [
                'label' => 'Тренер',
            ],
            'date' => [
                'label' => 'Дата',
                'from' => 'С',
                'to' => 'По',
            ],
            'active' => [
                'label' => 'Статус',
                'options' => [
                    'inactive' => 'Неактивен',
                    'active' => 'Активен',
                ]
            ],
            'title' => [
                'label' => 'Есть заголовок',
            ],
        ],
        'date' => 'Дата',
        'date_end' => 'Окончание',
        'title' => 'Заголовок',
        'description' => 'Описание',
        'gym' => 'Зал',
        'trainer' => 'Тренер',
        'active' => 'Активен',
        'author' => 'Автор',
        'editor' => 'Редактор',
        'created_at' => 'Создано',
        'updated_at' => 'Изменено',
        'deleted_at' => 'Удалено',
    ],
    'widget' => [

    ],
];