<?php

namespace CharlieEtienne\FilamentFontPicker;

use Filament\Forms\Components\Field;

class FontPicker extends Field
{
    protected string $view = 'filament-font-picker::font-picker';

    protected array $availableCategories = [];
    protected array $selectedCategories = [];
    protected ?string $placeholder;
    protected ?string $searchPrompt;
    protected ?string $loadingMessage;
    protected ?string $loadingPreviewMessage;
    protected ?string $previewText;
    protected ?string $noResultsTitle;
    protected ?string $noResultsSubtitle;

    public function availableCategories(array $categories): static
    {
        $this->availableCategories = $categories;

        return $this;
    }

    public function selectedCategories(array $categories): static
    {
        $this->selectedCategories = $categories;

        return $this;
    }

    public function placeholder(?string $string): static
    {
        $this->placeholder = $string;

        return $this;
    }

    public function searchPrompt(?string $string): static
    {
        $this->searchPrompt = $string;

        return $this;
    }

    public function loadingMessage(?string $string): static
    {
        $this->loadingMessage = $string;

        return $this;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder ?? trans('filament-font-picker::components.placeholder');
    }

    public function loadingPreviewMessage(?string $string): static
    {
        $this->loadingPreviewMessage = $string;

        return $this;
    }

    public function previewText(?string $string): static
    {
        $this->previewText = $string;

        return $this;
    }

    public function noResultsTitle(?string $string): static
    {
        $this->noResultsTitle = $string;

        return $this;
    }

    public function noResultsSubtitle(?string $string): static
    {
        $this->noResultsSubtitle = $string;

        return $this;
    }

    public function getSelectedCategories(): array
    {
        return $this->selectedCategories;
    }

    public function getAvailableCategories(): array
    {
        return $this->availableCategories;
    }

    public function getSearchPrompt(): ?string
    {
        return $this->searchPrompt ?? trans('filament-font-picker::components.search_prompt');
    }

    public function getLoadingMessage(): ?string
    {
        return $this->loadingMessage ?? trans('filament-font-picker::components.loading.fonts');
    }

    public function getLoadingPreviewMessage(): ?string
    {
        return $this->loadingPreviewMessage ?? trans('filament-font-picker::components.loading.preview');
    }

    public function getPreviewText(): ?string
    {
        return $this->previewText ?? trans('filament-font-picker::components.preview_text');
    }

    public function getNoResultsTitle(): ?string
    {
        return $this->noResultsTitle ?? trans('filament-font-picker::components.no_results.title');
    }

    public function getNoResultsSubtitle(): ?string
    {
        return $this->noResultsSubtitle ?? trans('filament-font-picker::components.no_results.subtitle');
    }

}
