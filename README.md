# Filament Font Picker

A beautiful Google Fonts picker component for Filament forms with real-time font previews, search functionality, and category filtering.

<img width="450" alt="image" src="https://github.com/user-attachments/assets/e2de5634-f7af-4e0d-bf38-d6044da191e0" />

## Features

- 🔍 **Search Google Fonts**: Find fonts quickly with intelligent search
- 🏷️ **Category Filtering**: Filter by serif, sans-serif, monospace, display, and handwriting fonts  
- 👀 **Live Previews**: See font previews as you browse
- ⚡ **Performance Optimized**: Fonts load only when needed with intersection observer
- 🌙 **Dark Mode Support**: Seamless integration with Filament's dark mode
- 📱 **Mobile Friendly**: Responsive design that works on all devices

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

### Available options

Here are all the available options you can pass to the component:

```php
FontPicker::make('font')
    ->label('Heading Font')
    ->placeholder('Select a font family...')
```

## Requirements

- PHP 8.2+
- Filament 4.0+
- Laravel 11.0+

## Font Data

The component uses a curated list of Google Fonts stored in `google-fonts.json`. 
The fonts are categorized and include metadata for optimal filtering and searching.

## Browser Support

- Modern browsers with ES6+ support
- Intersection Observer API support
- CSS Grid and Flexbox support

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- Built for [Filament](https://filamentphp.com)
- Fonts provided by [Google Fonts](https://fonts.google.com)
- Created by [Charlie Etienne](https://github.com/charlieetienne)
- Inspired by [Filament Studio](https://filamentstudio.dev/)'s font picker by [Dennis Koch](https://github.com/sponsors/pxlrbt)
