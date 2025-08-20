# Filament Font Picker

A beautiful Google Fonts picker component for Filament forms with real-time font previews, search functionality, and category filtering.

<img width="1280" height="640" alt="filament-font-picker-social-1280x640" src="https://github.com/user-attachments/assets/2f4ac6eb-7ddf-4b0c-9eb6-3fd0614e9a4b" />

## Features

- 🔍 **Search Google Fonts**: Find fonts quickly with intelligent search
- 🏷️ **Category Filtering**: Filter by serif, sans-serif, monospace, display, and handwriting fonts  
- 👀 **Live Previews**: See font previews as you browse
- ⚡ **Performance Optimized**: Fonts load only when needed with intersection observer
- 🌙 **Dark Mode Support**: Seamless integration with Filament's dark mode
- 📱 **Mobile Friendly**: Responsive design that works on all devices
- ♿ **Accessibility First**: Full keyboard navigation and screen reader support

## Installation

You can install the package via composer:

```bash
composer require charlieetienne/filament-font-picker
```

> [!IMPORTANT]
> If you have not set up a custom theme and are using Filament Panels follow the instructions in the [Filament Docs](https://filamentphp.com/docs/4.x/styling/overview#creating-a-custom-theme) first.

After setting up a custom theme add the plugin's views to your theme css file or your app's css file if using the standalone packages.

```css
@source '../../../../vendor/charlieetienne/filament-font-picker/resources/**/*.blade.php';
```

## Usage

Use the FontPicker component in your Filament forms:

```php
use CharlieEtienne\FilamentFontPicker\FontPicker;

FontPicker::make('font')
    ->label('Choose Font')
```

### Options

You can control which font categories are available and which are preselected:

#### Available Categories

Limit which categories are shown (restricts the available options):

```php
FontPicker::make('font')
    ->availableCategories([
        'serif', 
        'sans-serif', 
        'monospace', 
        'display', 
        'handwriting',
    ])
```

#### Selected Categories

Preselect certain categories when the component loads (users can still change them):

```php
FontPicker::make('font')
    ->selectedCategories([
        'monospace', 
        'handwriting',
    ])
```

#### Other options

You can customize all these options:

```php
FontPicker::make('font')
    ->placeholder('Select a font')
    ->searchPrompt('Search fonts')
    ->previewText('The quick brown fox jumps over the lazy dog')
    ->noResultsTitle('No fonts found matching')
    ->noResultsSubtitle('Try searching for serif, sans-serif, monospace, or display fonts')
    ->loadingMessage('Loading Google Fonts...')
    ->loadingPreviewMessage('Loading preview...')
```

### Updating the Google Fonts list

The package includes a built-in Google Fonts dataset that works out of the box. 

However, if you want to update the fonts data or need the latest fonts from Google, you'll need to configure a Google Fonts API key.

1. **Get a Google Fonts API key** from the [Google Cloud Console](https://developers.google.com/fonts/docs/developer_api#APIKey)
2. **Add your API key** to your `.env` file:
   ```env
   GOOGLE_FONTS_API_KEY=your_api_key_here
   ```
3. **Update the Google Fonts data** with the latest fonts from Google's API:
   ```bash
   php artisan filament-font-picker:update-fonts
   ```
    This command will fetch the latest fonts from Google and update the list of available fonts.

## Requirements

- PHP 8.2+
- Filament 4.0+
- Laravel 11.0+

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- Built for [Filament](https://filamentphp.com)
- Fonts provided by [Google Fonts](https://fonts.google.com)
- Created by [Charlie Etienne](https://github.com/charlieetienne)
- Inspired by [Filament Studio](https://filamentstudio.dev/)'s font picker by [Dennis Koch](https://github.com/sponsors/pxlrbt)
