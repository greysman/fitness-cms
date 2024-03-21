<?php

return [
    'label' => 'Контакт',
    'modelLabel' => 'Контакта',
    'pluralLabel' => 'Контакты',
    'fields' => [
        'email' => 'E-mail',
        'phone' => 'Телефон',
        'name' => 'Имя',
        'surname' => 'Фамилия',
        'patronymic' => 'Отчество',
        'birthday' => 'Дата рождения',
        'avatar' => 'Аватар',
        'email_verified_at' => 'Верифирован E-mail',
        'phone_verified_at' => 'Верифирован телефон',
        'password' => 'Пароль',
        'created_at' => 'Создан',
        'updated_at' => 'Изменен',
        'deleted_at' => 'Удален',
        'author' => 'Автор',
        'editor' => 'Редактор',
        'comment' => 'Коментарий',
        'last_activity' => 'Последняя активность',
        'status' => 'Статус',
        'inactive' => 'Заблокирован',
        'active' => 'Активен',
        'sex' => [
            'label' => 'Пол',
            'options' => [
                'male' => 'Мужчина',
                'female' => 'Женщина',
            ],
            'short_options' => [
                'm' => 'М',
                'w' => 'Ж',
            ]
        ],
    ],
    'filter' => [
        'active' => [
            'label' => 'Статус',
            'options' => [
                'inactive' => 'Неактивен',
                'active' => 'Активен'
            ],
        ],
        'sex' => [
            'label' => 'Пол',
            'options' => [
                'male' => 'Мужчина',
                'female' => 'Женщина',
            ]
        ],
    ]
]; 