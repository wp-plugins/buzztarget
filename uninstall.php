<?php

/**
 * Called when the plugin is uninstalling.
 *
 * @since 1.1.0
 *
 * @package BuzzTargetLive
 */

namespace WPBB;

if (!defined('WP_UNINSTALL_PLUGIN')) 
{
    exit();
}

// Delete plugin settings
if (!delete_option('repl_options'))
    error_log(__METHOD__ .': error deleting repl_options');


// Delete listings data
if (!delete_option('repl_listings'))
    error_log(__METHOD__ .': error deleting repl_listings');


// Delete properties data
if (!delete_option('repl_properties'))
    error_log(__METHOD__ .': error deleting repl_properties');


// Delete all posts with properties as custom type
global $wpdb;
if (!$wpdb->delete($wpdb->posts, array('post_type' => 'properties')))
    error_log(__METHOD__ .": error deleting posts with post type properties");


// Delete listings page and meta data
if (($listingsID = $wpdb->get_var("SELECT `id` FROM {$wpdb->posts} WHERE `post_title` = 'Listings';")) !== null)
{
    if (!$wpdb->delete($wpdb->posts, array('id' => $listingsID)))
        error_log(__METHOD__ .": error deleting listings page with id {$listingsID}");
    if (!$wpdb->delete($wpdb->postmeta, array('id' => $listingsID)))
        error_log(__METHOD__ .": error deleting listings page metadata with id {$listingsID}");
}
else
{
    error_log(__METHOD__ .": error fetching ID as variable for listings page from wp_posts");
}