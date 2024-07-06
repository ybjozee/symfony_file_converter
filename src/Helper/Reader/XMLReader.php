<?php

namespace App\Helper\Reader;

final class XMLReader extends AbstractReader {

    /**
     * @throws FileReadException
     */
    public function read(string $filePath)
    : void {

        $xml = simplexml_load_file($filePath);
        $content = json_decode(json_encode($xml), true);
        $this->validateContent($content);

        $values = array_values($content)[0];
        $this->writeKeys($values[0]);
        $this->writeData($values);
    }

    /**
     * @throws FileReadException
     */
    private function validateContent(mixed $content)
    : void {

        if (is_null($content)) {
            throw new FileReadException('Invalid XML provided');
        }
        if (empty($content)) {
            throw new FileReadException('Invalid XML provided');
        }
    }

    private function writeKeys(array $sample)
    : void {

        $attributes = array_keys($sample['@attributes']);
        unset($sample['@attributes']);
        $this->keys = array_values([...$attributes, ...array_keys($sample)]);
    }

    private function writeData(array $data)
    : void {

        $formatDataCallback = function (array $datum)
        : array {

            $attributes = array_values($datum['@attributes']);
            unset($datum['@attributes']);

            return array_values([...$attributes, ...$datum]);
        };

        $this->data = array_map($formatDataCallback, $data);
    }
}
