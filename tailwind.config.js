/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./*.{php,html,js}", "./assets/js/*.js"],
  //content: ["./*.{html,js}", "./asset/js/*.js"],
  theme: {
    extend: {
      fontFamily: {
        poppins: ["Poppins", "sans-serif"],
        montserrat: ["Montserrat", "sans-serif"],
        ibm: ["IBM Plex Serif", "serif"],
      },
      textColor: {
        blue: "#50C4ED",
        bluetua: "#0366FF",
        customGreen: "#03482b",
      },
      colors: {
        "primary-color": "#255145",
        "icon-color": "#438B77",
        customBlue: "#50C4ED",
        "green-temp": "#055935",

        biru: "#1b4367",
      },

      backgroundSize: {
        auto: "auto",
        cover: "cover",
        contain: "contain",
        "50%": "50%",
        16: "4rem",
      },
    },
  },
  plugins: [],
};
