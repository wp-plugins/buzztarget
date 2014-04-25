<?php

namespace BuzzTargetLive;

class FrontEndController
{
    protected $config;
    protected $text;
    
    public function __construct(Config $config, Text $text)
    {
        $this->config = $config;
        $this->text = $text;
        add_filter('template_include', array($this, 'templateInclude'));

    }

    /**
     * Called before WP includes the template file
     *
     * Hooks onto the 'template_include' action, including our own file to 
     * display for our custom post type.
     *
     * @access public
     *
     * @since 0.0.1
     *
     * @param string $templatePath Path to the template file.
     *
     * @return string The template file path.
    */

    public function templateInclude($templatePath)
    {
        if (get_post_type() === 'properties')
        {
            if (is_single())
            {
                if ($themeFile = locate_template(array('new-single-property-page.php')))
                {
                    $templatePath = $themeFile;
                } 
                else 
                {
                    $templatesPath = $this->config->getValue('templates_path');
                    $templatePath = $templatesPath . 'new-single-property-page.php';
                }
            }
        }
        return $templatePath;
    }

}