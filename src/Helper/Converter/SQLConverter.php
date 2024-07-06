<?php

namespace App\Helper\Converter;

use App\Helper\Reader\{AbstractReader, SQLReader};
use App\Helper\Writer\{AbstractWriter, SQLWriter};

class SQLConverter implements FileConverterInterface {

    public function supports(string $fileExtension)
    : bool {

        return $fileExtension === 'sql';
    }

    public function getReader()
    : AbstractReader {

        return new SQLReader;
    }

    public function getWriter(string $saveLocation, string $fileName)
    : AbstractWriter {

        return new SQLWriter($saveLocation, $fileName);
    }

    public function getSupportedFormat()
    : string {

        return 'sql';
    }
}

