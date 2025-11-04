// resources/js/app.js
import './bootstrap';

import Alpine from 'alpinejs';
import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';


// Buat komponen Alpine 'tiptapEditor'
document.addEventListener('alpine:initializing', () => {
    Alpine.data('tiptapEditor', ({ name, value }) => ({
        editor: null,
        content: value, // Nilai awal dari <input type="hidden">

        init() {
            this.editor = new Editor({
                element: this.$refs.editor, // Kaitkan ke <div x-ref="editor">
                extensions: [
                    StarterKit.configure({
                        heading: {
                            levels: [2, 3], // Hanya izinkan H2 dan H3
                        },
                    }),
                ],
                content: this.content, // Isi awal editor

                // Setiap kali konten berubah...
                onUpdate: ({ editor }) => {
                    // ...update nilai <input type="hidden">
                    this.content = editor.getHTML(); 
                }
            });

            // Sinkronkan input hidden -> editor saat form reset (cth: old())
            this.$watch('content', (content) => {
                if (content === this.editor.getHTML()) {
                    return;
                }
                this.editor.commands.setContent(content, false);
            });
        },
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
});

// Jalankan Alpine
window.Alpine = Alpine;
Alpine.start();