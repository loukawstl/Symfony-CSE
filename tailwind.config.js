/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      "./assets/**/*.js",
      "./templates/**/*.html.twig",
    ],
    theme: {
      extend: {
        colors: {
          'topHover': '#B6A886',
          'blueHome': '#1B3168',
          'greyHome': '#EFEDED ',
          'activebg': '#B6A886',
          'footerbg': '#B6A886',
        },
      }
    },
    plugins: [],
}