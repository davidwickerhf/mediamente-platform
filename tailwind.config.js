module.exports = {
  important: true,
  prefix: "tw-",
  content: ["./src/**/*.{html,js}", "./views/**/*.{php,js}"],
  theme: {
    extend: {},
  },
  plugins: [require("flowbite/plugin")],
  variants: {
    extend: {
      // ...
      display: ["hover", "focus", "group-hover"],
    },
  },
  theme: {
    screens: {
      sm: "480px",
      md: "768px",
      lg: "976px",
      xl: "1440px",
    },
    colors: {
      blue: "#2E3D4D",
      "blue-transparent": "#2E3D4Da8",
      flamingo: "##ef5350e0",
      "grey-super-light": "#F5F5F5",
      "grey-light": "#EDEDED",
      "grey-medium": "#D9D9D9",
      "grey-dark": "#B4B4B4",
    },
    fontFamily: {
      nunito: ["Nunito Sans", "sans-serif"],
      montserrat: ["Montserrat", "sans-serif"],
    },
    extend: {
      spacing: {
        128: "32rem",
        144: "36rem",
      },
      borderRadius: {
        "4xl": "2rem",
      },
    },
  },
};
