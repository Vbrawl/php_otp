<?php

require_once("base32.php");



class OTP {
    public function __construct($key, $digits = 6, $digest = 'sha1') {
        $this->key = self::process_key($key);
        $this->digits = $digits;
        $this->digest = $digest;
    }

    public static function process_key($key) {
        return Base32::decode($key);
    }

    public static function int_to_bytestring($value) {
        $result = '';
        while($value != 0) {
            $result .= chr($value & 0xFF);
            $value >>= 8;
        }
        $pad_size = strlen($result) % 8;
        return str_repeat("\0", 8 - $pad_size) . strrev($result);
    }

    public function generate_otp($counter) {
        $HMAC = hash_hmac("sha1", self::int_to_bytestring($counter), $this->key, true);
        $HMACLength = strlen($HMAC);
        $offset = ord($HMAC[$HMACLength - 1]) & 0xF;
        $Snum = (
            (ord($HMAC[$offset]) << 24)
            | (ord($HMAC[$offset + 1]) << 16)
            | (ord($HMAC[$offset + 2]) << 8)
            | (ord($HMAC[$offset + 3]))
        ) & 0x7FFFFFFF;
        $result = (string)($Snum % pow(10, $this->digits));
        $pad_size = strlen($result) % $this->digits;
        return str_repeat("0", $pad_size) . $result;
    }
}