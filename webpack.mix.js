let mix = require("laravel-mix");

require("./nova.mix");

mix.setPublicPath("dist")
    .js("resources/js/tool.js", "js")
    .vue({ version: 3 })
    // .css("resources/css/tool.css", "css")
    .postCss("resources/css/tool.css", "css", [
        require("tailwindcss")("tailwind.config.js"),
    ])
    .nova("ferdiunal/nova-shield");
