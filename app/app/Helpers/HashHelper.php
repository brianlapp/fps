<?php

namespace App\Helpers;

class HashHelper
{
    public static function hexDecode($hex) {
        return hex2bin($hex);
    }

    public static function hexEncode($data) {
        return bin2hex($data);
    }

    public static function hmacSha256($data, $key) {
        return hash_hmac('sha256', $data, $key, true);
    }
}
