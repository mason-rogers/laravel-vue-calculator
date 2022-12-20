/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
      './resources/js/**/*.{html,vue}',
      './resources/views/**/*.blade.php',
  ],
  theme: {
    extend: {
        colors: {
            neutral: {
                '500': '#2a2d36',
                '800': '#282B33',
                '900': '#22252D',
            }
        },
        fontFamily: {
            'inter': ['Inter', 'sans-serif']
        }
    },
  },
  plugins: [],
}
