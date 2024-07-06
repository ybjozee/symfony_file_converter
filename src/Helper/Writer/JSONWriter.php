<?php

namespace App\Helper\Writer;

use App\Helper\Reader\FileContent;

final class JSONWriter extends AbstractWriter {

    public function write(FileContent $content)
    : void {

        $json = [];
        foreach ($content->data as $datum) {
            $result = [];
            foreach ($datum as $key => $value) {
                $result[$content->keys[$key]] = $value;
            }
            $json[] = $result;
        }

        file_put_contents(
            "$this->saveLocation/$this->fileName.json",
            json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }
}

