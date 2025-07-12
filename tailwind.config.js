/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    "./src/**/*.php",
    "./vendor/salienture/**/*.php",
    "./vendor/saicosys/**/*.php",
    "./templates/**/*.{php,html,js}",
    "./webroot/**/*.{js,css}",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}