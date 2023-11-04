/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "!./resources/views/admin/*.blade.php",
        "!./resources/views/layouts/admin.blade.php",
    ],
    theme: {
        fontFamily: {
            serif: ['Fraunces', 'serif'],
            sans: ['Alegreya Sans', 'sans'],
        },
        extend: {
            colors: {
                // primary: {
                //     DEFAULT: '#3B82F6',
                //     50: '#f5f3ff',
                //     100: '#e0e7ff',
                //     200: '#ddd6fe',
                //     300: '#c4b5fd',
                //     400: '#a78bfa',
                //     500: '#8b5cf6',
                //     600: '#7c3aed',
                //     700: '#6d28d9',
                //     800: '#5b21b6',
                //     900: '#4c1d95'
                // },
                primary: {
                    '50': '#edfcf3',
                    '100': '#d3f8df',
                    '200': '#aaf0c5',
                    '300': '#73e2a5',
                    '400': '#51d28f',
                    '500': '#17b267',
                    '600': '#0b9052',
                    '700': '#097345',
                    '800': '#0a5b37',
                    '900': '#094b30',
                    '950': '#042a1b',
                },

                // primary: {
                //     '50': '#ecf9ff',
                //     '100': '#d5efff',
                //     '200': '#b4e5ff',
                //     '300': '#81d6ff',
                //     '400': '#46bdff',
                //     '500': '#1b9bff',
                //     '600': '#037bff',
                //     '700': '#0063f8',
                //     '800': '#054dc2',
                //     '900': '#0b469d',
                //     '950': '#0d2b5e',
                // },


            }
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
    ],
}