import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Definição da cor Caqui baseada no RGB
                'caqui': {
                    DEFAULT: 'rgb(155, 138, 92)', // Cor principal
                    'light': 'rgb(180, 163, 115)', // Para variações
                    'dark': 'rgb(130, 115, 68)', // Para variações
                }
            },
        },
    },

    plugins: [forms],
};
