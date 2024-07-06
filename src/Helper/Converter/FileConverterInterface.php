<?php

namespace App\Helper\Converter;

use App\Helper\Reader\AbstractReader;
use App\Helper\Writer\AbstractWriter;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.converter')]
interface FileConverterInterface {

    public function supports(string $fileExtension)
    : bool;

    public function getReader()
    : AbstractReader;

    public function getWriter(string $saveLocation, string $fileName)
    : AbstractWriter;

    public function getSupportedFormat()
    : string;
}

