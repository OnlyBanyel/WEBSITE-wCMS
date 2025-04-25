/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./**/*.{html}",
         "./**/*.php"
    ],
    theme: {
      extend: {
        colors: {
          primary: '#BD0F03',
          primaryLight: '#ee948e',
          primaryDark: '#8B0000',
          secondary: '#f5efef',
          neutral: '#6a6a6a',
        }
      }
    },
    plugins: [],
  }
  