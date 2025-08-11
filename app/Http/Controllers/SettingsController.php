<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'google_sheet_url' => 'required|url',
        ]);

        $url = $request->input('google_sheet_url');
        preg_match('/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $url, $matches);

        if (isset($matches[1])) {
            $sheetId = $matches[1];

            $this->setEnvironmentValue('GOOGLE_SHEET_ID', $sheetId);

            Artisan::call('config:clear');

            return redirect()->route('items.index')
                ->with('success', 'URL Google Таблицы успешно обновлён.');
        }

        return redirect()->route('items.index')
            ->with('error', 'Не удалось извлечь ID из URL.');
    }

    protected function setEnvironmentValue($key, $value)
    {
        $path = base_path('.env');

        if (File::exists($path)) {
            file_put_contents($path, preg_replace(
                "/{$key}=.*/",
                "{$key}={$value}",
                file_get_contents($path)
            ));
        }
    }
}
