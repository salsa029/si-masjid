/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./resources/**/*.jsx",
        "./resources/**/*.tsx",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Poppins", "ui-sans-serif", "system-ui", "sans-serif"],
                amiri: ["Amiri", "Traditional Arabic", "serif"],
            },
            colors: {
                masjid: {
                    50: "#f0fdf4",
                    100: "#dcfce7",
                    200: "#bbf7d0",
                    300: "#86efac",
                    400: "#4ade80",
                    500: "#22c55e",
                    600: "#16a34a",
                    700: "#047857",
                    800: "#065f46",
                    900: "#064e3b",
                },
                gold: {
                    300: "#fde68a",
                    400: "#fcd34d",
                    500: "#f2c94c",
                    600: "#d4a017",
                },
            },
        },
    },
    plugins: [],
};
