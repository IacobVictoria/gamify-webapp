import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                customGreen: '#62c864',
                customPink: '#e6092d',
                lightGreen: '#a8e6cf',
                darkerGreen: '#40893b',
                lightPink: '#f48fb1',
                darkerPink: '#ad1457',
                lightBlue: '#0369a1',
                darkBlue: '#075985'
            }
        },
    },

    plugins: [forms],
};
