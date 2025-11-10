import './bootstrap';
import './media-library';
import Alpine from 'alpinejs';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.default.css';

// =======================================================
// === 1. UPGRADE LOGIKA VIDEO QUILL ===
// =======================================================

// Impor 'Video' blot bawaan Quill agar bisa kita perluas
const Video = Quill.import('formats/video');

// Daftar URL video yang kita izinkan (keamanan)
const ALLOWED_VIDEO_HOSTS = ['youtube.com', 'youtu.be', 'vimeo.com', 'facebook.com', 'tiktok.com'];

// Fungsi untuk mengubah URL 'tonton' menjadi URL 'embed'
function transformUrl(url) {
    let embedUrl = url;

    // =======================================================
    // PERBAIKAN LOGIKA FACEBOOK
    // =======================================================
    // Cek apakah ini link /share/ atau /watch/ atau /videos/
    if (url.includes('facebook.com')) {

        // Kita tidak perlu mengubah link 'share' atau 'watch'.
        // Plugin Facebook (video.php) cukup pintar untuk
        // menanganinya JIKA kita memberinya URL yang bersih.

        // 1. Bersihkan parameter (cth: ?mibextid=...)
        const cleanUrl = url.split('?')[0];

        // 2. Gunakan URL yang sudah bersih itu untuk 'href'
        embedUrl = `https://www.facebook.com/plugins/video.php?href=${encodeURIComponent(cleanUrl)}&show_text=false&width=560`;
    }
    // =======================================================

    else if (url.includes('tiktok.com')) {
        // Ekstrak ID video TikTok
        const match = url.match(/tiktok\.com\/.*\/video\/(\d+)/);
        if (match && match[1]) {
            embedUrl = `https://www.tiktok.com/embed/v2/${match[1]}`;
        }
    }
    else if (url.includes('youtube.com/watch')) {
        // Ekstrak ID video YouTube
        const match = url.match(/v=([^&]+)/);
        if(match) embedUrl = `https://www.youtube.com/embed/${match[1]}`;
    }
    else if (url.includes('youtu.be')) {
        // Ekstrak ID video YouTube (shortlink)
        const match = url.match(/youtu\.be\/([^?]+)/);
        if(match) embedUrl = `https://www.youtube.com/embed/${match[1]}`;
    }
    else if (url.includes('vimeo.com')) {
        // Ekstrak ID video Vimeo
        const match = url.match(/vimeo\.com\/(\d+)/);
        if(match) embedUrl = `https://player.vimeo.com/video/${match[1]}`;
    }

    return embedUrl;
}

// Buat class Video kustom kita yang memperluas bawaan Quill
class CustomVideo extends Video {
    // Method ini dipanggil saat video dibuat
    static create(value) {
        // Panggil 'create' bawaan untuk membuat <iframe>
        const node = super.create();

        let url = value;
        // Cek keamanan URL
        try {
            const parsedUrl = new URL(url);
            if (!ALLOWED_VIDEO_HOSTS.some(host => parsedUrl.hostname.includes(host))) {
                console.warn('URL Video tidak diizinkan:', url);
                return node; // Kembalikan node kosong jika host tidak diizinkan
            }
        } catch (e) {
            console.error('URL Video tidak valid:', e);
            return node; // Kembalikan node kosong jika URL rusak
        }

        // Ubah URL menjadi URL embed yang benar
        const embedUrl = transformUrl(url);

        node.setAttribute('src', embedUrl);
        node.setAttribute('frameborder', '0');
        node.setAttribute('allowfullscreen', true);

        return node;
    }

    static value(domNode) {
        return domNode.getAttribute('src');
    }
}

// 2. DAFTARKAN KELAS KUSTOM KITA
// Ini akan 'menimpa' module 'video' bawaan Quill
Quill.register(CustomVideo, true);

// =======================================================
// === 3. INISIALISASI ALPINE (SUDAH BENAR) ===
// =======================================================

document.addEventListener('alpine:initializing', () => {

    Alpine.data('quillEditor', ({ name, value }) => ({
        editor: null,
        content: value,
        init() {
            if (this.$refs.editor.quill) return; // Penjaga (Sudah Benar)

            this.editor = new Quill(this.$refs.editor, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }, 'blockquote'],
                        ['video'] // <-- Tombol ini sekarang menggunakan logic kustom kita!
                    ]
                }
            });
            this.editor.root.innerHTML = this.content;
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
