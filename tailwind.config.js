// import novaTailwind from "../../../vendor/laravel/nova/tailwind.config.js";
const path = require("path");
const novaTailwind = require(path.resolve(
    __dirname,
    "../../vendor/laravel/nova/tailwind.config.js"
));

/** @type {import('tailwindcss').Config} */
module.exports = {
    prefix: "ns-",
    content: ["./resources/**/*{js,vue,blade.php}"],
    darkMode: ["class"],
    theme: {
        colors: novaTailwind.theme.colors,
        extend: {},
    },
    plugins: [require("tailwindcss-animated")],
};
