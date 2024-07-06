<?php

namespace App\Helper\Writer;

use App\Helper\Reader\FileContent;

abstract class AbstractWriter {

    protected readonly string $fileName;

    public function __construct(
        protected readonly string $saveLocation,
        string                  $fileName,
    ) {

        $this->fileName = strtolower($fileName);
    }

    public abstract function write(FileContent $content)
    : void;
}

