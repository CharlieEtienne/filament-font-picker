<?php

namespace CharlieEtienne\FilamentFontPicker;

use Filament\Support\Assets\Js;
use Illuminate\Support\Facades\Route;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FontPickerServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-font-picker';

    public static string $viewNamespace = 'filament-font-picker';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasTranslations()
            ->hasViews(static::$viewNamespace);
    }

    public function packageBooted(): void
    {
        // Register the Alpine.js component
        FilamentAsset::register([
            Js::make('font-picker', __DIR__ . '/../dist/font-picker.js'),
        ], 'charlieetienne/filament-font-picker');

        // Register route to serve Google Fonts JSON directly from package
        Route::get('/fonts/google-fonts.json', function () {
            $jsonPath = __DIR__ . '/../resources/assets/google-fonts.json';

            if (!file_exists($jsonPath)) {
                abort(404, 'Google Fonts JSON not found');
            }

            $contents = file_get_contents($jsonPath);

            return response($contents)
                ->header('Content-Type', 'application/json')
                ->header('Cache-Control', 'public, max-age=86400'); // Cache for 24 hours
        })->name('filament-font-picker.google-fonts');

    }
}
