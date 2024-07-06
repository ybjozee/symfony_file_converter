<?php

namespace App\Helper\Converter;

use Exception;

class UnsupportedFormatException extends Exception {

    public function __construct($format) {
        parent::__construct("This application does not support the conversion of $format files");
    }
}

