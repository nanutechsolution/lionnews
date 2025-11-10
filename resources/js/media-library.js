document.addEventListener('alpine:init', () => {
    Alpine.data('mediaLibrary', () => ({
        isOpen: false,
        media: [],
        selectedMediaId: null,
        selectedMediaItem: null,
        isLoading: false,
        currentPage: 1,
        lastPage: 1,
        totalMedia: 0,
        searchQuery: '',
        callback: null,

        init() {
            // Inisialisasi: Ambil media pertama kali saat modal dibuka
            this.$watch('isOpen', (value) => {
                if (value && this.media.length === 0) {
                    this.fetchMedia(1);
                }
            });
        },

        // Membuka modal dan mengatur callback
        open(callbackFunction) {
            this.callback = callbackFunction;
            this.isOpen = true;
            this.media = []; // Reset media setiap kali dibuka
            this.currentPage = 1;
            this.lastPage = 1;
            this.totalMedia = 0;
            this.searchQuery = '';
            this.selectedMediaId = null;
            this.selectedMediaItem = null;
            this.fetchMedia(1); // Fetch media saat membuka
        },

        // Mengambil daftar media dari API
        async fetchMedia(page) {
            if (this.isLoading || page > this.lastPage) return;

            this.isLoading = true;
            try {
                const response = await fetch(`/admin/media?page=${page}&search=${this.searchQuery}`);
                const data = await response.json();

                if (page === 1) {
                    this.media = data.data;
                } else {
                    this.media = [...this.media, ...data.data];
                }
                this.currentPage = data.meta.current_page;
                this.lastPage = data.meta.last_page;
                this.totalMedia = data.meta.total;
            } catch (error) {
                console.error('Error fetching media:', error);
            } finally {
                this.isLoading = false;
            }
        },

        // Mengecek scroll untuk infinite loading
        checkScroll() {
            if (this.$refs.mediaGrid.scrollHeight - this.$refs.mediaGrid.scrollTop <= this.$refs.mediaGrid.clientHeight + 100) {
                this.fetchMedia(this.currentPage + 1);
            }
        },

        // Memilih item media
        selectMedia(mediaItem) {
            this.selectedMediaId = mediaItem.id;
            this.selectedMediaItem = mediaItem;
        },

        // Mengkonfirmasi pilihan dan memanggil callback
        confirmSelection() {
            if (this.selectedMediaItem && this.callback) {
                this.callback(this.selectedMediaItem);
                this.isOpen = false;
            }
        }
    }));
});
