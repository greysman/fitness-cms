<?php 

return [
    'label' => 'Статья',
    'modelLabel' => 'Статья',
    'pluralLabel' => 'Статьи',
    'navigationLabel' => 'Cтатьи',
    'types' => [
        'income' => [
            'text' => 'Доход',
        ],
        'expenditure' => [
            'text' => 'Расход'
        ]
    ],
    'fields' => [
        'type' => [
            'label' => 'Тип'
        ],
        'title' => [
            'label' => 'Название'
        ],
        'comment' => [
            'label' => 'Комментарий'
        ],
        'author' => [
            'label' => 'Автор',
        ],
        'editor' => [
            'label' => 'Редактор'
        ],
        'created_at' => [
            'label' => 'Создана'
        ],
        'updated_at' => [
            'label' => 'Изменена'
        ],
        'has_contact' => [
            'label' => 'Клиент',
            'helper' => 'В документе нужно указать клиента',
        ],
        'has_items' => [
            'label' => 'Товары',
            'helper' => 'В документе присутствуют товары'
        ],
    ],
];