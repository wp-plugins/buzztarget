<?php

namespace BuzzTargetLive;

class TinyMCE
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;

        add_filter("mce_external_plugins", array($this, 'mceExternalPlugins')); 
        add_filter('mce_buttons', array($this, 'mceButtons'));
        wp_enqueue_script('jquery-ui-dialog');
    }

    /**
     * Adds our own tiny mce plugin.
     *
     * Hooks onto the 'mce_external_plugins' filter action. This is used for 
     * inserting our shortcode.
     *
     * @access public
     *
     * @since 0.0.1
     *
     * @param array $plugins Array of plugins as key/value pairs with keys as 
     *                       the plugin names and values as the plugin 
     *                       location.
     *
     * @return array Modified tiny mce plugins.
     */
    public function mceExternalPlugins($plugins)
    {
        $adminStaticUrl = $this->config->getValue('admin_static_url');
        $plugins['repl'] = $adminStaticUrl . 'js/repl-tinymce-plugin.js';
        return $plugins;
    }

    /**
     * Adds our own buttons to the tinymce toolbar
     *
     * Hooks onto the 'mce_buttons' filter action. 
     *
     * @access public
     *
     * @since 0.0.1
     *
     * @param array $buttons Array of button names as string values.
     *
     * @return array Modified buttons.
     */
    public function mceButtons($buttons)
    {
        $buttons[] = 'repl';
        return $buttons;
    }
}