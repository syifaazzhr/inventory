<?php

return [
    'required'      => '{field} wajib diisi.',
    'min_length'    => '{field} minimal harus terdiri dari {param} karakter.',
    'max_length'    => '{field} tidak boleh lebih dari {param} karakter.',
    'matches'       => '{field} harus sama dengan {param}.',
    'is_unique'     => '{field} sudah digunakan.',

    'custom' => [
    'username' => [
        'min_length' => 'Nama Pengguna minimal harus terdiri dari {param} karakter.',
        'required'   => 'Nama Pengguna wajib diisi.',
    ],
    'password' => [
        'min_length' => 'Kata Sandi minimal harus terdiri dari {param} karakter.',
        'required'   => 'Kata Sandi wajib diisi.',
    ],
    'email' => [
        'required' => 'Alamat Email wajib diisi.',
        'valid_email' => 'Alamat Email tidak valid.',
    ],
],
];