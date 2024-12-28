<?php
define('ENCRYPTION_KEY', 'DB8u25EUQfuvf4yp');

function encryptToken($data)
{
    $iv = random_bytes(16);
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', ENCRYPTION_KEY, 0, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptToken($encryptedData)
{
    $data = base64_decode($encryptedData);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    return openssl_decrypt($encrypted, 'aes-256-cbc', ENCRYPTION_KEY, 0, $iv);
}
?>