const mix = require('./resources/mix');
mix
    .js('resources/admin/boot.js', 'assets/admin/js/boot.js')
    .js('resources/admin/start.js', 'assets/admin/js/plugin-main-admin-app.js')

    .copy('resources/admin/tinymce/editor-tinymce-button.js', 'assets/admin/js/editor-tinymce-button.js')
    .sass('resources/scss/plugin-main-admin.scss', 'assets/admin/css/plugin-main-admin.css')

    .sass('resources/scss/gutenblock.scss', 'assets/admin/css/gutenblock.css')
    .copy('resources/assets/admin/css/fonts', 'assets/admin/css/fonts')
    .copy('resources/assets/admin/img', 'assets/admin/img');

// mix.react('resources/admin/tinymce/gutenblock.js', 'resources/admin/tinymce/gutenblock-editor-build.js');
