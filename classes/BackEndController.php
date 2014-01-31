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
                    'theme_color', 'theme_color_overlay_text', 
                    'advanced_search', 
                    'map_view_status',
                );

                list($themeColor, $themeColorOverlayText, $advancedSearch, $mapViewStatus) = $this->request->getPostValues($postKeys);


                if (!preg_match('/^#[\w]+$/', $themeColor))
                    $themeColor = '';

                if (!preg_match('/^#[\w]+$/', $themeColorOverlayText))
                    $themeColorOverlayText = '';

                $advancedSearchStatus = (isset($advancedSearch['status']) 
                    && ($advancedSearch['status'] === 'on' || $advancedSearch['status'] === 'off')) ? $advancedSearch['status'] : 'on';

                $advancedSearchSearchButtonCSS = (isset($advancedSearch['search_button_css']) 
                    && preg_match('/^#[\w]+$/', $advancedSearch['search_button_css'])) ? $advancedSearch['search_button_css'] : '';

                $advancedSearchResetButtonCSS = (isset($advancedSearch['reset_button_css']) 
                    && preg_match('/^#[\w]+$/', $advancedSearch['reset_button_css'])) ? $advancedSearch['reset_button_css'] : '';

                $mapViewStatus = ($mapViewStatus === 'on' || $mapViewStatus === 'off') ? $mapViewStatus : 'on';

                $themeOptions = array(
                    'theme_color' => $themeColor,
                    'theme_color_overlay_text' => $themeColorOverlayText,
                    'advanced_search' => array(
                        'status' => $advancedSearchStatus,
                        'search_button_css' => $advancedSearchSearchButtonCSS,
                        'reset_button_css' => $advancedSearchResetButtonCSS,
                    ),
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
            $vars['theme_options_advanced_search'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH');
            $vars['theme_options_advanced_search_on'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_ON');
            $vars['theme_options_advanced_search_off'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_OFF');
            $vars['theme_options_advanced_search_search_button_css'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_SEARCH_BUTTON_CSS');
            $vars['theme_options_advanced_search_reset_button_css'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_RESET_BUTTON_CSS');
            $vars['theme_options_map_view'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_MAP_VIEW');
            $vars['theme_options_map_view_on'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_MAP_VIEW_ON');
            $vars['theme_options_map_view_off'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_THEME_MAP_VIEW_OFF');
            $vars['save_changes'] = $this->text->__('ADMIN_THEME_OPTIONS_TAB_SAVE_CHANGES');
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