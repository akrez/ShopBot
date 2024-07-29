<?php

namespace App\Support;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

class Excel
{
    public function export($fileName, $sheetNameSource)
    {
        $headers = [
            'Expires' => '0',
            'Content-Encoding' => 'UTF-8',
            'Content-type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        $callback = function () use ($sheetNameSource) {
            $spreadsheet = new Spreadsheet;
            //
            $ctiveSheetIndex = $spreadsheet->getActiveSheetIndex();
            $spreadsheet->removeSheetByIndex($ctiveSheetIndex);
            //
            foreach ($sheetNameSource as $sheetName => $sheetSource) {
                $sheet = $spreadsheet->createSheet();
                $sheet->setCodeName($sheetName);
                $sheet->setTitle($sheetName);
                $sheet->setRightToLeft(true);
                $sheet->fromArray($sheetSource);
                foreach ($sheet->getColumnIterator() as $column) {
                    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
                }
            }
            $writer = new XlsxWriter($spreadsheet);
            $writer->save('php://output');
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function read($path)
    {
        $result = [];
        //
        $reader = new XlsxReader;
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($path);
        //
        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $result[$sheetName] = $spreadsheet->getSheetByName($sheetName)->toArray();
        }

        //
        return $result;
    }
}
