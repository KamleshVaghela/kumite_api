<?php

return [
    'langs' => [
        'es' => 'www.domain.es',
        'en' => 'www.domain.us'
        // etc
    ],
    'payment_mode' => [
        1 => 'Cash',
        2 => 'Cheque',
        3 => 'Online',
        4 => 'Other',
        5 => 'Bank Transfer',
    ],
    'AES_KEY' => ('JaNdRgUkXn2r5u8x/A?D(G+KbPeShVmY'),    
    'AES_IV' => ('y/B?E(H+MbQeThWm'),
    'competition_level' => [
        'IDJ' => 'Inter-Dojo',
        'ISC' => 'Inter-School',
        'IDS' => 'Inter-District',
        'D' => 'District',
        'IST' => 'Inter-State',
        'S' => 'State',
        'N' => 'National',
        'I' => 'International',
    ],
    'competition' => [
        'player_location' => [
            '1' => [
                'left_1'=> 30,
                'left_2'=> 100,
                'right' => 45,
                'font' => 'helvetica',
                'style' => 'b',
                'fontsize' => 14
            ],
            '2' => [
                'left_1'=> 30,
                'left_2'=> 100,
                'right' => 60,
                'font' => 'helvetica',
                'style' => 'b',
                'fontsize' => 14
            ],
            '3' => [
                'left_1'=> 30,
                'left_2'=> 100,
                'right' => 75,
                'font' => 'helvetica',
                'style' => 'b',
                'fontsize' => 14
            ],
            '4' => [
                'left_1'=> 30,
                'left_2'=> 100,
                'right' => 90,
                'font' => 'helvetica',
                'style' => 'b',
                'fontsize' => 14
            ],
            '5' => [
                'left_1'=> 30,
                'left_2'=> 100,
                'right' => 105,
                'font' => 'helvetica',
                'style' => 'b',
                'fontsize' => 14
            ],
            '6' => [
                'left_1'=> 30,
                'left_2'=> 100,
                'right' => 120,
                'font' => 'helvetica',
                'style' => 'b',
                'fontsize' => 14
            ],
            '7' => [
                'left_1'=> 30,
                'left_2'=> 100,
                'right' => 135,
                'font' => 'helvetica',
                'style' => 'b',
                'fontsize' => 14
            ],
            '8' => [
                'left_1'=> 30,
                'left_2'=> 100,
                'right' => 150,
                'font' => 'helvetica',
                'style' => 'b',
                'fontsize' => 14
            ],
        ],
        1 => ['1'],
        2 => ['1', '5'],
        3 => ['1', '3', '5'],
        4 => ['1', '3', '5', '7'],
        5 => ['1', '2', '3', '5', '7'],
        6 => ['1', '2', '3', '5', '6', '7'],
        7 => ['1', '2', '3', '4', '5', '6', '7'],
        8 => ['1', '2', '3', '4', '5', '6', '7', '8'],
    ],
    "start_date" => "2023/09/01"
];
// $dayOfTheWeek = Carbon::now()->dayOfWeek;
// $weekday = $weekMap[$dayOfTheWeek];
?>
