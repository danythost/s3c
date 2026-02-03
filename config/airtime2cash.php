<?php

return [
    'numbers' => [
        'MTN' => '08031234567',
        'AIRTEL' => '08021234567',
        'GLO' => '08051234567',
        '9MOBILE' => '08091234567',
    ],
    'ussd' => [
        'MTN' => '*600*RECIPIENT*AMOUNT*PIN#',
        'AIRTEL' => '*432#',
        'GLO' => '*131*RECIPIENT*AMOUNT*PIN#',
        '9MOBILE' => '*223*PIN*AMOUNT*RECIPIENT#',
    ],
    'rates' => [
        'MTN' => 0.82, // 82% of amount (18% commission)
        'AIRTEL' => 0.80,
        'GLO' => 0.70,
        '9MOBILE' => 0.75,
    ],
    'min_amount' => 100,
];
