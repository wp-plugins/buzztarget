<?php

namespace BuzzTargetLive;

class BackEndScripts
{
    protected $config;
    
    public function __construct(Config $config)
    {
        $this->config = $config;

        add_action('admin_enqueue_scripts', array($this, 'adminEnqueueScripts'));
    }

    public function adminEnqueueScripts()
    {
        $staticURL = $this->config->getValue('static_url');

        // Listings page specific
        if (isset($_GET['page']) && $_GET['page'] === 'repl_admin')
        {
            wp_register_style('listings-page', $staticURL . 'admin/css/listings-page.css');
            wp_enqueue_style('listings-page');

            if (isset($_GET['tab']) && $_GET['tab'] === 'theme_options')
            {
                wp_register_script('theme-options-tab', $staticURL . 'admin/js/theme-options-tab.js', array('jquery'));
                wp_enqueue_script('theme-options-tab');
                
                wp_register_style('theme-options-tab', $staticURL . 'admin/css/theme-options-tab.css');
                wp_enqueue_style('theme-options-tab');
            }
            else
            {
                ///*
                // jQuery UI
                wp_register_style('jquery-ui', $staticURL . 'admin/css/jquery-ui.css');
                wp_enqueue_style('jquery-ui');

                // Fetch Listings JS
                wp_register_script('fetch-listings', $staticURL . 'admin/js/fetch-listings.js', array('jquery'));
                wp_enqueue_script('fetch-listings');

                //*/

                wp_register_script('fetch-settings-tab', $staticURL . 'admin/js/fetch-settings-tab.js', array('jquery'));
                wp_enqueue_script('fetch-settings-tab');

                // Fetch Settings Tab CSS
                wp_register_style('fetch-settings-tab', $staticURL . 'admin/css/fetch-settings-tab.css');
                wp_enqueue_style('fetch-settings-tab');
            }
        }
        elseif (isset($_GET['page']) && $_GET['page'] === 'repl_options')
        {
            wp_register_style('api-settings-page', $staticURL . 'admin/css/api-settings-page.css');
            wp_enqueue_style('api-settings-page');
        }
    }
}