import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        "./node_modules/flowbite/**/*.js"
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "primary-blue": {
                    "50": "#eff6ff",
                    "100": "#dbeafe",
                    "200": "#bfdbfe",
                    "300": "#93c5fd",
                    "400": "#60a5fa",
                    "500": "#3b82f6",
                    "600": "#2563eb",
                    "700": "#1d4ed8",
                    "800": "#1e40af",
                    "900": "#1e3a8a",
                    "950": "#172554"
                },
                primary: {
                    "default": "#21b156",
                    '50': '#f0fdf4',
                    '100': '#dcfce7',
                    '200': '#bcf6d0',
                    '300': '#87eeac',
                    '400': '#4cdc81',
                    '500': '#21b156',
                    '600': '#18a14b',
                    '700': '#177e3d',
                    '800': '#176434',
                    '900': '#15522d',
                    '950': '#062d16',
                },
                secondary: {
                    "50": "#f9fafb",
                    "100": "#f3f4f6",
                    "200": "#e5e7eb",
                    "300": "#d1d5db",
                    "400": "#9ca3af",
                    "500": "#6b7280",
                    "600": "#4b5563",
                    "700": "#374151",
                    "800": "#1f2937",
                    "900": "#111827",
                    "950": "#030712"
                }
            },
            screens: {
                'xxl': '1799px',
            },
            backgroundImage: {
                'blue-gradient': 'linear-gradient(135deg, #3c82f6, #18b0f8)',
                'dark-blue-gradient': 'linear-gradient(135deg, #1e40af, #60a5fa)',
            },
        },
    },

    plugins: [forms, require('flowbite/plugin')],
};
