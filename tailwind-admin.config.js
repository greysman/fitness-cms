/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors') 
 
module.exports = {
    content: [
        // './resources/**/*.blade.php',
        './vendor/filament/**/*.blade.php', 
        './vendor/rahmanramsi/filament-editorjs/resources/css/editor.css',
        './vendor/rahmanramsi/filament-editorjs/resources/views/**/*.blade.php',
        './vendor/awcodes/overlook/resources/**/*.blade.php',
        './vendor/koalafacade/filament-alertbox/**/*.blade.php',
        './app/Filament/**/*.php',
        "./vendor/suleymanozev/**/*.blade.php",
        "./resources/views/vendor/filament-radio-button-field/forms/components/radio-button.blade.php",
        // './app/Models/Status.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: { 
                danger: colors.rose,
                primary: colors.indigo,
                success: colors.green,
                warning: colors.yellow,
            }, 
        },
    },
    plugins: [
        require('@tailwindcss/forms'), 
        require('@tailwindcss/typography'), 
    ],
}