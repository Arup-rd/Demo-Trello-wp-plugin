(function() {
    /*eslint-disable*/
    tinymce.create('tinymce.plugins.my_plugin', {
        init: function(editor, url) {
            editor.addButton('my_plugin', {
                title: 'Add My Plugin Shortcode',
                cmd: 'my_plugin_action',
                image: url.slice(0, url.length - 2) + 'img/icon_small.png'
            });

            editor.addCommand('my_plugin_action', function() {
                editor.windowManager.open(
                    {
                        title: window.my_plugin_tiny_mce.title,
                        body: [
                            {
                                type: 'listbox',
                                name: 'my_plugin_shortcode',
                                label: window.my_plugin_tiny_mce.label,
                                values: window.my_plugin_tiny_mce.data
                            }
                        ],
                        width: 768,
                        height: 100,
                        onsubmit: function(e) {
                            if (e.data.my_plugin_shortcode) {
                                editor.insertContent(
                                    '[my_plugin id="' +
                                        e.data.my_plugin_shortcode +
                                        '"]'
                                );
                            } else {
                                alert(window.my_plugin_tiny_mce.select_error);
                                return false;
                            }
                        },
                        buttons: [
                            {
                                text: window.my_plugin_tiny_mce.insert_text,
                                subtype: 'primary',
                                onclick: 'submit'
                            }
                        ]
                    },
                    {
                        tinymce: tinymce
                    }
                );
            });
        }
        // ... Hidden code
    });
    // Register plugin
    tinymce.PluginManager.add('my_plugin', tinymce.plugins.my_plugin);
})();
