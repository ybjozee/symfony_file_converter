<?php

namespace App\Helper\Converter;

use App\Helper\Reader\{AbstractReader, SpreadsheetReader};
use App\Helper\Writer\{AbstractWriter, SpreadsheetWriter};

class CSVConverter implements FileConverterInterface {

    public function supports(string $fileExtension)
    : bool {

        return $fileExtension === 'csv';
    }

    public function getReader()
    : AbstractReader {

        return new SpreadsheetReader;
    }

    public function getWriter(string $saveLocation, string $fileName)
    : AbstractWriter {

        return new SpreadsheetWriter($saveLocation, $fileName, isCSV: true);
    }

    public function getSupportedFormat()
    : string {

        return 'csv';
    }
}

