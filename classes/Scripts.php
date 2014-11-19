<?php

namespace BuzzTargetLive;

class FrontEndScripts
{
    public function __construct(Config $config)
    {
        $this->config = $config;

        add_action('admin_enqueue_scripts', array($this, 'adminEnqueueScripts'));
        add_action('wp_enqueue_scripts', array($this, 'wpEnqueueScripts'));
    }

    public function adminEnqueueScripts()
    {
        $staticURL = $this->config->getValue('static_url');

        wp_register_style(
            'fetch-listings',
            $staticURL . 'admin/css/fetch-listings.css'
        );
        wp_enqueue_style('fetch-listings');

        wp_register_script(
            'fetch-listings',
            $staticURL . 'admin/js/fetch-listings.js',
            array('jquery')
        );
        wp_enqueue_script('fetch-listings');

        wp_register_style(
            'jquery-ui',
            $staticURL . 'admin/css/jquery-ui.css'
        );
        wp_enqueue_style('jquery-ui');
    }

    /**
     * Registers and enqueues scripts and styles in the frontend
     *
     * @access public
     *
     * @since 0.0.1
     */
    public function wpEnqueueScripts()
    {
        $staticURL = $this->config->getValue('static_url');

        // Listings page(s)
        wp_register_style(
            'repl-listings',
            $staticURL . 'css/listings.css'
        );
        wp_enqueue_style('repl-listings');

        // Listing page
        wp_register_style(
            'repl-listing',
            $staticURL . 'css/listing.css'
        );
        wp_enqueue_style('repl-listing');


        wp_register_script(
            'repl-listing', 
           $staticURL . 'js/listing.js',
            array('jquery')
        );
        wp_enqueue_script('repl-listing');
    }
}