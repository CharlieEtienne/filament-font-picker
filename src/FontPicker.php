<?php

namespace CharlieEtienne\FilamentFontPicker;

use Filament\Forms\Components\Field;

class FontPicker extends Field
{
    protected string $view = 'filament-font-picker::font-picker';

    protected array $availableCategories = [];
    protected array $selectedCategories = [];

    public function availableCategories(array $categories): static
    {
        $this->availableCategories = $categories;

        return $this;
    }

    public function getAvailableCategories(): array
    {
        return $this->availableCategories;
    }

    public function selectedCategories(array $categories): static
    {
        $this->selectedCategories = $categories;

        return $this;
    }

    public function getSelectedCategories(): array
    {
        return $this->selectedCategories;
    }
}
