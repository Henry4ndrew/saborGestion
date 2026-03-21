/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'gastronomico': {
          'primary': '#8B4513',      // Marrón madera
          'secondary': '#D2691E',    // Naranja cálido
          'accent': '#F4A460',       // Melocotón
          'success': '#2E7D32',      // Verde oliva
          'warning': '#FFA000',       // Ámbar
          'danger': '#C62828',        // Rojo vino
          'info': '#0277BD',          // Azul marino
          'light': '#FFF8E7',         // Crema
          'dark': '#3E2723',          // Café oscuro
        },
      },
      fontFamily: {
        'sans': ['Poppins', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
}