<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Illuminate\Support\Facades\Log;

class GoogleSheetService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Laravel Google Sheets Sync');
        $this->client->setScopes([Sheets::SPREADSHEETS]);
        $this->client->setAuthConfig(storage_path('app/google-credentials.json'));
        $this->service = new Sheets($this->client);
    }

    public function updateSheet($spreadsheetId, $range, $data): void
    {
        try {
            $body = new \Google_Service_Sheets_ValueRange([
                'values' => $data
            ]);
            $params = ['valueInputOption' => 'RAW'];
            $this->service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
        } catch (\Exception $e) {
            Log::error('Google Sheet Update Error: ' . $e->getMessage());
        }
    }

    public function getSheetData($spreadsheetId, $range): array
    {
        try {
            $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
            if ($response->getValues()) {
                return $response->getValues();
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Google Sheet Read Error: ' . $e->getMessage());
            return [];
        }
    }
}
