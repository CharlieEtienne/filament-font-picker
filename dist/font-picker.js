function fontPicker(wire, statePath) {
    return {
        state: wire.$entangle(statePath),
        isOpen: false,
        isLoading: true,
        search: '',
        googleFonts: [],
        filteredFonts: [],
        fontPreviews: {},
        loadedFonts: new Set(),
        selectedCategories: [],

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
        },
    }
}
