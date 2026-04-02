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
          primary: '#C2410C',   // orange-700 (principal fuerte)
          secondary: '#F97316', // orange-500 (base vibrante)
          accent: '#FDBA74',    // orange-300 (ligero y amigable)
          background: '#FFF7ED',// orange-50 (fondo suave)
          surface: '#FFFFFF',   // white
          text: '#111827',      // gray-900
          muted: '#78716C',     // stone-500
          border: '#FED7AA',    // orange-200
      },
      fontFamily: {
        'sans': ['Poppins', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
}