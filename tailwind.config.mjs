/** @type {import('tailwindcss').Config} */
const config = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/views/**/*.blade.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}

export default config