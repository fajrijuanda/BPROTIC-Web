<?php

if (!function_exists('fastEncrypt')) {
    function fastEncrypt($id)
    {
        $key = config('app.key'); // Use Laravel's app key
        $encrypted = base64_encode($id ^ crc32($key)); // XOR with CRC32
        return strtr($encrypted, '+/', '-_'); // Make it URL-safe
    }
}

if (!function_exists('fastDecrypt')) {
    function fastDecrypt($encryptedId)
    {
        $key = config('app.key');
        $decoded = base64_decode(strtr($encryptedId, '-_', '+/')); // Decode from URL-safe
        return $decoded ^ crc32($key); // XOR back
    }
}
