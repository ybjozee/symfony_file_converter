<?php

namespace App\Helper\Writer;

use App\Helper\Reader\FileContent;

final class SQLWriter extends AbstractWriter {

    private string $sql = '';

    public function write(FileContent $content)
    : void {

        $this->writeInsertStatement($content->keys);
        $this->writeValues($content->data);
        file_put_contents("$this->saveLocation/$this->fileName.sql", $this->sql);
    }

    private function writeInsertStatement(array $keys)
    : void {

        $keyString = join("`,`", $keys);
        $this->sql .= "INSERT INTO `$this->fileName` (`$keyString`) VALUES \n";
    }

    private function writeValues(array $data)
    : void {

        $callback = function (?string $item)
        : string {

            if (is_null($item)) {
                return 'NULL';
            }

            return is_numeric($item) ? $item : "'$item'";
        };

        foreach ($data as $datum) {
            $datumString = join(', ', array_map($callback, $datum));
            $this->sql .= "\n($datumString),";
        }
        $this->sql .= ';';
        $this->sql = str_replace(',;', ';', $this->sql);
    }
}

