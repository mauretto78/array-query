<?php
/**
 * This file is part of the ArrayQuery package.
 *
 * (c) Mauro Cassani<https://github.com/mauretto78>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
