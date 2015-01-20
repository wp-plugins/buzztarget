<?php

namespace BuzzTargetLive;

define('BUZZTARTGET_USE_LIVE_API', true);

/*
    Plugin Name: BuzzTarget
    Plugin URI: www.buzztarget.com
    Description: A Commercial Real Estate Property Listing plugin for WordPress
    Version: 3.0.0
    Author: BuzzTarget
    Author URI: www.buzztarget.com
    License: GPLv2 or later
*/
    
// Autoloads BuzzTargetLive's classes.
require_once __DIR__ . '/classes/Autoloader.php';
$autoloader = new Autoloader();
$autoloader->setNamespace(__NAMESPACE__)
           ->setIncludePath(__DIR__ . '/classes/')
           ->register();

// Load configuration.
$config = new Config();

// Check if the plugin needs to be installed or upgraded.
$installCheck = new InstallCheck($config);
$installCheck->run();

// Load and initialise Twig Templating Engine.
require_once __DIR__ . '/includes/twig.php';
$twig = load_twig($config);

// Load translator.
require_once __DIR__ . '/includes/strings.php';
$text = new Text($strings);

// Load HTTP global vars manipulator
$request = new Request();

// Responsible for fetching listings from API
// Doesn't do anything at this stage, however
$listings = new Listings($config);

// We add our custom cron schedules here (weekly, monthly)
new CronSchedules($text);

add_action('init', __NAMESPACE__ . '\\bt_schedule_fetch_listings_event');

// Registers our properties post type.
new PropertiesPostType($text);

// Run specific code if we're in the backend (admin panel)
if (isInAdmin())
{
    new TinyMCE($config);
    new BackEndScripts($config);
    new BackEndController($config, $text, $twig, $request, $listings);
}
else // Otherwise we're in the frontend
{
    new FrontEndScripts($config);
    new FrontEndController($config, $text);
    $listingPagination = new ListingPagination();
    $listingSort = new ListingSort();
    new Shortcodes($config, $twig, $text, $listingPagination, $request, $listingSort);
    new FrontEndScripts($config);
}

/**
 * Schedules the fetch listings event.
 *
 * @since 1.1.0
 */
function bt_schedule_fetch_listings_event()
{
    if (!wp_next_scheduled('buzztarget_fetch_listings_event'))
    {
        wp_schedule_event(time(), 'buzztarget_fetch_schedule', 'buzztarget_fetch_listings_event');
    }
}

add_action('buzztarget_fetch_listings_event', __NAMESPACE__ .'\\buzztarget_fetch_listings');

/**
 * Fetches listings when the scheduled time has passed.
 *
 * @since 1.1.0
 *
 * @global Listings $listings Listings instance.
 */
function buzztarget_fetch_listings()
{
    global $listings;
    $listings->getListings();
}

/**
 * Whether the current user is inside the administration panel.
 *
 * @since 1.1.0
 *
 * @return bool True otherwise false.
 */
function isInAdmin()
{
    return (function_exists('is_admin') && is_admin());
}


add_action('init', function() {
    if(!session_id()) session_start();
});

require_once(__DIR__ . '/forms/index.php');