/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme')
const colors = require('tailwindcss/colors')

module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",

        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',

        './vendor/filament/**/*.blade.php',
        './vendor/awcodes/filament-quick-create/resources/**/*.blade.php',
        './vendor/bezhansalleh/filament-exceptions/resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontSize: {
                'tiny': '.7rem',
                'xs': '.75rem',
                'sm': '.875rem',
                'base': '1.0rem',
                'lg': '1.15rem',
                'xl': '1.25rem',
                '2xl': '1.7rem',
                '3xl': '1.875rem',
                '4xl': '2.25rem',
                '5xl': '3rem',
                '6xl': '4rem',
                '7xl': '5rem',
            },
            maxWidth: {
                'xsm': '22rem',
                'tiny': '16rem',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
    ],
}

