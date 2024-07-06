<?php

namespace App\Helper\Reader;

final readonly class FileContent {

    /**
     * @throws FileReadException
     */
    public function __construct(
        public array $keys,
        public array $data,
    ) {

        if (empty($this->keys) || empty($this->data)) {
            throw new FileReadException('Empty files are not permitted');
        }
    }
}

