module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './resources/css/**/*.css',
    './public/build/**/*.js'
  ],
  theme: {
    extend: {
      animation: {
        pop: 'pop 0.3s ease-out'
      },
      keyframes: {
        pop: {
          '0%': { transform: 'scale(0.7)' },
          '100%': { transform: 'scale(1)' }
        }
      }
    }
  },
  plugins: []
};
