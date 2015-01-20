<?php

/**
 * All the plugin's strings are contained within this file.
 *
 * @since 1.1.0
 *
 * @package BuzzTargetLive
 */

namespace BuzzTargetLive;

$strings = array(
    // Top menu page
    'ADMIN_TOP_MENU_PAGE_PAGE_TITLE' => 'BuzzTarget',
    'ADMIN_TOP_MENU_PAGE_MENU_TITLE' => 'BuzzTarget',
    'ADMIN_TOP_MENU_PAGE_MENU_TITLE_2' => 'Listings',

    /*
     * API Settings Page (Admin)
     */
    'API_SETTINGS_PAGE_CONTENT_HEADING'         => 'API Settings',
    'API_SETTINGS_PAGE_CONTENT_DESC'            => 'From here you can change your API key, secret key, username and user access token.',
    'API_SETTINGS_PAGE_CONTENT_SUB_HEADING'     => 'API Settings',
    'API_SETTINGS_PAGE_CONTENT_API_KEY'         => 'API Key',
    'API_SETTINGS_PAGE_CONTENT_API_SECRET_KEY'  => 'API Secret Key',
    'API_SETTINGS_PAGE_CONTENT_API_USER'        => 'API User',
    'API_SETTINGS_PAGE_CONTENT_API_USER_TOKEN'  => 'API User Token',
    'API_SETTINGS_PAGE_CONTENT_SAVE_CHANGES'    => 'Save Changes',
    'API_SETTINGS_PAGE_CONTENT_SAVE_CHANGES_SUCCESS' => 'API settings saved successfully.',
    'API_SETTINGS_PAGE_CONTENT_SAVE_CHANGES_FAILURE' => 'An error occurred attempting to save the API settings. Please try again.',


    /*
     * Listings page
     */
    'ADMIN_LISTINGS_PAGE_CONTENT_HEADING' => 'BuzzTarget',
    'ADMIN_LISTINGS_PAGE_CONTENT_DESC'    => 'From here you can configure your listings pull and display from BuzzTarget.',

    // Fetch Settings tab
    'ADMIN_FETCH_SETTINGS_TAB_NAME'     => 'Fetch Settings',
    'ADMIN_FETCH_SETTINGS_TAB_DESC'       => 'Set your fetch parameters and scheduling on this screen.',
    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE' => 'Fetch Schedule',
    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_DESC' => 'Setup your automatic listing fetch schedule.',

    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_INTERVAL' => 'Interval',
    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_INTERVAL_DAILY' => 'Daily',
    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_INTERVAL_WEEKLY' => 'Weekly',
    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_INTERVAL_MONTHLY' => 'Monthly',

    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_AT' => 'At',
    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_AT_AM' => 'AM',
    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_AT_PM' => 'PM',
    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_AT_EST' => 'EST',

    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_EVERY' => 'Every',
    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_EVERY_MONDAY' => 'Monday',
    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_EVERY_TUESDAY' => 'Tuesday',
    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_EVERY_WEDNESDAY' => 'Wednesday',
    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_EVERY_THURSDAY' => 'Thursday',
    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_EVERY_FRIDAY' => 'Friday',
    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_EVERY_SATURDAY' => 'Saturday',
    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_EVERY_SUNDAY' => 'Sunday',

    'ADMIN_FETCH_SETTINGS_TAB_FETCH_SCHEDULE_DAY_OF_EVERY_MONTH' => 'Day of Every Month',

    'ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER' => 'Listings Filter',
    'ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_DESC' => 'Determine what listings to pull',

    'ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_TYPES' => 'Listing Types',
    'ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_TYPES_FOR_SALE' => 'For Sale',
    'ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_TYPES_FOR_LEASE' => 'For Lease',

    'ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS' => 'Listing Status',
    'ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS_ACTIVE' => 'Active',
    'ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS_AVAILABLE' => 'Available',
    'ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS_DRAFT' => 'Draft',
    'ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS_INCONTRACT' => 'In Contract',
    'ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS_INACTIVE' => 'Inactive',
    'ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS_LEASED' => 'Leased',
    'ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS_OFFMARKET' => 'Off Market',
    'ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FILTER_LISTING_STATUS_SOLD' => 'Sold',

    'ADMIN_FETCH_SETTINGS_TAB_SAVE_SETTINGS_BUTTON_LABEL' => 'Save Settings',
    'ADMIN_FETCH_SETTINGS_TAB_SAVE_SETTINGS_RESULT_SUCCESS' => 'Fetch settings saved successfully.',
    'ADMIN_FETCH_SETTINGS_TAB_SAVE_SETTINGS_RESULT_FAILURE' => 'Fetch settings were not saved. This could be because the settings are the same as before. Please try again.',

    'ADMIN_FETCH_SETTINGS_TAB_LISTINGS_FETCH_LISTINGS_SUBMIT_BUTTON_LABEL' => 'Fetch Listings',

    'ADMIN_FETCH_SETTINGS_TAB_FETCH_LISTINGS_RESULT_SUCCESS' => 'Fetched listings successfully.',
    'ADMIN_FETCH_SETTINGS_TAB_FETCH_LISTINGS_RESULT_FAILURE' => 'Error attempting to fetch listings. Please check your settings and try again.',

    // Theme Options
    'ADMIN_THEME_OPTIONS_TAB_THEME_OPTIONS_HEADING' => 'Theme Options',
    'ADMIN_THEME_OPTIONS_TAB_THEME_OPTIONS_DESC' => 'Set your theme options',
    'ADMIN_THEME_OPTIONS_TAB_THEME_CSS_HEADING' => 'Custom CSS',
    'ADMIN_THEME_OPTIONS_TAB_THEME_CSS_DESC' => 'Customize your CSS based on your Theme',
    'ADMIN_THEME_OPTIONS_TAB_THEME_COLOR' => 'Theme Color',
    'ADMIN_THEME_OPTIONS_TAB_THEME_COLOR_OVERLAY_TEXT' => 'Theme Color Overlay Text',

    'ADMIN_THEME_OPTIONS_TAB_LISTING_STYLE' => 'Listing Style',
    'ADMIN_THEME_OPTIONS_TAB_LISTING_DETAIL_STYLE' => 'Listing Detail Style',
    'ADMIN_THEME_OPTIONS_TAB_LISTING_STYLE_1' => 'Grid View',
    'ADMIN_THEME_OPTIONS_TAB_LISTING_STYLE_2' => 'List View',
    'ADMIN_THEME_OPTIONS_TAB_LISTING_DETAIL_STYLE_1' => 'One Column View',
    'ADMIN_THEME_OPTIONS_TAB_LISTING_DETAIL_STYLE_2' => 'Two Column View',
    'ADMIN_THEME_OPTIONS_TAB_FULL_WIDTH' => 'Full Width?',

    'ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH' => 'Advanced Search with Map',
    'ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_ON' => 'On',
    'ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_OFF' => 'Off',

    'ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_SEARCH_BUTTON_CSS' => 'Search Button CSS',
    'ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_RESET_BUTTON_CSS' => 'Reset Button CSS',
    'ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_LISTING_TYPE' => 'Listing Type',
    'ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_STREET' => 'Street',
    'ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_CITY' => 'City',
    'ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_COUNTY' => 'County',
    'ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_ZIP' => 'Zip',
    'ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_PROPERTY_TYPE' => 'Property Type',
    'ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_BROKER' => 'Broker',
    'ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_KEYWORD' => 'Keyword',
    'ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_TOTAL_SIZE_RANGE' => 'Total Lot Size Range',
    'ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_SIZE_RANGE' => 'Size Range',
    'ADMIN_THEME_OPTIONS_TAB_THEME_ADVANCED_SEARCH_PRICE_RANGE' => 'Price Range',

    'ADMIN_THEME_OPTIONS_TAB_SHOW_SIZE_IN_ACRES' => 'Show size in Acres',
    'ADMIN_THEME_OPTIONS_TAB_ALLOW_LISTING_PER_PAGE_CHANGE' => 'Allow Listing Per Page Change',
    'ADMIN_THEME_OPTIONS_TAB_ALLOW_LISTING_PER_PAGE_CHANGE_ON' => 'On',
    'ADMIN_THEME_OPTIONS_TAB_ALLOW_LISTING_PER_PAGE_CHANGE_OFF' => 'Off',
    'ADMIN_THEME_OPTIONS_TAB_DEFAULT_LISTING_PER_PAGE' => 'Default Listing Per Page',
    'ADMIN_THEME_OPTIONS_TAB_THEME_SHOW_PRICE_ON_LISTING' => 'Show Price On Listing',
    'ADMIN_THEME_OPTIONS_TAB_THEME_SHOW_DOCUMENT_ICONS_ON_LISTING' => 'Show Document Icons',

    'ADMIN_THEME_OPTIONS_TAB_SHOW_SORT_BY' => 'Show Sort By',
    'ADMIN_THEME_OPTIONS_TAB_SHOW_SORT_BY_ON' => 'On',
    'ADMIN_THEME_OPTIONS_TAB_SHOW_SORT_BY_OFF' => 'Off',
    'ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY' => 'Default Sort By',
    'ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_NAME_A_Z' => 'Name A to Z',
    'ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_NAME_Z_A' => 'Name Z to A',
    'ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_PRICE_A_Z' => 'Price A to Z',
    'ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_PRICE_Z_A' => 'Price Z to A',
    'ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_DATE_A_Z' => 'Date A to Z',
    'ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_DATE_Z_A' => 'Date Z to A',
    'ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_SIZE_A_Z' => 'Size A to Z',
    'ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_SIZE_Z_A' => 'Size Z to A',
    'ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_BROKER_A_Z' => 'Broker A to Z',
    'ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_BROKER_Z_A' => 'Broker Z to A',
    'ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_COUNTY_A_Z' => 'County A to Z',
    'ADMIN_THEME_OPTIONS_TAB_DEFAULT_SORT_BY_COUNTY_Z_A' => 'County Z to A',

    'ADMIN_THEME_OPTIONS_TAB_THEME_MAP_VIEW' => 'Map View',
    'ADMIN_THEME_OPTIONS_TAB_THEME_MAP_VIEW_ON' => 'On',
    'ADMIN_THEME_OPTIONS_TAB_THEME_MAP_VIEW_OFF' => 'Off',

    'ADMIN_THEME_OPTIONS_TAB_SAVE_CHANGES' => 'Save Changes',
    'ADMIN_THEME_OPTIONS_TAB_SAVE_SUCCESS' => 'Theme options saved successfully.',
    'ADMIN_THEME_OPTIONS_TAB_SAVE_FAILURE' => 'An error occurred attempting to save the theme options. Please try again.',

    'ADMIN_THEME_CSS_TAB_SAVE_CHANGES' => 'Save Changes',
    'ADMIN_THEME_CSS_TAB_RESTORE_CHANGES' => 'Restore Original',
    'ADMIN_THEME_CSS_TAB_SAVE_SUCCESS' => 'Theme CSS saved successfully.',
    'ADMIN_THEME_CSS_TAB_SAVE_FAILURE' => 'An error occurred attempting to save the theme CSS. Please try again.',
    'ADMIN_THEME_CSS_TAB_RESTORE_SUCCESS' => 'Theme CSS restored successfully.',
    'ADMIN_THEME_CSS_TAB_RESTORE_FAILURE' => 'An error occurred attempting to restore the theme CSS. Please try again.',

    // Map Options
    'ADMIN_MAP_OPTIONS_TAB_HEADING' => 'Map Options.',
    'ADMIN_MAP_OPTIONS_TAB_DESC' => 'Some Map Options description.',
    'ADMIN_MAP_OPTIONS_TAB_DEFAULT_MARKER' => 'Default',
    'ADMIN_MAP_OPTIONS_MARKERS_HEADING' => 'Custom Map markers images.',
    'ADMIN_MAP_OPTIONS_MARKERS_DESC' => 'You can set custom map markers image URLs for different property types here. Don\'t use big icons!',
    'ADMIN_MAP_OPTIONS_TAB_SAVE_CHANGES' => 'Save Map Options',
    'ADMIN_MAP_OPTIONS_TAB_SAVE_SUCCESS' => 'Map options saved successfully.',
    'ADMIN_MAP_OPTIONS_TAB_SAVE_FAILURE' => 'An error occurred attempting to save the map options. Please try again.',

    // Options page
    'ADMIN_SETTINGS_PAGE_TITLE' => 'API Settings',
    'ADMIN_SETTINGS_MENU_TITLE' => 'API Settings',

    // Listings page (frontend)
    'PUBLIC_LISTINGS_SEARCH_LISTINGS_HEADING'               => 'Search Listings',
    'PUBLIC_LISTINGS_SEARCH_LISTINGS_PROPERTY_NAME_EXAMPLE' => 'E.g. 127 Merrick Plaza (Optional)',
    'PUBLIC_LISTINGS_SEARCH_LISTINGS_SORT_ABC_DESC'         => 'Alphabetically (A-Z)',
    'PUBLIC_LISTINGS_SEARCH_LISTINGS_SORT_ABC_ASC'          => 'Alphabetically (Z-A)',
    'PUBLIC_LISTINGS_SEARCH_LISTINGS_SORT_PRICE_DESC'       => 'Price (Highest - Lowest)',
    'PUBLIC_LISTINGS_SEARCH_LISTINGS_SORT_PRICE_ASC'        => 'Price (Lowest - Highest)',
    'PUBLIC_LISTINGS_SEARCH_LISTINGS_SUBMIT_BUTTON_TEXT'    => 'Search Listings',

    'PUBLIC_LISTINGS_NO_FOR_SALE_LISTINGS' => 'Sorry, there aren\'t any properties available for sale at the moment. Please check back later.',
    'PUBLIC_LISTINGS_NO_FOR_LEASE_LISTINGS' => 'Sorry, there aren\'t any properties avilable for leasing at the moment. Please check back later.',
    'PUBLIC_LISTINGS_READ_MORE' => 'Read More',
    'PUBLIC_LISTINGS_PRICE'     => 'Price: ',
    'PUBLIC_LISTINGS_CAP_RATE'  => 'Cap Rate: ',
    'PUBLIC_LISTINGS_STATUS'    => 'Status: ',
    'PUBLIC_LISTINGS_DETAILS'   => 'Details',

    'PUBLIC_LISTINGS_LEASE_RENTAL_RATE' => 'Rental Rate: ',
    'PUBLIC_LISTINGS_LEASE_AVAILABLE' => 'Available: ',

    // Properties post type
    'PROPERTIES_POST_TYPE_LABEL'                        => 'Property',
    'PROPERTIES_POST_TYPE_LABELS_NAME'                  => 'Properties',
    'PROPERTIES_POST_TYPE_LABELS_SINGULAR_NAME'         => 'Property',
    'PROPERTIES_POST_TYPE_LABELS_MENU_NAME'             => 'Properties',
    'PROPERTIES_POST_TYPE_LABELS_ALL_ITEMS'             => 'All Properties',
    'PROPERTIES_POST_TYPE_LABELS_ADD_NEW'               => 'Add New',
    'PROPERTIES_POST_TYPE_LABELS_ADD_NEW_ITEM'          => 'Add New Property',
    'PROPERTIES_POST_TYPE_LABELS_EDIT_ITEM'             => 'Edit Property',
    'PROPERTIES_POST_TYPE_LABELS_NEW_ITEM'              => 'Add New Property',
    'PROPERTIES_POST_TYPE_LABELS_VIEW_ITEM'             => 'View Property',
    'PROPERTIES_POST_TYPE_LABELS_SEARCH_ITEMS'          => 'Search Properties',
    'PROPERTIES_POST_TYPE_LABELS_NOT_FOUND'             => 'No Properties found',
    'PROPERTIES_POST_TYPE_LABELS_NOT_FOUND_IN_TRASH'    => 'No Properties found in trash',
    'PROPERTIES_POST_TYPE_DESCRIPTION'                  => 'Displays properties',
    'PROPERTIES_POST_TYPE_LABELS_PARENT_ITEM_COLON'     => 'listings',

    // Our custom cronjob name
    'BUZZTARGET_FETCH_SCHEDULE' => 'BuzzTarget Fetch Schedule',

    /*
     * All listings page (frontend)
     */
    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_PROPERTY_SEARCH' => 'Property Search',
    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_KEYWORD' => 'Keyword',

    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_ADDRESS' => 'Address',
    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_ADDRESS_ADDRESS_LINE_1_PLACEHOLDER' => 'Address Line 1',
    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_ADDRESS_CITY_PLACEHOLDER' => 'City',
    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_ADDRESS_STATE_ZIP_PLACEHOLDER' => 'State, Zip (separated by commas)',

    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_LISTING_TYPE' => 'Listing Type',

    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_PROPERTY_TYPE' => 'Property Type',

    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_SIZE' => 'Size',
    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_SIZE_FROM_PLACEHOLDER' => 'From',
    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_SIZE_TO_PLACEHOLDER' => 'To',
    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_TOTAL_SIZE_FROM_PLACEHOLDER' => 'From',
    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_TOTAL_SIZE_TO_PLACEHOLDER' => 'To',

    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_PRICE' => 'Price',
    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_PRICE_FROM_PLACEHOLDER' => 'From',
    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_PRICE_TO_PLACEHOLDER' => 'To',

    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_RESET_BUTTON_LABEL' => 'Reset',
    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH_SEARCH_BUTTON_LABEL' => 'Search',

    'ALL_LISTINGS_PAGE_ADVANCED_SEARCH' => 'Advanced Search',

    'ALL_LISTINGS_PAGE_LIST_VIEW_BUTTON_LABEL' => 'List View',
    'ALL_LISTINGS_PAGE_MAP_VIEW_BUTTON_LABEL' => 'Map View',

    'ALL_LISTINGS_PAGE_PROPERTY_STATUS' => 'Status:',
    'ALL_LISTINGS_PAGE_PROPERTY_DETAILS' => 'Details',

    /**
     * Advanced search
     */

    // When no listings have been fetched, or there was a database error.
    'ADVANCED_SEARCH_NO_LISTINGS' => 'Sorry, there aren\'t any listings available at the moment. Please try again later.',

    // When a search doesn't turn up any results
    'ADVANCED_SEARCH_NO_RESULTS_FOUND' => 'No properties found matching your criteria.',
);