<?php

return [
    'filament' => [
        'recordTitleAttribute' => config('filament-pages.filament.recordTitleAttribute', 'Заголовок'),
        'modelLabel' => 'Страницу',
        'pluralLabel' => 'Страницы',
        'navigation' => [
            'icon' => config('filament-pages.filament.navigation.icon', 'heroicon-o-document'),
            'group' => config('filament-pages.filament.navigation.group', null),
            'sort' => config('filament-pages.filament.navigation.sort', null),
        ],
        'templates' => [
            'label' => 'Шаблон',
            'items' => [
                'simple_blog' => 'Запись',
                'simple_page' => 'Страница',
                'simple_service' => 'Услуга',
            ]
        ],
        'table' => [
            'status' => [
                'label' => 'Статус',
                'published' => 'Опубликовано',
                'draft' => 'Черновк',
            ],
            'title' => 'Заголовок',
            'created_at' => 'Создано',
        ],
        'form' => [
            'title' => [
                'label' => 'Заголовок',
            ],
            'slug' => [
                'label' => 'Ссылка',
                'visit_link' => [
                    'label' => 'Просмотреть',
                ]
            ],
            'content' => [
                'label' => 'Содержание',
            ],
            'image' => [
                'label' => 'Изображение',
            ],
            'published' => [
                'label' => 'Опубликовано',
            ],
            'published_at' => [
                'label' => 'Опубликовано с',
                'displayFormat' => 'd. M Y',
            ],
            'published_until' => [
                'label' => 'Опубликовано до',
                'displayFormat' => 'd. M Y',
            ],
            'created_at' => [
                'label' => 'Создано',
            ],
            'updated_at' => [
                'label' => 'Изменено',
            ],
        ],
    ],
];
