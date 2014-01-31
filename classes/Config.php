<?php

namespace BuzzTargetLive;

class Config
{
    protected $values = array();

    public function __construct()
    {
        // Paths
        $this->values['root_path'] = dirname(dirname(__FILE__)) . '/';
        $this->values['public_path'] = $this->values['root_path'] . '/public/';
        $this->values['public_admin_path'] = $this->values['public_path'] . 'admin/';
        $this->values['static_path'] = $this->values['root_path'] . 'static/';
        $this->values['vendor_path'] = $this->values['root_path'] . 'vendor/';
        $this->values['templates_path'] = $this->values['root_path'] . 'templates/';
        $this->values['admin_templates_path'] = $this->values['templates_path'] . 'admin/';

        // Urls
        $this->values['root_url'] = plugin_dir_url(dirname(__FILE__));
        $this->values['static_url'] = $this->values['root_url'] . 'static/';
        $this->values['admin_static_url'] = $this->values['static_url'] . 'admin/';

        // API credentials.
        $this->values['api_url'] = 'http://' . (defined('BUZZTARTGET_USE_LIVE_API') 
            && BUZZTARTGET_USE_LIVE_API === true ? 'api' : 'dev') . '.buzztarget.com/api/Listings';
        $this->values['api_key'] = 'DfmlmUrY8UMVlOnjwKbE5EUBUpI=';
        $this->values['api_secret_key'] = 'kH5OVx1sNv0=';
        $this->values['api_user'] = 'albert@fcmre.com';
        $this->values['api_user_token'] = '29739c46-adbb-45b7-a754-9be707744564';

        // Versions
        $this->values['version'] = '1.1.0'; // Plugin version.
    }

    public function getValue($key)
    {
        return (is_array($this->values) && array_key_exists($key, $this->values)) ? $this->values[$key] : false;
    }

    public function getValues()
    {
        return $this->values;
    }
}