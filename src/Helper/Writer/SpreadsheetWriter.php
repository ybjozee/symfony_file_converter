<?php

namespace App\Helper\Writer;

use App\Helper\Reader\FileContent;
use PhpOffice\PhpSpreadsheet\{IOFactory, Spreadsheet, Worksheet\Worksheet};
use PhpOffice\PhpSpreadsheet\Style\{Alignment, Border, Color, Style};

final class SpreadsheetWriter extends AbstractWriter {

    private int $rowIndex = 1;
    private string $type;
    private Spreadsheet $spreadsheet;

    public function __construct(
        string $saveLocation,
        string $fileName,
        bool   $isCSV
    ) {

        parent::__construct($saveLocation, $fileName);
        $this->type = $isCSV ? 'Csv' : 'Xlsx';

        $this->spreadsheet = new Spreadsheet;
    }

    public function write(FileContent $content)
    : void {

        $this->writeKeys($content->keys);
        $this->writeData($content->data);
        $this->autosizeColumns();
        $this->save();
    }

    private function writeKeys(array $keys)
    : void {

        foreach ($keys as $index => $value) {
            $column = $this->getColumnFromNumber($index);
            $this->writeHeader("$column$this->rowIndex", ucwords($value));
        }
        $this->rowIndex++;
    }

    private function getColumnFromNumber(int $number)
    : string {

        $letter = chr(65 + ($number % 26));
        $remainder = intval($number / 26);
        if ($remainder > 0) {
            return $this->getColumnFromNumber($remainder - 1).$letter;
        }

        return $letter;
    }

    protected function writeHeader(string $cell, string $value)
    : void {

        $this->writeToCell($cell, $value);
        $this->getStyle($cell)->getFont()->setBold(true);
        $this->applyThinBorder($cell);
        $this->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    protected function writeToCell(string $cell, ?string $value)
    : void {

        $this->getActiveSheet()->setCellValue($cell, $value);
    }

    protected function getActiveSheet()
    : Worksheet {

        return $this->spreadsheet->getActiveSheet();
    }

    private function getStyle(string $cell)
    : Style {

        return $this->getActiveSheet()->getStyle($cell);
    }

    protected function applyThinBorder(string $range)
    : void {

        $this->getStyle($range)->applyFromArray(
            [
                'borders'   => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => [
                            'argb' => Color::COLOR_BLACK,
                        ],
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
                ],
            ]
        );
    }

    private function writeData(array $data)
    : void {

        foreach ($data as $datum) {
            foreach ($datum as $index => $value) {
                $column = $this->getColumnFromNumber($index);
                $cell = "$column$this->rowIndex";
                $this->writeToCell($cell, $value);
                $this->applyThinBorder($cell);
            }
            $this->rowIndex++;
        }
    }

    private function autosizeColumns()
    : void {

        foreach ($this->spreadsheet->getWorksheetIterator() as $worksheet) {
            $this->spreadsheet->setActiveSheetIndex($this->spreadsheet->getIndex($worksheet));
            $sheet = $this->spreadsheet->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }
    }

    private function save()
    : void {

        $writer = IOFactory::createWriter($this->spreadsheet, $this->type);
        $fileExtension = strtolower($this->type);
        $writer->save("$this->saveLocation/$this->fileName.$fileExtension");
    }
}

