<?php 

return [
    'modelLabel' => 'Запись',
    'pluralLabel' => 'Записи',
    'statuses' => [
        'new' => 'Новая',
        'processing' => 'Идет занятие',
        'finished' => 'Завершена',
        'canceled' => 'Отменена',
    ],
    'actions' => [
        'start' => [
            'label' => 'Начать занятие',
        ],
        'stop' => [
            'label' => 'Закончить занятие',
            'modal' => [
                'rating' => 'Оценка клиента',
                'review' => 'Отзыв клиента',
            ]
        ],
        'cancel' => [
            'label' => 'Отменить',
            'reasonHeader' => '<h3>Причина отмены: </h3>',
            'modal' => [
                'heading' => 'Отменить запись',
                'confirmButton' => 'Отменить запись',
                'comment' => [
                    'label' => 'Причина отмены',
                ],
            ],
        ],
    ],
    'filters' => [
        'gym' => [
            'label' => 'Зал',
        ],
        'trainer' => [
            'label' => 'Тренер',
        ],
        'responsible' => [
            'label' => 'Ответственный',
        ],
        'product' => [
            'label' => 'Товар'
        ],
        'status' => [
            'label' => 'Статус'
        ],
    ],
    'form' => [
        'tabs' => [
            'general' => [
                'label' => 'Основное',
            ],
            'review' => [
                'label' => 'Отзыв',
            ]
        ],
        'contact' => [
            'label' => 'Контакт',
        ],
        'gym' => [
            'label' => 'Зал',
        ],
        'status' => [
            'label' => 'Статус',
        ],
        'trainer' => [
            'label' => 'Тренер',
        ],
        'responsible' => [
            'label' => 'Ответственный',
        ],
        'product' => [
            'label' => 'Товар',
        ],
        'duration' => [
            'label' => 'Продолжительность',
        ],
        'date' => [
            'label' => 'Дата и время',
        ],
        'start_time' => [
            'label' => 'Фактическое время начала',
        ],
        'finish_time' => [
            'label' => 'Время окончания',
        ],
        'comment' => [
            'label' => 'Комментарий',
        ],
        'rating' => [
            'label' => 'Рейтинг',
            'options' => [
                'Ужасно',
                'Плохо',
                'Удовлетворительно',
                'Хорошо',
                'Отлично',
            ],
        ],
        'review' => [
            'label' => 'Отзыв',
        ],
        'additional_data' => [

        ],
    ],
    'table' => [
        'contact' => 'Контакнт',
        'gym' => 'Зал',
        'trainer' => 'Тренер',
        'status' => 'Статус',
        'responsible' => 'Ответственный',
        'product' => 'Товар',
        'duration' => [
            'label' => 'Продолжительность',
            'suffix' => ' мин.'
        ],
        'date' => 'Дата',
        'start_time' => 'Время начала',
        'finish_time' => 'Время окончания',
        'review' => 'Рейтинг',
        'created_at' => 'Создана',
        'updated_at' => 'Изменена',
        'deleted_at' => 'Удалена',
        'author' => 'Автор',
        'editor' => 'Редактор',
    ],
];