<?php

function __generate_random_string(int $length = 32) {
    return $secret = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}

function __config__create_secret(string $file): string
{
    $secret = __generate_random_string();
    file_put_contents($file, $secret);
    return $secret;
}

return [
    "secret" => file_exists(__DIR__ . "/.secret") ? file_get_contents(__DIR__ . "/.secret") : __config__create_secret(__DIR__ . "/.secret"),
    "database" => [
        "driver" => "sqlite3", #Change to pdo_mysql for mysql
        "host" => "localhost",
        "port" => 3306,
        "user" => "automator",
        "password" => "automator",
        "database" => "automator",
        "path " => __DIR__ . "/db.sqlite",
    ]
];