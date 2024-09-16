import { generateTailwindColors } from '../../vendor/laravel/nova/generators'
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./resources/**/*.{js,vue}'],
  darkMode: ['class', '[class="dark"]'],
  prefix: 'ns-',
  corePlugins: {
    preflight: false,
  },
  theme: {
    colors: generateTailwindColors(),
    extend: {},
  },
  plugins: [require("tailwindcss-animated")],
}
