<?php

namespace BuzzTargetLive;

class InstallCheck
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function run()
    {
        $options      = get_option('repl_options');

        $releaseVers  = $this->config->getValue('version');
        $apiKey       = $this->config->getValue('api_key');
        $apiSecretKey = $this->config->getValue('api_secret_key');
        $apiUser      = $this->config->getValue('api_user');
        $apiUserToken = $this->config->getValue('api_user_token');

        // Install required.
        if (false === $options)
        {
            $values = array(
                'version'        => $releaseVers,
                'api_key'        => $apiKey,
                'api_secret_key' => $apiSecretKey,
                'api_user'       => $apiUser,
                'api_user_token' => $apiUserToken,
            );

            update_option('repl_options', $values);
        }
        else
        {
            if (isset($options['version']))
            {
                if (version_compare($releaseVers, $options['version'], '>'))
                {
                    // Upgade required.
                }
            }
            else
            {
                $options['version'] = $releaseVers;
                update_option('repl_options', $options);
            }
        }
    }
}