/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/views/*.blade.php", // Direct Blade files in views folder
        "./resources/views/frontend/layouts/**/app.blade.php", // All Blade files inside the frontend folder
        "./resources/**/*.js", // JavaScript files
        "./resources/**/*.vue", // Vue files
    ],

    theme: {
      extend: {},
    },
    plugins: [],
  }
