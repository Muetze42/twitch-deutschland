module.exports = {
    content: [
        "./resources/public/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            screens: {
                'xs': '425px',
                'lt': '1440px',
                '3xl': '1920px',
            },
            width: {
                'twitch-logo': '18.75rem',
                'temp': '14.6rem',
            },
            brightness: {
                '70': '.7',
                '80': '.8',
                '85': '.85',
            },
        },
    },
    plugins: [
        require('@tailwindcss/aspect-ratio'),
        require('tailwind-scrollbar'),
    ],
}
