<?php
class EncryptionHelper {
    private static $method = "aes-256-gcm";

    public static function encrypt($data, $key) {
        $iv = openssl_random_pseudo_bytes(12); // 12 bytes for GCM
        $encrypted = openssl_encrypt($data, self::$method, $key, 0, $iv, $tag);
        
        return [
            'content' => $encrypted,
            'iv' => bin2hex($iv),
            'tag' => bin2hex($tag)
        ];
    }

    public static function decrypt($encryptedData, $key, $ivHex, $tagHex) {
        $iv = hex2bin($ivHex);
        $tag = hex2bin($tagHex);
        $decrypted = openssl_decrypt($encryptedData, self::$method, $key, 0, $iv, $tag);
        
        if ($decrypted === false) {
            return null;
        }
        return $decrypted;
    }
}