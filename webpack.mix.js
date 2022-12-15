const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/app.js", "public/js").vue();

mix.styles(
    [
        "resources/assets/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css",
        "resources/assets/admin/dist/css/adminlte.min.css",
    ],
    "public/css/admin.css"
);

mix.scripts(
    [
        "resources/assets/admin/plugins/jquery/jquery.min.js",
        "resources/assets/admin/plugins/jquery-ui-dist/jquery-ui.js",
        "resources/assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js",
        "resources/assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js",
        "resources/assets/admin/dist/js/adminlte.min.js",
    ],
    "public/js/admin.js"
);