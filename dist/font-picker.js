function fontPicker(wire, statePath, availableCategories = [], selectedCategories = [], isDisabled = false) {
    return {
        state: wire.$entangle(statePath),
        isOpen: false,
        isLoading: true,
        isDisabled: isDisabled,
        search: '',
        googleFonts: [],
        filteredFonts: [],
        fontPreviews: {},
        loadedFonts: new Set(),
        selectedCategories: [...selectedCategories],
        highlightedIndex: -1,
        focusedElement: null,

        async init() {
            await this.loadGoogleFonts();

            this.$watch('state', (newValue) => {
                if (newValue) {
                    this.loadFontPreview(newValue);
                }
            });

            if (this.state) {
                this.loadFontPreview(this.state);
            }
        },

        open() {
            if (this.isDisabled) {
                return;
            }
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.$nextTick(() => {
                    this.$refs.searchInput?.focus();
                });
            }
        },

        async loadGoogleFonts() {
            try {
                const response = await fetch('/fonts/google-fonts.json');
                if (response.ok) {
                    const data = await response.json();
                    this.googleFonts = data.fonts;
                    this.applyFilters();
                } else {
                    console.warn('Google Fonts JSON not found');
                }
                this.isLoading = false;
            } catch (error) {
                console.error('Failed to load Google Fonts:', error);
                this.isLoading = false;
            }
        },

        searchFonts() {
            this.applyFilters();
        },

        applyFilters() {
            const query = this.search.toLowerCase().trim();
            let fonts = this.googleFonts;

            if (this.selectedCategories.length > 0) {
                fonts = fonts.filter(font => this.selectedCategories.includes(font.category));
            }

            if (query) {
                fonts = fonts.filter(font => {
                    return font.family.toLowerCase().includes(query) ||
                           font.category.toLowerCase().includes(query);
                });
            }

            this.filteredFonts = fonts;
            this.resetHighlight();
        },

        toggleCategoryFilter(category) {
            const index = this.selectedCategories.indexOf(category);
            if (index > -1) {
                this.selectedCategories.splice(index, 1);
            } else {
                this.selectedCategories.push(category);
            }
            this.applyFilters();
        },

        loadFontPreview(fontFamily) {
            if (this.fontPreviews[fontFamily] || this.loadedFonts.has(fontFamily)) {
                return;
            }

            this.loadedFonts.add(fontFamily);

            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = `https://fonts.googleapis.com/css2?family=${fontFamily.replace(' ', '+')}&display=swap`;

            link.onload = () => {
                setTimeout(() => {
                    this.fontPreviews[fontFamily] = true;
                }, 100);
            };

            link.onerror = () => {
                this.fontPreviews[fontFamily] = true;
            };

            document.head.appendChild(link);
        },

        loadFontWhenVisible(fontFamily, element) {
            this.loadFontPreview(fontFamily);
        },

        selectFont(fontFamily) {
            this.state = fontFamily;
            this.isOpen = false;
            this.loadFontPreview(fontFamily);
            this.highlightedIndex = -1;
        },

        // Keyboard navigation methods
        handleKeyDown(event) {
            if (this.isDisabled) {
                return;
            }

            if (!this.isOpen) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    this.open();
                }
                return;
            }

            // Prevent handling the same event multiple times
            if (event.defaultPrevented) {
                return;
            }

            switch (event.key) {
                case 'ArrowDown':
                    event.preventDefault();
                    this.highlightNext();
                    break;
                case 'ArrowUp':
                    event.preventDefault();
                    this.highlightPrevious();
                    break;
                case 'Enter':
                    event.preventDefault();
                    this.selectHighlightedFont();
                    break;
                case ' ':
                    if (event.target !== this.$refs.searchInput) {
                        event.preventDefault();
                        this.selectHighlightedFont();
                    }
                    break;
                case 'Escape':
                    event.preventDefault();
                    this.close();
                    break;
                case 'Home':
                    event.preventDefault();
                    this.highlightedIndex = 0;
                    this.scrollToHighlighted();
                    break;
                case 'End':
                    event.preventDefault();
                    this.highlightedIndex = Math.min(this.filteredFonts.length - 1, 49);
                    this.scrollToHighlighted();
                    break;
                case 'Tab':
                    this.close();
                    break;
            }
        },

        highlightNext() {
            const maxIndex = Math.min(this.filteredFonts.length - 1, 49);
            if (this.highlightedIndex < maxIndex) {
                this.highlightedIndex++;
                this.scrollToHighlighted();
            }
        },

        highlightPrevious() {
            if (this.highlightedIndex > 0) {
                this.highlightedIndex--;
                this.scrollToHighlighted();
            }
        },

        selectHighlightedFont() {
            if (this.highlightedIndex >= 0 && this.highlightedIndex < this.filteredFonts.length) {
                const font = this.filteredFonts[this.highlightedIndex];
                this.selectFont(font.family);
            }
        },

        scrollToHighlighted() {
            this.$nextTick(() => {
                const fontList = this.$refs.fontList;
                const options = fontList?.querySelectorAll('.font-option-container');
                const highlightedOption = options?.[this.highlightedIndex];

                if (highlightedOption && fontList) {
                    const optionTop = highlightedOption.offsetTop;
                    const optionBottom = optionTop + highlightedOption.offsetHeight;
                    const listTop = fontList.scrollTop;
                    const listBottom = listTop + fontList.clientHeight;

                    if (optionTop < listTop) {
                        fontList.scrollTop = optionTop;
                    } else if (optionBottom > listBottom) {
                        fontList.scrollTop = optionBottom - fontList.clientHeight;
                    }
                }
            });
        },

        close() {
            this.isOpen = false;
            this.highlightedIndex = -1;
        },

        resetHighlight() {
            this.highlightedIndex = -1;
        },
    }
}
