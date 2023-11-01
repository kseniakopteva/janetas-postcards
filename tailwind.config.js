import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "rose-950": "#37142c",
                "rose-900": "#502d45",
                "rose-900/50": "#502d457f",
                "rose-800": "#5c3951",
                "rose-700": "#67445c",
                "rose-600": "#7a576f",
                "rose-500": "#96738b",
                "rose-400": "#b996ae",
                "rose-300": "#ddbad2",
                "rose-200": "#f6d3eb",
                "rose-100": "#ffe2f6",
                "rose-50": "#fff1fb",
            },
        },
    },

    plugins: [forms],
};
