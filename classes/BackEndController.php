<?php

/**
 * Handles most back end tasks.
 *
 * @since 1.1.0
 *
 * @package BuzzTargetLive;
 */

namespace BuzzTargetLive;

use Twig_Environment;

class BackEndController
{
    public function __construct(Config $config, Text $text, Twig_Environment $twig, Request $request, Listings $listings)
    {
        $this->config = $config;
        $this->text = $text;
        $this->twig = $twig;
        $this->request = $request;
        $this->listings = $listings;

        add_action('admin_menu', array($this, 'adminMenu'));
    }

    /**
     * Adds our custom pages after the basic admin page structure is setup.
     *
     * Hooks onto the 'admin_menu' action.
     *
     * @access public
     *
     * @since 0.0.1
     */
    public function adminMenu()
    {
        // Plugin top menu page
        add_object_page(
            $this->text->__('ADMIN_TOP_MENU_PAGE_PAGE_TITLE'),
            $this->text->__('ADMIN_TOP_MENU_PAGE_MENU_TITLE'),
            'manage_options',
            'repl_admin',
            array($this, 'displayTopMenuPage'),
            trailingslashit(plugins_url('', dirname(__FILE__))) . 'cpt-icon.png'
        );
        // Settings are also added as a child of our main page
        add_submenu_page(
            'repl_admin',
            $this->text->__('ADMIN_SETTINGS_PAGE_TITLE'),
            $this->text->__('ADMIN_SETTINGS_MENU_TITLE'),
            'manage_options',
            'repl_options',
            array($this, 'displayOptionsPage')
        );
    }


    /**
     * Displays top menu contents. Also responsible for handling listing fetch.
     *
     * @access public
     *
     * @since 0.0.1
     */
    public function displayTopMenuPage()
    {

        if ($this->request->isPost())
        {
            // Theme options form submission
            if (isset($_POST['save_theme_options']))
            {
                $errors = array();

                // $_POST keys
                $postKeys = array(
                    'listing_style',
                    'listing_detail_style',
                    'full_width_style',
                    'advanced_search',
                    'show_size_in_acres',
                    'allow_listing_per_page_change',
                    'default_listing_per_page',
                    'show_price_on_listing',
                    'show_sort_by',
                    'default_sort_by',
                    'map_view_status',
                    'show_document_icons_on_listing'
                );

                list($listingStyle, $listingDetailStyle, $fullWidthStyle, $advancedSearch, $show_size_in_acres, $allow_listing_per_page_change, $default_listing_per_page, $show_price_on_listing, $show_sort_by, $default_sort_by, $mapViewStatus, $show_document_icons_on_listing) = $this->request->getPostValues($postKeys);

                $listingStyle = ($listingStyle === 'style1' || $listingStyle === 'style2') ? $listingStyle : 'style1';
                $listingDetailStyle = ($listingDetailStyle === 'style1' || $listingDetailStyle === 'style2') ? $listingDetailStyle : 'style1';
                $fullWidthStyle = ($fullWidthStyle === 'on' || $fullWidthStyle === 'off') ? $fullWidthStyle : 'on';

                $advancedSearchStatus = (isset($advancedSearch['status'])
                    && ($advancedSearch['status'] === 'on' || $advancedSearch['status'] === 'off')) ? $advancedSearch['status'] : 'on';

                $advancedSearchListingType = (isset($advancedSearch['listing_type'])
                    && ($advancedSearch['listing_type'] === 'on' || $advancedSearch['listing_type'] === 'off')) ? $advancedSearch['listing_type'] : 'on';

                $advancedSearchStreet = (isset($advancedSearch['street'])
                    && ($advancedSearch['street'] === 'on' || $advancedSearch['street'] === 'off')) ? $advancedSearch['street'] : 'on';

                $advancedSearchCity = (isset($advancedSearch['city'])
                    && ($advancedSearch['city'] === 'on' || $advancedSearch['city'] === 'off')) ? $advancedSearch['city'] : 'on';

                $advancedSearchCounty = (isset($advancedSearch['county'])
                    && ($advancedSearch['county'] === 'on' || $advancedSearch['county'] === 'off')) ? $advancedSearch['county'] : 'on';

                $advancedSearchZip = (isset($advancedSearch['zip'])
                    && ($advancedSearch['zip'] === 'on' || $advancedSearch['zip'] === 'off')) ? $advancedSearch['zip'] : 'on';

                $advancedSearchPropertyType = (isset($advancedSearch['property_type'])
                    && ($advancedSearch['property_type'] === 'on' || $advancedSearch['property_type'] === 'off')) ? $advancedSearch['property_type'] : 'on';

                $advancedSearchBroker = (isset($advancedSearch['broker'])
                    && ($advancedSearch['broker'] === 'on' || $advancedSearch['broker'] === 'off')) ? $advancedSearch['broker'] : 'on';

                $advancedSearchKeyword = (isset($advancedSearch['keyword'])
                    && ($advancedSearch['keyword'] === 'on' || $advancedSearch['keyword'] === 'off')) ? $advancedSearch['keyword'] : 'on';

                $advancedSearchSizeRange = (isset($advancedSearch['size_range'])
                    && ($advancedSearch['size_range'] === 'on' || $advancedSearch['size_range'] === 'off')) ? $advancedSearch['size_range'] : 'on';

                $advancedSearchTotalSizeRange = (isset($advancedSearch['total_size_range'])
                    && ($advancedSearch['total_size_range'] === 'on' || $advancedSearch['total_size_range'] === 'off')) ? $advancedSearch['total_size_range'] : 'on';

                $advancedSearchPriceRange = (isset($advancedSearch['price_range'])
                    && ($advancedSearch['price_range'] === 'on' || $advancedSearch['price_range'] === 'off')) ? $advancedSearch['price_range'] : 'on';

                $show_size_in_acres = ($show_size_in_acres === 'acres' || $show_size_in_acres === 'sf') ? $show_size_in_acres : 'sf';
                $allow_listing_per_page_change = ($allow_listing_per_page_change === 'on' || $allow_listing_per_page_change === 'off') ? $allow_listing_per_page_change : 'on';
                $default_listing_per_page = ($default_listing_per_page === '9' || $default_listing_per_page === '12' || $default_listing_per_page === '60') ? $default_listing_per_page : '9';
                $show_price_on_listing = ($show_price_on_listing === 'on' || $show_price_on_listing === 'off') ? $show_price_on_listing : 'on';
                $show_document_icons_on_listing = ($show_document_icons_on_listing === 'on' || $show_document_icons_on_listing === 'off') ? $show_document_icons_on_listing : 'on';


                $show_sort_by = ($show_sort_by === 'on' || $show_sort_by === 'off') ? $show_sort_by : 'on';
                $default_sort_by = ($default_sort_by === 'name_a_z' || $default_sort_by === 'name_z_a' ||$default_sort_by === 'price_a_z' || $default_sort_by === 'price_z_a' || $default_sort_by === 'date_a_z' || $default_sort_by === 'date_z_a' || $default_sort_by === 'size_a_z' || $default_sort_by === 'size_z_a' || $default_sort_by === 'broker_a_z' || $default_sort_by === 'broker_z_a' || $default_sort_by === 'county_a_z' || $default_sort_by === 'county_z_a') ? $default_sort_by : 'date_z_a';

                $mapViewStatus = ($mapViewStatus === 'on' || $mapViewStatus === 'off') ? $mapViewStatus : 'on';

                $themeOptions = array(
                    'listing_style' => $listingStyle,
                    'listing_detail_style' => $listingDetailStyle,
                    'full_width_style' => $fullWidthStyle,
                    'advanced_search' => array(
                        'status' => $advancedSearchStatus,
                        'listing_type' => $advancedSearchListingType,
                        'street' => $advancedSearchStreet,
                        'city' => $advancedSearchCity,
                        'county' => $advancedSearchCounty,
                        'zip' => $advancedSearchZip,
                        'property_type' => $advancedSearchPropertyType,
                        'broker' => $advancedSearchBroker,
                        'keyword' => $advancedSearchKeyword,
                        'size_range' => $advancedSearchSizeRange,
                        'total_size_range' => $advancedSearchTotalSizeRange,
                        'price_range' => $advancedSearchPriceRange
                    ),
                    'show_size_in_acres' => $show_size_in_acres,
                    'allow_listing_per_page_change' => $allow_listing_per_page_change,
                    'default_listing_per_page' => $default_listing_per_page,
                    'show_price_on_listing' => $show_price_on_listing,
                    'show_document_icons_on_listing' => $show_document_icons_on_listing,
                    'show_sort_by' => $show_sort_by,
                    'default_sort_by' => $default_sort_by,
                    'map_view_status' => $mapViewStatus,
                );

                update_option('buzztarget_scheduled_event', 'false');

                if (update_option('buzztarget_theme_options', $themeOptions))
                    $vars['save_theme_options_result'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_SAVE_SUCCESS');
                else
                    $vars['save_theme_options_result'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_SAVE_FAILURE');

            }
            // Fetch settings form submission
            elseif (isset($_POST['fetchListings']) || isset($_POST['save_fetch_settings']))
            {
                // returns specified $_POST key('s) value(s) after being filtered.
                $postKeys = array(
                    'fetch_schedule_interval', 'fetch_schedule_time', 'fetch_schedule_cycle',
                    'fetch_schedule_every_weekday', 'fetch_schedule_every_month_day'
                );
                $postFilters = array('wp_strip_all_tags', 'absint', 'wp_strip_all_tags', 'wp_strip_all_tags', 'absint');
                list ($interval, $at, $atCycle, $everyWeekDay, $everyMonthDay) = $this->request->getPostValues($postKeys, $postFilters);

                // Update fetch schedule options
                $fetchSchedule = array(
                    'interval' => $interval,
                    'at' => $at,
                    'at_cycle' => $atCycle,
                    'every_week_day' => $everyWeekDay,
                    'every_month_day' => $everyMonthDay,
                    'current_month' => date('m'),
                    'current_day' => date('d'),
                    'current_day_alpha' => date('l'),
                    'current_year' => date('Y'),
                );
                $updatedFetchScheduleOptions = update_option('buzztarget_fetch_schedule_options', $fetchSchedule);

                // Get listing types and statuses
                list($listingTypes, $listingStatuses) = $this->request->getPostValues(array('listing_types', 'listing_statuses'));

                // Update listings filter options
                $listingsFilter = array(
                    'listing_types' => $listingTypes,
                    'listing_statuses' => $listingStatuses,
                );
                $updatedListingsFilterOptions = update_option('buzztarget_listings_filter_options', $listingsFilter);

                /*
                 * This will allow the fetch listings event to be scheduled
                 * in buzztarget.php since fetch schedule / filter options
                 * could be changed.
                 */
                update_option('buzztarget_scheduled_event', 'false');

                // Attempt to Fetch listings
                if (isset($_POST['fetchListings']))
                {
                    $fetchedListings = $this->listings->getListings();

                    if ($fetchedListings)
                    {
                        $vars['fetch_listings_result'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_LISTINGS_RESULT_SUCCESS');
                    }
                    else
                    {
                        $vars['fetch_listings_result'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_LISTINGS_RESULT_FAILURE');
                    }
                }
                else
                {
                    if ($updatedFetchScheduleOptions || $updatedListingsFilterOptions)
                    {
                        $vars['fetch_listings_result'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_SAVE_SETTINGS_RESULT_SUCCESS');
                    }
                    else
                    {
                        $vars['fetch_listings_result'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_SAVE_SETTINGS_RESULT_FAILURE');
                    }
                }
            }
            elseif(isset($_POST['save_theme_css']) || isset($_POST['restore_theme_css'])){
                if(isset($_POST['save_theme_css'])){

                    if(update_option('buzztarget_css', $_POST['theme-css-text'])){
                        $vars['theme_css_save_result'] = $this->text->__('ADMIN_THEME_CSS_TAB_SAVE_SUCCESS');
                    }
                    else{
                        $vars['theme_css_save_result'] = $this->text->__('ADMIN_THEME_CSS_TAB_SAVE_FAILURE');
                    }
                }
                if(isset($_POST['restore_theme_css'])){
                    $originalFile = WP_PLUGIN_DIR . '/buzztarget/static/css/properties_original.css';
                    $cssOriginalFileContent = file_get_contents($originalFile);

                    if(update_option('buzztarget_css', $cssOriginalFileContent)){
                        $vars['theme_css_restore_result'] = $this->text->__('ADMIN_THEME_CSS_TAB_RESTORE_SUCCESS');
                    }
                    else{
                        $vars['theme_css_restore_result'] = $this->text->__('ADMIN_THEME_CSS_TAB_RESTORE_FAILURE');
                    }
                }

            }
            elseif(isset($_POST['save_map_options'])){
                // returns specified $_POST key('s) value(s) after being filtered.
                $postKeys = array(
                    'map_options_markers'
                );
                list ($markers) = $this->request->getPostValues($postKeys);
                $map_options = array(
                    'markers' => $markers
                    );
                update_option('buzztarget_map_options', $map_options);
                //var_dump($map_options);
            }

            wp_clear_scheduled_hook('buzztarget_fetch_listings_event');
        }

        $tab = (isset($_GET['tab'])) ? $_GET['tab'] : '';


        $vars['page_url']                   = admin_url(). 'admin.php?page=repl_admin';
        $vars['tab']                        = $tab;
        $vars['listings_page_heading']      = $this->text->__('ADMIN_LISTINGS_PAGE_CONTENT_HEADING');
        $vars['listings_page_description']  = $this->text->__('ADMIN_LISTINGS_PAGE_CONTENT_DESC');

        if ($tab === 'theme_options')
        {
            $vars['theme_options'] = get_option('buzztarget_theme_options');
            $vars['theme_options_heading'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_OPTIONS_HEADING');
            $vars['theme_options_desc'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_OPTIONS_DESC');
            $vars['theme_options_theme_color'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_COLOR');
            $vars['theme_options_theme_color_overlay_text'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_COLOR_OVERLAY_TEXT');

            $vars['theme_options_listing_style'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_LISTING_STYLE');
            $vars['theme_options_listing_detail_style'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_LISTING_DETAIL_STYLE');
            $vars['theme_options_listing_style_1'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_LISTING_STYLE_1');
            $vars['theme_options_listing_style_2'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_LISTING_STYLE_2');
            $vars['theme_options_listing_detail_style_1'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_LISTING_DETAIL_STYLE_1');
            $vars['theme_options_listing_detail_style_2'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_LISTING_DETAIL_STYLE_2');
            $vars['theme_options_full_width'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_FULL_WIDTH');

            $vars['theme_options_advanced_search'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH');
            $vars['theme_options_advanced_search_on'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_ON');
            $vars['theme_options_advanced_search_off'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_OFF');
            $vars['theme_options_advanced_search_search_button_css'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_SEARCH_BUTTON_CSS');
            $vars['theme_options_advanced_search_reset_button_css'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_RESET_BUTTON_CSS');
            $vars['theme_options_advanced_search_listing_type'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_LISTING_TYPE');
            $vars['theme_options_advanced_search_street'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_STREET');
            $vars['theme_options_advanced_search_city'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_CITY');
            $vars['theme_options_advanced_search_county'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_COUNTY');
            $vars['theme_options_advanced_search_zip'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_ZIP');
            $vars['theme_options_advanced_search_property_type'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_PROPERTY_TYPE');
            $vars['theme_options_advanced_search_broker'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_BROKER');
            $vars['theme_options_advanced_search_keyword'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_KEYWORD');
            $vars['theme_options_advanced_search_total_size_range'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_TOTAL_SIZE_RANGE');
            $vars['theme_options_advanced_search_size_range'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_SIZE_RANGE');
            $vars['theme_options_advanced_search_price_range'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_PRICE_RANGE');

            $vars['theme_options_show_size_in_acres'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_SHOW_SIZE_IN_ACRES');
            $vars['theme_options_allow_listing_per_page_change'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_ALLOW_LISTING_PER_PAGE_CHANGE');
            $vars['theme_options_allow_listing_per_page_change_on'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_ALLOW_LISTING_PER_PAGE_CHANGE_ON');
            $vars['theme_options_allow_listing_per_page_change_off'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_ALLOW_LISTING_PER_PAGE_CHANGE_OFF');
            $vars['theme_options_default_listing_per_page'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_DEFAULT_LISTING_PER_PAGE');
            $vars['theme_options_show_price_on_listing'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_SHOW_PRICE_ON_LISTING');
            $vars['theme_options_show_document_icons_on_listing'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_SHOW_DOCUMENT_ICONS_ON_LISTING');

            $vars['theme_options_show_sort_by'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_SHOW_SORT_BY');
            $vars['theme_options_show_sort_by_on'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_SHOW_SORT_BY_ON');
            $vars['theme_options_show_sort_by_off'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_SHOW_SORT_BY_OFF');
            $vars['theme_options_default_sort_by'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY');
            $vars['theme_options_default_sort_by_name_a_z'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_NAME_A_Z');
            $vars['theme_options_default_sort_by_name_z_a'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_NAME_Z_A');
            $vars['theme_options_default_sort_by_price_a_z'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_PRICE_A_Z');
            $vars['theme_options_default_sort_by_price_z_a'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_PRICE_Z_A');
            $vars['theme_options_default_sort_by_date_a_z'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_DATE_A_Z');
            $vars['theme_options_default_sort_by_date_z_a'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_DATE_Z_A');
            $vars['theme_options_default_sort_by_size_a_z'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_SIZE_A_Z');
            $vars['theme_options_default_sort_by_size_z_a'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_SIZE_Z_A');
            $vars['theme_options_default_sort_by_broker_a_z'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_BROKER_A_Z');
            $vars['theme_options_default_sort_by_broker_z_a'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_BROKER_Z_A');
            $vars['theme_options_default_sort_by_county_a_z'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_COUNTY_A_Z');
            $vars['theme_options_default_sort_by_county_z_a'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_COUNTY_Z_A');

            $vars['theme_options_map_view'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_MAP_VIEW');
            $vars['theme_options_map_view_on'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_MAP_VIEW_ON');
            $vars['theme_options_map_view_off'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_MAP_VIEW_OFF');
            $vars['save_changes'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_SAVE_CHANGES');
        }
        elseif ($tab === 'theme_css')
        {
            $cssContent = get_option('buzztarget_css');
            // ensure the CSS is present in database
            if(strlen($cssContent) < 1) {
                $file = WP_PLUGIN_DIR . '/buzztarget/static/css/properties.css';
                // check wether custom properties.css exists and read from there
                if(file_exists($file)) {
                    $cssFileContent = file_get_contents($file);
                } else { // otherwise read the original file
                    $cssFileContent = file_get_contents(WP_PLUGIN_DIR . '/buzztarget/static/css/properties_original.css');
                }
                update_option('buzztarget_css', $cssFileContent);
                $cssContent = $cssFileContent;
            }

            $vars['css_file_content'] = str_replace('\\', '', $cssContent);

            $vars['theme_css_heading'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_CSS_HEADING');
            $vars['theme_css_desc'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_CSS_DESC');

            // Submit Button Label
            $vars['save_css_changes'] = $this->text->__('ADMIN_THEME_CSS_TAB_SAVE_CHANGES');
            $vars['restore_css_changes'] = $this->text->__('ADMIN_THEME_CSS_TAB_RESTORE_CHANGES');
        }elseif ($tab === 'map_options')
        {
            // $test_arr = array(
            //     'default_marker' => '',
            //     'additional_markers' => array(
            //         'industrial' => 'test1',
            //         'retail' => 'test2')
            //     );
            // update_option('buzztarget_map_options', $test_arr);

            // construct the possible property types

            $vars['property_types'] = array();
            if ($listings = get_option('repl_listings')) {
                foreach($listings as $listing){
                    foreach($listing["PropertyTypes"] as $property_type) {
                        if(!in_array($property_type, $vars['property_types']))
                            $vars['property_types'][] = $property_type;
                    }
                }
            }

            $vars['map_options'] = get_option('buzztarget_map_options');

            $vars['map_options_heading'] = $this->text->__('ADMIN_MAP_OPTIONS_TAB_HEADING');
            $vars['map_options_desc'] = $this->text->__('ADMIN_MAP_OPTIONS_TAB_DESC');

            $vars['map_options_markers_heading'] = $this->text->__('ADMIN_MAP_OPTIONS_MARKERS_HEADING');
            $vars['map_options_markers_desc'] = $this->text->__('ADMIN_MAP_OPTIONS_MARKERS_DESC');

            $vars['map_options_default_marker'] = $this->text->__('ADMIN_MAP_OPTIONS_TAB_DEFAULT_MARKER');

            $vars['map_options_save_changes'] = $this->text->__('ADMIN_MAP_OPTIONS_TAB_SAVE_CHANGES');
        }
        else
        {
            // Get fetch schedule and filter options

            $fetchScheduleOptions = get_option('buzztarget_fetch_schedule_options');
            $filterOptions = get_option('buzztarget_listings_filter_options');
            $vars['fetch_schedule_options'] = $fetchScheduleOptions;
            $vars['filter_options'] = $filterOptions;

            // Translations below
            $vars['fetch_settings_desc'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_DESC');
            $vars['fetch_schedule_heading'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE');
            $vars['fetch_schedule_desc'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_DESC');

            // Fetch Schedule (Interval)
            $vars['fetch_schedule_internal'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_INTERVAL');
            $vars['fetch_schedule_interval_daily'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_INTERVAL_DAILY');
            $vars['fetch_schedule_interval_weekly'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_INTERVAL_WEEKLY');
            $vars['fetch_schedule_interval_monthly'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_INTERVAL_MONTHLY');

            // Fetch Schedule (AT)
            $vars['fetch_schedule_at']  = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_AT');
            $vars['fetch_schedule_am']  = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_AT_AM');
            $vars['fetch_schedule_pm']  = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_AT_PM');
            $vars['fetch_schedule_est'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_AT_EST');

            $vars['fetch_schedule_every'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_EVERY');

            // Get current month as an int.
            $current_month = (int) date('n');

            // Returns the last day of the current month
            $getMonthLastDay = function ($month) {
                if ($month === 9 || $month === 4 || $month === 6 || $month === 11)
                {
                    $lastDay = 30;
                }
                elseif ($month === 2)
                {
                    $lastDay = 28;
                }
                else
                {
                    $lastDay = 31;
                }
                return $lastDay;
            };

            // Last day of the current month
            $vars['current_month_last_day'] = $getMonthLastDay($current_month);

            // Monday-Sunday
            $vars['fetch_schedule_weekdays'] = array(
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_EVERY_MONDAY'),
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_EVERY_TUESDAY'),
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_EVERY_WEDNESDAY'),
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_EVERY_THURSDAY'),
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_EVERY_FRIDAY'),
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_EVERY_SATURDAY'),
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_EVERY_SUNDAY'),
            );

            // Day of Every Month
            $vars['day_of_every_month'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_DAY_OF_EVERY_MONTH');

            // Listings Filter
            $vars['listings_filter'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER');
            $vars['listings_filter_desc'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_DESC');

            // Listing Types
            $vars['listing_types_label'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_TYPES');
            $vars['listing_types'] = array(
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_TYPES_FOR_SALE'),
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_TYPES_FOR_LEASE'),
            );

            // Listing Status
            $vars['listing_status_label'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS');
            $vars['listing_statuses'] = array(
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS_ACTIVE'),
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS_AVAILABLE'),
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS_DRAFT'),
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS_INCONTRACT'),
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS_INACTIVE'),
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS_LEASED'),
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS_OFFMARKET'),
                $this->text->__('ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS_SOLD'),
            );

            // Save settings button label
            $vars['save_settings_button_label'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_SAVE_SETTINGS_BUTTON_LABEL');

            // Submit Button Label
            $vars['fetch_listings_submit_button_label'] = $this->text->__('ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FETCH_LISTINGS_SUBMIT_BUTTON_LABEL');
        }

        echo $this->twig->render('@admin/main.twig', $vars);
    }

   /**
     * Displays options page contents.
     *
     * @access public
     *
     * @since 0.0.1
     */
    public function displayOptionsPage()
    {
        require_once $this->config->getValue('public_admin_path') . 'api-settings.php';
    }


}