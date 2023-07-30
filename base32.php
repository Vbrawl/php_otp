<?php



class Base32 {

    const BIT_VALUE = 31;
    const BIT_NUMBER = 5;
    const DICTIONARY = 'abcdefghijklmnopqrstuvwxyz234567';

    public static function encode($data, $pad = "=") {
        $dataSize = strlen($data);
        $encoded = "";
        $encoded_size = 0;

        $chunk = 0;
        $chunkSize = 0;

        for($i = 0; $i < $dataSize; $i++) {
            $chunk = ($chunk << 8) | ord($data[$i]);
            $chunkSize += 8;

            while($chunkSize > 4) {
                $chunkSize -= self::BIT_NUMBER;
                $c = (($chunk >> $chunkSize) & self::BIT_VALUE);
                $encoded .= self::DICTIONARY[$c];
            }
        }

        if($chunkSize !== 0) {
            $c = (($chunk << self::BIT_NUMBER - $chunkSize) & self::BIT_VALUE);
            $encoded .= self::DICTIONARY[$c];
        }

        if($pad) {
            return self::add_padding($encoded, $pad);
        }
        return $encoded;
    }

    public static function add_padding($encoded, $pad = '=') {
        $pad_size = strlen($encoded) % 8;
        $encoded .= str_repeat($pad, 8 - $pad_size);
        return $encoded;
    }

    public static function decode($data, $pad = "=") {
        $data = strtolower(rtrim($data, $pad."\x20\t\n\r\0\x0B"));
        $dataSize = strlen($data);
        $decoded = '';
        $buf = 0;
        $bufSize = 0;

        for($i = 0; $i < $dataSize; $i++) {
            $c = $data[$i];
            $buf = ($buf << 5) | strpos(self::DICTIONARY, $c);
            $bufSize += 5;

            while($bufSize > 7) {
                $bufSize -= 8;
                $decoded .= chr(($buf >> $bufSize) & 0xFF);
            }
        }

        return $decoded;
    }
}