<?php

namespace App\Helper\Reader;

final class SQLReader extends AbstractReader {

    public function read(string $filePath)
    : void {

        $sql = file_get_contents($filePath);
        $components = explode(PHP_EOL, $sql);

        $header = $components[0];
        $this->parseKeys($header);

        $data = array_slice($components, 1);
        $this->parseData($data);
    }

    private function parseKeys(string $header)
    : void {

        $formattedHeader = $this->getFormattedSQLString($header);
        $this->keys = explode(',', $formattedHeader);
    }

    private function getFormattedSQLString(string $header)
    : string {

        $replacementPattern = [
            '/INSERT INTO [A-Za-z`]* /',
            '/ VALUES/',
            '/[;`()\']/',
        ];

        return preg_replace($replacementPattern, '', $header);
    }

    private function parseData(array $data)
    : void {

        $getCorrectNullValue = fn(string $input) => $input === 'NULL' ? null : $input;
        foreach ($data as $datum) {
            $formattedDatum = $this->getFormattedDatum($datum);
            $this->data[] = array_map($getCorrectNullValue, $formattedDatum);
        }
    }

    private function getFormattedDatum(string $datum)
    : array {

        $formattedSQLString = $this->getFormattedSQLString($datum);
        $formattedString = preg_replace('/, /', ',', $formattedSQLString);
        $formattedDatum = explode(',', $formattedString);
        array_pop($formattedDatum);

        return $formattedDatum;
    }
}

