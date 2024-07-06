<?php

namespace App\Helper\Converter;

use App\Helper\Reader\{AbstractReader, SpreadsheetReader};
use App\Helper\Writer\{AbstractWriter, SpreadsheetWriter};

class XLSXConverter implements FileConverterInterface {

    public function supports(string $fileExtension)
    : bool {

        return $fileExtension === 'xlsx';
    }

    public function getReader()
    : AbstractReader {

        return new SpreadsheetReader;
    }

    public function getWriter(string $saveLocation, string $fileName)
    : AbstractWriter {

        return new SpreadsheetWriter($saveLocation, $fileName, isCSV: false);
    }

    public function getSupportedFormat()
    : string {

        return 'xlsx';
    }
}

