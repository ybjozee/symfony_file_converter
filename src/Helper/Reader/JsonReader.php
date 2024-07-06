<?php

namespace App\Helper\Reader;

final class JsonReader extends AbstractReader {

    /**
     * @throws FileReadException
     */
    public function read(string $filePath)
    : void {

        $json = file_get_contents($filePath);
        $content = json_decode($json, true);
        $this->validateContent($content);

        $this->keys = array_keys($content[0]);

        $this->parseData($content);
    }

    /**
     * @throws FileReadException
     */
    private function validateContent(mixed $content)
    : void {

        if (is_null($content)) {
            throw new FileReadException('Invalid JSON provided');
        }
        if (empty($content)) {
            throw new FileReadException('Invalid JSON provided');
        }
    }

    private function parseData(array $data)
    : void {

        foreach ($data as $datum) {
            $this->data[] = array_values($datum);
        }
    }
}
