<?php

$users = [
    [
        'email' => 'admin@gmail.com',
        'username' => 'adminxxx',
        'name' => 'Admin',
        'password' => password_hash('admin123', PASSWORD_DEFAULT) // Password: admin123
    ],
    [
        'email' => 'naldi@gmail.com',
        'username' => 'naldi_aja',
        'name' => 'Muh. Rinaldi Ruslan',
        'password' => password_hash('naldi123', PASSWORD_DEFAULT), // Password: naldi123
        'gender' => 'Female',
        'faculty' => 'MIPA',
        'batch' => '2023'
    ],
    [
        'email' => 'ervin@gmail.com',
        'username' => 'ervin',
        'name' => 'Muhammad Ervin',
        'password' => password_hash('ervin123', PASSWORD_DEFAULT), // Password: ervin123
        'gender' => 'Male',
        'faculty' => 'Hukum',
        'batch' => '2023'
    ],
    [
        'email' => 'yusta@gmail.com',
        'username' => 'yusra59',
        'name' => 'Yusra Airlangga',
        'password' => password_hash('yusra123', PASSWORD_DEFAULT), // Password: yusra123
        'gender' => 'Female',
        'faculty' => 'Keperawatan',
        'batch' => '2021'
    ],
    [
        'email' => 'muslih@gmail.com',
        'username' => 'muslih23',
        'name' => 'Muslih',
        'password' => password_hash('muslih123', PASSWORD_DEFAULT), // Password: muslih123
        'gender' => 'Male',
        'faculty' => 'Teknik',
        'batch' => '2020'
    ]
];