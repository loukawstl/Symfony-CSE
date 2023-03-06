/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      "./assets/**/*.js",
      "./templates/**/*.html.twig",
    ],
    theme: {
      extend: {
        colors: {
          'topblue': '#1B3168',
          'topgrey': '#EFEDED',
        },
      }
    },
    plugins: [],
}