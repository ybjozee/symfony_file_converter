<?php

namespace App\Helper\Converter;

use App\Helper\Reader\{AbstractReader, JsonReader};
use App\Helper\Writer\{AbstractWriter, JSONWriter};

class JsonConverter implements FileConverterInterface {

    public function supports(string $fileExtension)
    : bool {

        return $fileExtension === 'json';
    }

    public function getReader()
    : AbstractReader {

        return new JsonReader;
    }

    public function getWriter(string $saveLocation, string $fileName)
    : AbstractWriter {

        return new JSONWriter($saveLocation, $fileName);
    }

    public function getSupportedFormat()
    : string {

        return 'json';
    }
}

