<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="fontPicker($wire, @js($getStatePath()), @js($getAvailableCategories()), @js($getSelectedCategories()))"
        x-init="init()"
        {{ $getExtraAttributeBag() }}
    >
        <div class="fi-input-wrp fi-fo-select">
            <div class="fi-select-input relative">
                <!-- Loading indicator -->
                <div x-show="isLoading" class="w-full px-3 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                    <div class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ $getLoadingMessage() }}
                    </div>
                </div>

                <!-- Trigger Button -->
                <button
                    x-show="!isLoading"
                    @click="open()"
                    @keydown="handleKeyDown($event)"
                    type="button"
                    class="fi-select-input-btn"
                    :style="state ? `font-family: '${state}', system-ui, -apple-system, sans-serif` : ''"
                    :aria-expanded="isOpen"
                    aria-haspopup="listbox"
                    role="combobox"
                    :aria-label="state ? `Selected font: ${state}` : '{{ $getPlaceholder() }}'"
                >
                    <span x-text="state || '{{ $getPlaceholder() }}'" class="truncate"></span>
                </button>

                <!-- Dropdown Panel -->
                <div
                    x-show="isOpen && !isLoading"
                    @click.away="close()"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="fi-dropdown-panel overflow-hidden max-h-100 mt-1"
                    role="listbox"
                    aria-label="Font selection"
                >
                    <!-- Search Input -->
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <div class="fi-select-input-search-ctn">
                            <input
                                x-model="search"
                                x-ref="searchInput"
                                placeholder="{{ $getSearchPrompt() }}"
                                class="fi-input"
                                @keydown="handleKeyDown($event)"
                                @input="searchFonts"
                                role="searchbox"
                                aria-label="{{ $getSearchPrompt() }}"
                                aria-autocomplete="list"
                                :aria-activedescendant="highlightedIndex >= 0 ? `font-option-${highlightedIndex}` : null"
                            >
                        </div>

                        <!-- Category Filter Badges -->
                        <div class="flex gap-1 px-3 py-2 flex-wrap">

                            @php
                                $allCategories = [
                                    'serif' => 'Serif',
                                    'sans-serif' => 'Sans',
                                    'monospace' => 'Mono',
                                    'display' => 'Display',
                                    'handwriting' => 'Hand',
                                ];

                                $availableCategories = $getAvailableCategories();
                                $categories = empty($availableCategories) ? $allCategories : array_intersect_key($allCategories, array_flip($availableCategories));
                            @endphp

                            @foreach($categories as $key => $category)
                                <button
                                    @click="toggleCategoryFilter('{{ $key }}')"
                                    type="button"
                                    class="px-1.5 py-1 text-xs rounded-lg transition-colors border flex-shrink-0"
                                    :class="selectedCategories.includes('{{ $key }}')
                                    ? 'bg-primary-500 text-white border-primary-500'
                                    : 'border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700'"
                                >
                                    {{ $category }}
                                </button>
                            @endforeach


                        </div>
                    </div>

                    <!-- Font Options -->
                    <div class="max-h-60 overflow-y-auto overflow-x-clip" x-ref="fontList">
                        <template x-for="(font, index) in filteredFonts.slice(0, 50)" :key="font.family">
                            <div
                                x-data="{ fontLoaded: false }"
                                x-intersect.once="loadFontWhenVisible(font.family, $el)"
                                class="font-option-container"
                            >
                                <button
                                    @click="selectFont(font.family)"
                                    @mouseenter="highlightedIndex = index"
                                    type="button"
                                    class="w-full px-4 py-4 text-left text-sm text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors border-b border-gray-200 dark:border-gray-700/50 last:border-b-0 flex items-center justify-between"
                                    :class="{
                                        'bg-primary-50 dark:bg-primary-700/20': state === font.family,
                                        'bg-primary-100 dark:bg-primary-600/30': highlightedIndex === index && state !== font.family
                                    }"
                                    :style="fontPreviews[font.family] ? `font-family: '${font.family}', system-ui, -apple-system, sans-serif` : ''"
                                    role="option"
                                    :aria-selected="state === font.family"
                                    :id="`font-option-${index}`"
                                >
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <span
                                                x-text="font.family"
                                                class="text-lg font-medium truncate"
                                                :style="fontPreviews[font.family] ? `font-family: '${font.family}', system-ui, -apple-system, sans-serif` : ''"
                                            ></span>
                                        </div>

                                        <div class="text-base text-gray-600 dark:text-gray-400">
                                            <!-- Loading indicator for font preview -->
                                            <div x-show="!fontPreviews[font.family]" class="flex items-center">
                                                <div class="w-3 h-3 mr-2 border border-gray-400 border-t-transparent rounded-full animate-spin opacity-50"></div>
                                                <span>{{ $getLoadingPreviewMessage() }}</span>
                                            </div>

                                            <!-- Actual font preview -->
                                            <div
                                                x-show="fontPreviews[font.family]"
                                                class="truncate"
                                                :style="fontPreviews[font.family] ? `font-family: '${font.family}';` : ''"
                                            >
                                                {{ $getPreviewText() }}
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </template>

                        <!-- No results message -->
                        <div x-show="search && filteredFonts.length === 0" class="px-4 py-8 text-sm text-gray-500 text-center">
                            <div class="mb-2">
                                <svg class="w-8 h-8 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            {{ $getNoResultsTitle() }} "<span x-text="search" class="font-medium"></span>"
                                <div class="text-xs mt-1">{{ $getNoResultsSubtitle() }}</div>
                        </div>

                        <!-- Show limited results notice -->
                        <div x-show="filteredFonts.length > 50" class="px-4 py-2 text-xs text-gray-400 text-center border-t border-gray-200 dark:border-gray-700">
                            Showing first 50 results. Use search to narrow down options.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
