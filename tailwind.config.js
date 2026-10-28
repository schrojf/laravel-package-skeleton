import aspectRatio from '@tailwindcss/aspect-ratio';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.{js,ts,jsx,tsx}",
        "./workbench/resources/views/**/*.blade.php",
    ],
    theme: {
        extend: {},
    },
    plugins: [
        typography,
        forms,
        aspectRatio,
    ],
}
