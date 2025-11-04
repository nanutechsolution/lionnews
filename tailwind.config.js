import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'

export default {
  darkMode: 'class', // pastikan class mode, bukan 'media'
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
    './resources/js/**/*.jsx',
  ],

  theme: {
    extend: {
      colors: {
        brand: {
          primary: '#1E3A8A', // Deep Blue
          accent: '#FFD700', // Gold
          base: '#F5F5F5',
          dark: '#1F2937', // Gray-800
        },
      },

      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
        heading: ['"Merriweather Sans"', ...defaultTheme.fontFamily.sans],
      },

      typography: ({ theme }) => ({
        DEFAULT: {
          css: {
            color: theme('colors.gray.800'),
            a: {
              color: theme('colors.brand.primary'),
              textDecoration: 'none',
              '&:hover': { color: theme('colors.brand.accent'), textDecoration: 'underline' },
            },
            h1: { color: theme('colors.brand.primary'), fontWeight: '700' },
            h2: { color: theme('colors.brand.primary'), fontWeight: '600' },
            h3: { color: theme('colors.brand.primary') },
            strong: { color: theme('colors.brand.dark') },
            blockquote: {
              borderLeftColor: theme('colors.brand.accent'),
              color: theme('colors.gray.700'),
              fontStyle: 'italic',
            },
          },
        },
        invert: {
          css: {
            color: theme('colors.gray.200'),
            a: {
              color: theme('colors.brand.accent'),
              '&:hover': { color: theme('colors.yellow.300') },
            },
            h1: { color: theme('colors.brand.accent') },
            h2: { color: theme('colors.brand.accent') },
            h3: { color: theme('colors.brand.accent') },
            strong: { color: theme('colors.brand.accent') },
            blockquote: {
              borderLeftColor: theme('colors.brand.accent'),
              color: theme('colors.gray.300'),
            },
          },
        },
      }),
    },
  },

  plugins: [forms, typography],
}
