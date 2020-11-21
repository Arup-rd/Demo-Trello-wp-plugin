<?php

namespace MyPlugin\Hooks\Handlers;

use MyPlugin\App;
use MyPlugin\Framework\Database\Orm\Model;

class TinyMce extends Model
{
    protected $table = 'my_plugin';
    public function addChartsToEditor()
    {
        if (user_can_richedit()) {
            $pages_with_editor_button = array('post.php', 'post-new.php');
            foreach ($pages_with_editor_button as $editor_page) {
                add_action("load-{$editor_page}", array($this, 'initNinjaMceButtons'));
            }
        }
    }

    public function initNinjaMceButtons()
    {
        add_filter("mce_external_plugins", array($this, 'addDataButton'));
        add_filter('mce_buttons', array($this, 'myPluginRegisterButton'));
        add_action('admin_footer', array($this, 'pushMyPluginToEditorFooter'));
    }

    public function addDataButton($plugin_array)
    {
        $plugin_array['my_plugin'] = MY_PLUGIN_URL . 'assets/admin/js/editor-tinymce-button.js';
        return $plugin_array;
    }

    public function myPluginRegisterButton($buttons)
    {
        array_push($buttons, 'my_plugin');
        return $buttons;
    }

    public function pushMyPluginToEditorFooter()
    {
        $data = $this->getAllChartsForMce(); ?>
        <script type="text/javascript">
            window.my_plugin_tiny_mce = {
                label: '<?php _e('Select a data to insert', 'my-plugin') ?>',
                title: '<?php _e('Insert My Plugin Shortcode', 'my-plugin') ?>',
                select_error: '<?php _e('Please select a data'); ?>',
                insert_text: '<?php _e('Insert Shortcode', 'my-plugin'); ?>',
                data: <?php echo json_encode($data); ?>,
                logo: <?php echo json_encode(MY_PLUGIN_URL . 'assets/admin/img/icon_small.png'); ?>
            }
        </script>
        <?php
    }

    private function getAllChartsForMce()
    {
        $my_plugin = [];
        // $my_plugin = TinyMce::select('id', 'chart_name')->orderBy('id', 'desc')->get();

        $formatted = array();

        $title = __('Select a data', 'my-plugin');
        if (!$my_plugin) {
            $title = __('No data found. Please add a data first');
        }

        $formatted[] = array(
            'text'  => $title,
            'value' => ''
        );

        foreach ($my_plugin as $chart) {
            $formatted[] = [
                'value'   => $chart->id,
                'text' => $chart->chart_name
            ];
        }
        return apply_filters('my_plugin_editor_available_data', $formatted);
    }

    public function gutenBlockLoad()
    {
        // add_action('enqueue_block_editor_assets', function () {
        //     wp_enqueue_script(
        //         'my-plugin-gutenberg-block',
        //         MY_PLUGIN_URL . 'assets/admin/js/gutenblock-editor-build.js',
        //         array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor')
        //     );

        //     wp_enqueue_style(
        //         'my-plugin-gutenberg-block',
        //         MY_PLUGIN_URL . 'assets/admin/css/gutenblock.css',
        //         array('wp-edit-blocks')
        //     );
        // });
    }
}
