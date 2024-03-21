<?php

return [
    'modelLabel' => 'Товар',
    'pluralLabel' => 'Товары',
    'types' => [
        'physical' => 'Физический',
        'virtual' => 'Виртуальный',
        'subscription' => 'Абонемент',
        'service' => 'Услуга',
    ],
    'filters' => [
        'active' => [
            'label' => 'Статус',
            'options' => [
                'inactive' => 'Неактивен',
                'active' => 'Активен',
            ],
        ],
        'published' => [
            'label' => 'Видимость на сайте',
            'options' => [
                'unpublished' => 'Скрыт',
                'published' => 'Опубликован', 
            ]
        ]
    ],
    'form' => [
        'tabs' => [
            'general' => [
                'label' => 'Основное',
            ],
            'data' => [
                'label' => 'Данные',
            ],
            'additional' => [
                'label' => 'Дополнительно',
            ],
            'images' => [
                'label' => 'Изображения'
            ],
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
        'image' => [
            'label' => 'Изображение',
        ],
        'categories' => [
            'label' => 'Категории',
        ],
        'main_category' => [
            'label' => 'Главная категория',
        ],
        'sku' => [
            'label' => 'SKU'
        ],
        'type' => [
            'label' => 'Тип',
        ],
        'subtract' => [
            'label' => 'Вычитать',
        ],
        'additional_data' => [
            'fieldsets' => [
                'conditions' => [
                    'label' => 'Условия',
                ],
                'publishing' => [
                    'label' => 'Видимость',
                ],
                'display' => [
                    'label' => 'Отображение', 
                ]
            ],
            'subtitle' => [
                'label' => 'Подзаголовок',
            ],
            'days' => [
                'label' => 'Срок действия',
                'hint' => 'Дней',
                'helperText' => 'Число дней. Данная информация нужна для расчета срока действия абонемента',
            ],
            'period_text' => [
                'label' => 'Пояснение к цене',
                'hint' => 'К примеру: В месяц',
                'helperText' => 'Данный текст будет отображаться на сайте возле стоимости.',
            ],
            'trainings_count' => [
                'label' => 'Кол-во тренировок',
            ],
            'duration' => [
                'label' => 'Продолжительность',
                'hint' => 'В минутах',
                'helperText' => 'Продолжительность одной тренировки',
            ],
            'available_from' => [
                'label' => 'Доступен с',
            ],
            'available_to' => [
                'label' => 'Доступен до',
                'helperText' => 'Для бессрочного отображения абонемента на сайте, оставьте поле пустым',
            ],
            'button' => [
                'link' => [
                    'label' => 'Ссылка',
                ],
                'text' => [
                    'label' => 'Текст кнопки',
                    'default' => 'Выбрать',
                ]
            ]
        ],
        'active' => [
            'label' => 'Активный',
        ],
        'published' => [
            'label' => 'Опубликован',
        ],
        'price' => [
            'label' => 'Цена',
            'suffix' => '₽',
        ],
        'order' => [
            'label' => 'Порядок',
        ],
    ],
    'table' => [
        'title' => 'Название',
        'slug' => 'Ссылка',
        'image' => 'Изображени',
        'category' => 'Категория',
        'sku' => 'SKU',
        'type' => 'Тип',
        'active' => 'Активен',
        'published' => 'Опубликован',
        'price' => [
            'label' => 'Цена',
            'suffix' => '₽',
        ],
        'order' => 'Порядок',
        'viewed' => 'Просмотров',
        'author' => 'Автор',
        'editor' => 'Редактор',
        'created_at' => 'Создан',
        'updated_at' => 'Изменен',
        'deleted_at' => 'Удален',
    ],
    'images' => [
        'label' => 'Изображения',
        'createButton' => [
            'text' => 'Добавить изображение',
        ],
        'form' => [
            'image' => [
                'label' => 'Изображение',
            ],
            'order' => [
                'label' => 'Порядок'
            ]
        ],
        'table' => [
            'image' => 'Изображение',
            'order' => 'Порядок',
        ],
    ]
];