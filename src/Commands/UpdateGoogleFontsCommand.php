<?php

namespace CharlieEtienne\FilamentFontPicker\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class UpdateGoogleFontsCommand extends Command
{
    protected $signature = 'filament-font-picker:update-fonts';

    protected $description = 'Update Google Fonts JSON file from Google Fonts API';

    public function handle(): int
    {
        $this->info('Updating Google Fonts data...');

        $apiKey = config('filament-font-picker.google_fonts_api_key');

        if (empty($apiKey)) {
            $this->error('Google Fonts API key is not set. Please set GOOGLE_FONTS_API_KEY in your .env file.');
            return self::FAILURE;
        }

        try {
            // Fetch fonts from Google Fonts API sorted by popularity
            $this->info('Fetching fonts from Google Fonts API...');
            $response = Http::timeout(60)->get('https://www.googleapis.com/webfonts/v1/webfonts', [
                'key' => $apiKey,
                'sort' => 'popularity'
            ]);

            if (!$response->successful()) {
                $this->error('Failed to fetch fonts from Google Fonts API: ' . $response->status());
                return self::FAILURE;
            }

            $data = $response->json();

            if (!isset($data['items']) || !is_array($data['items'])) {
                $this->error('Invalid response from Google Fonts API');
                return self::FAILURE;
            }

            // Format the data to match the expected structure
            $formattedData = [
                'lastUpdated' => now()->toISOString(),
                'totalFonts' => count($data['items']),
                'fonts' => []
            ];

            foreach ($data['items'] as $font) {
                $formattedData['fonts'][] = [
                    'family' => $font['family'],
                    'category' => $font['category'],
                    'variants' => $font['variants'],
                    'subsets' => $font['subsets'],
                    'version' => $font['version'],
                    'lastModified' => $font['lastModified'],
                ];
            }

            // Write the updated data to the JSON file
            $jsonPath = __DIR__ . '/../../resources/assets/google-fonts.json';
            $jsonContent = json_encode($formattedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            if ($jsonContent === false) {
                $this->error('Failed to encode JSON data');
                return self::FAILURE;
            }

            File::put($jsonPath, $jsonContent);

            $this->info('Successfully updated Google Fonts data!');
            $this->info("Total fonts: {$formattedData['totalFonts']}");
            $this->info("Last updated: {$formattedData['lastUpdated']}");

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('An error occurred while updating Google Fonts data: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
