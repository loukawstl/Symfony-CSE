/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      "./assets/**/*.js",
      "./templates/**/*.html.twig",
    ],
    safelist: [
      //used in Form/Admin/LimitedOfferType
      'bg-blue-900',
      'hover:bg-blue-600',
    ],
    theme: {
      extend: {
        colors: {
          'topHover': '#B6A886',
          'blueHome': '#1B3168',
          'greyHome': '#EFEDED ',
          'activebg': '#B6A886',
          'footerbg': '#B6A886',
          'modalbg': 'rgba(0,0,0,0.4)',
        },
      }
    },
    plugins: [],
}