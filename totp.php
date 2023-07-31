<?php

require_once("otp.php");

class TOTP extends OTP {
    public function __construct($key, $T0, $interval, $digits = 6, $digest = 'sha1') {
        parent::__construct($key, $digits, $digest);
        $this->T0 = $T0;
        $this->interval = $interval;
    }

    public function get_current_seed($epoch = null) {
        if($epoch === null) $epoch = time();

        return floor(($epoch - $this->T0) / $this->interval);
    }

    public function generate_otp($from_epoch = null) {
        $T = $this->get_current_seed($from_epoch);
        return parent::get_current_seed($T);
    }
}