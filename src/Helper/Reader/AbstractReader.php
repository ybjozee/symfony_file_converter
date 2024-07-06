<?php

namespace App\Helper\Reader;

abstract class AbstractReader {

    protected array $keys = [];
    protected array $data = [];

    public abstract function read(string $filePath)
    : void;

    /**
     * @throws FileReadException
     */
    public function getContent()
    : FileContent {

        return new FileContent($this->keys, $this->data);
    }
}

