<?php

namespace App\Services;

use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Google_Service_Sheets_ClearValuesRequest;

use Log;

class GoogleSheet 
{
    private $spreadSheetId;
    private $client;
    private $googleSheetService;

    public function __construct()
    {
        $this->spreadSheetId = config('google.spreadsheet_id'); 
        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path(config('google.service.file')));
        $this->client->addScope('https://www.googleapis.com/auth/spreadsheets');
        $this->googleSheetService = new Google_Service_Sheets($this->client); 
    }

    public function readGoogleSheet()
    {
        $dimensions = $this->getDimensions($this->spreadSheetId);
        $range = config('google.sheet_id').'!A1:' . $dimensions['colCount'];

        $data = $this->googleSheetService
            ->spreadsheets_values
            ->batchGet($this->spreadSheetId, ['ranges' => $range]);

        return $data->getValueRanges()[0]->values;
    }

    public function saveDataToSheet(array $data, bool $overwrite=false)
    {
        if ($overwrite) {
            $body = new Google_Service_Sheets_ClearValuesRequest();
            $response = $this->googleSheetService
                ->spreadsheets_values
                ->clear($this->spreadSheetId,'A:Z', $body);
        }

        $range = "A1";        
        $dimensions = $this->getDimensions($this->spreadSheetId);

        if (array_key_exists('rowCount', $dimensions)) {
            $range = "A" . ($dimensions['rowCount'] + 1);
        }

        $body = new Google_Service_Sheets_ValueRange([
            'values' => $data
        ]);

        $params = [
            'valueInputOption' => 'USER_ENTERED',
        ];
             
        return $this->googleSheetService
            ->spreadsheets_values
            ->update($this->spreadSheetId, $range, $body, $params);
    }

    private function getDimensions($spreadSheetId)
    {
        $rowDimensions = $this->googleSheetService->spreadsheets_values->batchGet(
            $spreadSheetId,
            ['ranges' => config('google.sheet_id').'!A:A', 'majorDimension' => 'COLUMNS']
        );

        // if data is present at nth row, it will return array till nth row
        // if all column values are empty, it returns null
        $rowMeta = $rowDimensions->getValueRanges()[0]->values;
        if (!$rowMeta) {
            return [
                'error' => true,
                'message' => 'missing row data'
            ];
        }

        $colDimensions = $this->googleSheetService->spreadsheets_values->batchGet(
            $spreadSheetId,
            ['ranges' => config('google.sheet_id').'!1:1', 'majorDimension' => 'ROWS']
        );

        // if data is present at nth col, it will return array till nth col
        // if all column values are empty, it returns null
        $colMeta = $colDimensions->getValueRanges()[0]->values;
        if (!$colMeta) {
            return [
                'error' => true,
                'message' => 'missing row data'
            ];
        }

        return [
            'error' => false,
            'rowCount' => count($rowMeta[0]),
            'colCount' => $this->colLengthToColumnAddress(count($colMeta[0]))
        ];
    }

    private function colLengthToColumnAddress($number)
    {
        if ($number <= 0) return null;

        $letter = '';
        while ($number > 0) {
            $temp = ($number - 1) % 26;
            $letter = chr($temp + 65) . $letter;
            $number = ($number - $temp - 1) / 26;
        }

        return $letter;
    }
}
