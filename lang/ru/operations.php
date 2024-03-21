<?php 

return [
    'modelLabel' => 'Операция',
    'pluralLabel' => 'Операции',
    'discount_types' => [
        'percent' => 'Процент',
        'value' => 'Значение'
    ],
    'wizard' => [
        'common' => 'Основное',
        'contact' => 'Клиент',
        'items' => 'Состав',
    ],
    'fieldsets' => [
        'discount' => ['label' => 'Скидка'],
    ],
    'fields' => [
        'contact' => 'Клиент',
        'uid' => 'Уникальный номер',
        'hash' => 'Хэш',
        'expenditure' => 'Статья',
        'discount' => 'Значение скидки',
        'discount_type' => 'Тип скидки',
        'total_amount' => 'Итого',
        'comment' => 'Комментарий',
        'author' => 'Автор',
        'editor' => 'Редактор',
        'created_at' => 'Создана',
        'updated_at' => 'Изменена',
        'deleted_at' => 'Удалена',
    ],  
    'items' => [
        'modelLabel' => 'Товар',
        'pluralLabel' => 'Состав заказа',
        'fields' => [
            'title' => 'Название',
            'price' => 'Цена',
            'quantity' => 'Кол-во',
            'amount' => 'Итого',
            'image' => 'Изображение',
            'deleted_at' => 'Удален',
        ],
    ],
];