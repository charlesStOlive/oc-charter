<?php
return [
    'chartModel' => [
        'pie_or_doughnut' => [
            'label' => "Anneau/Circulaire"
        ],
        'bar_or_line' => [
            'label' => 'Bar ou ligne'

        ],
        'bar_or_line_2_axis' => [
            'label' => 'Bar ou ligne, 2 axes'
        ],
    ],
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
