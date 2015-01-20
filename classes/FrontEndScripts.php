<?php

namespace BuzzTargetLive;

class FrontEndScripts
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;

        add_action('wp_enqueue_scripts', array($this, 'wpEnqueueScripts'));
    }

    /**
     * Registers and enqueues scripts and styles in the frontend
     *
     * @access public
     *
     * @since 1.0.0
     */
    public function wpEnqueueScripts()
    {
        global $post;
        $staticURL = $this->config->getValue('static_url');

        // All listings page
        if (isset($post->post_content)
            && trim($post->post_content) === '[all-listings][/all-listings]')
        {
            wp_register_script('all-listings-page', $staticURL . 'js/all-listings-page.js');
            wp_enqueue_script('all-listings-page');
            wp_register_style('all-listings-page', $staticURL . 'css/properties_original.css');
            wp_enqueue_style('all-listings-page');
        }
        // For sale / for lease / individual properties
        else
        {
            if (get_post_type() === 'properties')
            {
                /* jQuery UI CSS */
                wp_register_style('bt-jquery-ui', $staticURL . 'css/jquery-ui.css');
                wp_enqueue_style('bt-jquery-ui');

                wp_register_style('single-property-page', $staticURL . 'css/properties_original.css');
                wp_enqueue_style('single-property-page');
                wp_register_script('single-property-page', $staticURL . 'js/single-property-page.js', array('jquery', 'jquery-ui-dialog'));
                wp_enqueue_script('single-property-page');
            }
            else
            {
                wp_register_script('all-listings-page', $staticURL . 'js/all-listings-page.js');
                wp_enqueue_script('all-listings-page');
                wp_register_style('all-listings-page', $staticURL . 'css/properties_original.css');
                wp_enqueue_style('all-listings-page');
            }
        }
        add_action( 'wp_head', array($this, 'wpFormBuzzTargetCss') );
    }

    public function wpFormBuzzTargetCss() {
        $css = get_option('buzztarget_css');
        if (!isset($css) || strlen($css) < 1) { // try to load that from custom properties.css
            $file = WP_PLUGIN_DIR . '/buzztarget/static/css/properties.css';
            // check wether custom properties.css exists and read from there
            if(file_exists($file)) {
                $css = file_get_contents($file);
            }
        }
        if(isset($css) && strlen($css) > 0) {
            $css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
            $css = str_replace( array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css );
            $css = str_replace('\\', '', $css);
            echo "<style type='text/css'>";
            echo $css;
            echo "</style>";
        }
    }
}

