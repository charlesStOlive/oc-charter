<?php
return [
    'fnc_fields' => [
        'beginAtZero' => [
            'type' => 'switch',
            'label' => 'Commencer à 0',
            'default' => false,
            'span' => 'full',
        ],
        'width' => [
            'type' => 'number',
            'label' => 'Longueur',
            'default' => 500,
            'span' => 'left',
        ],
        'height' => [
            'type' => 'number',
            'label' => 'Longueur',
            'default' => 500,
            'span' => 'right',
        ],
        'color' => [
            'label' => 'Couleur',
            'type' => 'dropdown',
            'default' => 'primary',
            'options' => [
                'primary' => 'Primary',
                'secondary' => 'Secondary',
                'complementary' => 'Couleur complémentaire',

            ],

        ],
    ],
];
