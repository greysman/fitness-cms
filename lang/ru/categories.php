<?php

return [
    'label' => 'Категория',
    'modelLabel' => 'Категорию',
    'pluralLabel' => 'Категории',
    'form' => [
        'parent' => [
            'label' => 'Родительская',
        ],
        'title' => [
            'label' => 'Название',
        ],
        'description' => [
            'label' => 'Описание',
        ],
        'slug' => [
            'label' => 'Ссылка',
            'visit_link' => [
                'label' => 'Просмотреть',
            ]
        ],
    ],
    'table' => [
        'title' => 'Название',
        'slug' => 'Ссылка',
        'author' => 'Автор',
        'editor' => 'Редактор',
        'created_at' => 'Создана',
        'updated_at' => 'Изменена',
        'deleted_at' => 'Удалена',
    ],
];