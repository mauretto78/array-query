<?php

require __DIR__.'/../vendor/autoload.php';

try {
    $usersFile = __DIR__.'/../tests/files/users.json';
    $usersFileContent = file_get_contents($usersFile);
    $usersObjectArray = json_decode($usersFileContent);
    $usersPlainArray = json_decode($usersFileContent, true);

    return [
        'usersObjectArray' => $usersObjectArray,
        'usersPlainArray' => $usersPlainArray
    ];
} catch (\Exception $e) {
    printf('Unable to parse the file ' . $usersFile . ' : %s', $e->getMessage());
}
