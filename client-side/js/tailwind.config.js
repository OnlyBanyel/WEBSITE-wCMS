module.exports = {
  content: ["./**/*.{html,php,js}"], // Adjust this to match your file patterns
  theme: {
    extend: {
      colors: {
        primary: "#BD0F03",
        primaryLight: "#ee948e",
        primaryDark: "#8B0000",
        secondary: "#f5efef",
        neutral: "#6a6a6a",
        accent: "#EC3D31",
        accentLight: "#FFF0F0",
      },
      fontFamily: {
        poppins: ["Poppins", "sans-serif"],
        inter: ["Inter", "sans-serif"],
        montserrat: ["Montserrat", "sans-serif"],
      },
      animation: {
        fade: "fadeIn 1.5s ease-in-out",
        "slide-up": "slideUp 0.8s ease-out",
        "pulse-slow": "pulse 3s infinite",
      },
      keyframes: {
        fadeIn: {
          "0%": { opacity: "0" },
          "100%": { opacity: "1" },
        },
        slideUp: {
          "0%": { transform: "translateY(20px)", opacity: "0" },
          "100%": { transform: "translateY(0)", opacity: "1" },
        },
      },
      boxShadow: {
        custom:
          "0 10px 25px -5px rgba(189, 15, 3, 0.1), 0 8px 10px -6px rgba(189, 15, 3, 0.1)",
        card: "0 4px 6px -1px rgba(189, 15, 3, 0.1), 0 2px 4px -1px rgba(189, 15, 3, 0.06)",
      },
    },
  },
  plugins: [],
};
