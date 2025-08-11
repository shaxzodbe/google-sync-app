<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Services\GoogleSheetService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class SyncGoogleSheet extends Command
{
    protected $signature = 'google:sync {--count=}';

    protected $description = 'Syncs items with status "Allowed" to a Google Sheet.';

    public function handle(GoogleSheetService $googleSheets)
    {
        $spreadsheetId = config('services.google.sheet_id');
        if (!$spreadsheetId) {
            $this->error('Google Sheet ID is not configured.');
            return CommandAlias::FAILURE;
        }

        $allowedItems = Item::allowed()->get();
        $header = ['ID', 'Name', 'Description', 'Status', 'Created At', 'Updated At', 'Comment'];
        $dataToSync = [$header];

        $itemsMap = $allowedItems->keyBy('id');

        $existingData = $googleSheets->getSheetData($spreadsheetId, 'Sheet1');
        $existingComments = [];
        if ($existingData) {
            $existingComments = collect($existingData)->mapWithKeys(function ($row) {
                return [$row[0] => $row[6] ?? null];
            })->filter(fn($comment) => $comment !== null)->toArray();
        }

        foreach ($allowedItems as $item) {
            $comment = $existingComments[$item->id] ?? null;
            $dataToSync[] = [
                $item->id,
                $item->name,
                $item->description,
                $item->status,
                $item->created_at,
                $item->updated_at,
                $comment
            ];
        }

        $googleSheets->updateSheet($spreadsheetId, 'Sheet1', $dataToSync);

        $this->info('Synchronization complete.');

        $count = (int) $this->option('count');
        $this->info('Fetching data from Google Sheet...');

        $dataFromSheet = $googleSheets->getSheetData($spreadsheetId, 'Sheet1');
        $progressBar = $this->output->createProgressBar(count($dataFromSheet) - 1);

        for ($i = 1; $i < count($dataFromSheet); $i++) {
            if ($count > 0 && $i > $count) {
                break;
            }
            $row = $dataFromSheet[$i];
            $comment = $row[6] ?? 'N/A';
            $this->line("ID: {$row[0]}, Comment: {$comment}");
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->line('');

        return CommandAlias::SUCCESS;
    }
}
