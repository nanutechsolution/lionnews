import './bootstrap';

import Alpine from 'alpinejs';
import Quill from 'quill';
import 'quill/dist/quill.snow.css'; 
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.default.css';
document.addEventListener('alpine:initializing', () => {
    Alpine.data('quillEditor', ({ name, value }) => ({
        editor: null,
        content: value, 
        init() {
            if (this.$refs.editor.quill) {
                return;
            }
            // 4. Inisialisasi Quill di <div> 'x-ref="editor"'
          this.editor = new Quill(this.$refs.editor, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ]
                }
            });

            // 5. Set konten awal dari database (jika edit)
            this.editor.root.innerHTML = this.content;

            // 6. SINKRONISASI: Saat editor berubah...
            this.editor.on('text-change', () => {
                this.content = this.editor.root.innerHTML;
            });
        }
    }));

    Alpine.data('darkModeToggle', () => ({
        // Baca status awal dari tag <html>
        isDark: document.documentElement.classList.contains('dark'),
        
        toggle() {
            this.isDark = !this.isDark;
            if (this.isDark) {
                localStorage.setItem('darkMode', 'true');
                document.documentElement.classList.add('dark');
            } else {
                localStorage.setItem('darkMode', 'false');
                document.documentElement.classList.remove('dark');
            }
        }
    }));

    Alpine.data('tomselect', (config) => ({
        tom: null,
        init() {
            // Guard clause (penjaga) jika x-ref belum siap
            if (!this.$refs.tomselect) {
                console.warn('TomSelect ref tidak ditemukan.');
                return;
            }
            
            // Penjaga "Already Initialized"
            if (this.$refs.tomselect.tomselect) { 
                return; 
            }

            const initialTags = config.initialTags || [];

            // PERBAIKAN: Ganti 'this.$el' dengan 'this.$refs.tomselect'
            this.tom = new TomSelect(this.$refs.tomselect, {
                plugins: ['remove_button'], 
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                options: initialTags,
                create: false, 
                load(query, callback) {
                    const url = `/admin/tags/search?q=${encodeURIComponent(query)}`;
                    fetch(url)
                        .then(response => response.json())
                        .then(json => { callback(json); })
                        .catch(() => { callback(); });
                }
            });
        },
        destroy() {
            if (this.tom) this.tom.destroy();
        }
    }));
});

// Jalankan Alpine
window.Alpine = Alpine;
Alpine.start();