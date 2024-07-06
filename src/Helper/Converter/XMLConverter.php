<?php

namespace App\Helper\Converter;

use App\Helper\Reader\AbstractReader;
use App\Helper\Reader\XMLReader;
use App\Helper\Writer\AbstractWriter;
use App\Helper\Writer\XMLWriter;

class XMLConverter implements FileConverterInterface {

    public function supports(string $fileExtension)
    : bool {

        return $fileExtension === 'xml';
    }

    public function getReader()
    : AbstractReader {

        return new XMLReader();
    }

    public function getWriter(string $saveLocation, string $fileName)
    : AbstractWriter {

        return new XMLWriter($saveLocation, $fileName);
    }

    public function getSupportedFormat(): string
    {
        return 'xml';
    }
}

