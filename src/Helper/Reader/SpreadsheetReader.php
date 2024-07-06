<?php

namespace App\Helper\Reader;

use PhpOffice\PhpSpreadsheet\IOFactory;

final class SpreadsheetReader extends AbstractReader {

    public function read(string $filePath)
    : void {

        $inputFileType = IOFactory::identify($filePath);
        /**  Create a new Reader of the type that has been identified  **/
        $reader = IOFactory::createReader($inputFileType);
        /**  Load $inputFileName to a Spreadsheet Object  **/
        $data = $reader->load($filePath)->getActiveSheet()->toArray();
        $this->keys = $data[0];
        $this->data = array_slice($data, 1);
    }
}

