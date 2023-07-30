<?php

require_once("otp.php");

class HOTP extends OTP {
    public function __construct($key, $counter, $digits = 6, $digest = 'sha1') {
        parent::__construct($key, $digits, $digest);
        $this->counter = $counter;
    }

    public function generate_otp($advance_counter = true) {
        $otp = parent::generate_otp($this->counter);
        if($advance_counter) $advance_counter += 1;
        return $otp;
    }
}