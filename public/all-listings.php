<?php
namespace BuzzTargetLive;

switch($shortcode){
    case 'lease_listing':
        $_POST['advanced_search_submit'] = true;
        $_POST['listing_types'] = array('forlease');
        break;
    case 'sale_listing':
        $_POST['advanced_search_submit'] = true;
        $_POST['listing_types'] = array('forsale');
        break;
}

$search_listings = null;
$search_vars = array();

// Search form submission

if (isset($_POST['advanced_search_submit']) || isset($_GET['search']))
{
    if(isset($_GET['search']) && !isset($_POST['advanced_search_submit'])){
        $_POST = unserialize($_SESSION["wp-search"]);
    }else{
        $_SESSION["wp-search"] = serialize($_POST);
    }
    // Listings could not be retrieved from database, or none have been
    // fetched yet.
    if (!$listings = get_option('repl_listings'))
    {
        $search_vars['trans']['search_error'] = $this->text->__('ADVANCED_SEARCH_NO_LISTINGS');
    }
    else
    {
        // Holds any listings which met our criteria
        $listings_maybe_matching_search_criteria = array();

        // $_POST keys
        $postKeys = array(
            'address_line_1',
            'address_line_2',
            'county',
            'address_zip_state',
            'property_type',
            'broker',
            'keyword',
            'size_from', 'size_to',
            'price_from', 'price_to'
        );
        // $_POST filter for key(s)
        $postFilters = 'wp_strip_all_tags';
        // Get $_POST values from key(s)
        list($addressLine1,
            $addressLine2,
            $county,
            $addressZipState,
            $propertyType,
            $broker,
            $keyword,
            $sizeFrom, $sizeTo,
            $priceFrom, $priceTo) = $this->request->getPostValues($postKeys, $postFilters);

        // Fetch listing types separately
        list($listingTypes) = $this->request->getPostValues(array('listing_types'));

        if($sizeFrom){
            $sizeFrom = (float) $sizeFrom;
        }
        if($sizeTo){
            $sizeTo = (float) $sizeTo;
        }
        if($priceFrom){
            $priceFrom = (float) $priceFrom;
        }
        if($priceTo){
            $priceTo = (float) $priceTo;
        }
        // Saves form submission values
        $search_vars['saved'] = array(
            'address_line_1' => $addressLine1,
            'address_line_2' => $addressLine2,
            'county' => $county,
            'address_zip_state' => $addressZipState,
            'property_type' => $propertyType,
            'broker' => $broker,
            'keyword' => $keyword,
            'size_from' => $sizeFrom,
            'size_to' => $sizeTo,
            'price_from' => $priceFrom,
            'price_to' => $priceTo,
            'listing_types' => $listingTypes
        );

        // Save search criteria
        update_option('buzztarget_saved_search_values', $search_vars['saved']);

        // Create search criteria array
        foreach($search_vars['saved'] as $key => $value){
            if(is_array($value) && !empty($value)){
                $search_criteria[$key] = $value;
            }elseif(is_numeric($value) && $value != 0){
                $search_criteria[$key] = $value;
            }elseif(is_string($value) && trim($value) != ""){
                $search_criteria[$key] = trim($value);
            }
        }

        $getAvailableSpaceSize = function ($spacesToLease) {
            $sizes = array();
            foreach ($spacesToLease as $space)
            {
                if (isset($space['Size']))
                {
                    $sizes[] = $space['Size'];
                }
            }
            unset($space);
            return array(min($sizes), max($sizes));
        };

        $getSpacePrice = function ($spacesToLease) {
            $rates = array();
            foreach ($spacesToLease as $space)
            {
                if (isset($space['RentalRate']))
                {
                    $rates[] = $space['RentalRate'];
                }
            }
            unset($space);
            return array(min($rates), max($rates));
        };

        foreach ($listings as $key => $listing)
        {
            $matched = true;

            $forLease = (strtolower($listing['ListingType']) == 'forlease') ? true : false;
            $forSale = (strtolower($listing['ListingType']) == 'forsale') ? true : false;

            if(!($forLease || $forSale)){
                $forLease = true;
                $forSale = true;
            }

            if(count($search_criteria) && count($listing)){
                foreach($search_criteria as $s_criteria => $value){
                    switch($s_criteria){
                        case 'address_line_1': // address
                            if (! isset($listing['Property']['Address']['Address'])
                                || strripos($listing['Property']['Address']['Address'], $value) === false){
                                $matched = false;
                            }
                            break;
                        case 'address_line_2': // city
                            if (!isset($listing['Property']['Address']['City'])
                                || strripos(str_replace(array(',',' '), array("",""), $value), str_replace(array(',',' '), array("",""), $listing['Property']['Address']['City'])) === false){
                                $matched = false;
                            }
                            break;
                        case 'county': // address
                            if (! isset($listing['County'])
                                || strripos($listing['County'], $value) === false){
                                $matched = false;
                            }
                            break;
                        case 'address_zip_state': // state & zip
                            if(!isset($listing['Property']['Address']['State']) && !isset($listing['Property']['Address']['Zip'])){
                                $matched = false;
                            }else{
                                $stateZip = "";
                                if(isset($listing['Property']['Address']['State'])){
                                    $stateZip = $listing['Property']['Address']['State'];
                                }
                                if(isset($listing['Property']['Address']['Zip'])){
                                    $stateZip .= $listing['Property']['Address']['Zip'];
                                }
                                $addrStateZip = str_replace(array(',',' '), array("",""), $value);

                                if (strripos($stateZip, $addrStateZip) === false){
                                    $matched = false;
                                }
                            }
                            break;
                        case 'property_type':
                            if(!isset($listing['PropertyTypes'])){
                                $matched = false;
                            }else{
                                for($i = 0; $i < count($listing['PropertyTypes']); ++$i){
                                    if($listing['PropertyTypes'][$i] == $value){
                                        break;
                                    }
                                }
                                if($i >= count($listing['PropertyTypes'])){
                                    $matched = false;
                                }
                            }
                            break;
                        case 'broker':
                            if(!isset($listing['ListingAgents'])){
                                $matched = false;
                            }else{
                                for($i = 0; $i < count($listing['ListingAgents']); ++$i){
                                    if($listing['ListingAgents'][$i]['FirstName'] . ' ' . $listing['ListingAgents'][$i]['LastName'] == $value){
                                        break;
                                    }
                                }
                                if($i >= count($listing['ListingAgents'])){
                                    $matched = false;
                                }
                            }
                            break;
                        case 'keyword':
                            if(!in_multiarray($value, $listing)){
                                $matched = false;
                            }
                            break;
                        case 'size_from': //&& $listing['TotalLotSize'] <= $sizeTo |  && $availableSpaceSize[1] <= $sizeTo
                            if ($forSale){
                                if(!isset($listing['TotalLotSize'])
                                   || $listing['TotalLotSize'] < $value ){
                                    $matched = false;
                                }
                            }elseif($forLease){
                                if(! isset($listing['SpacesToLease'])){
                                    $matched = false;
                                }else{
                                    $availableSpaceSize = $getAvailableSpaceSize($listing['SpacesToLease']);
                                    if ($availableSpaceSize[0] < $value){
                                        $matched = false;
                                    }
                                }
                            }
                            break;
                        case 'size_to':
                            if ($forSale){
                                if(!isset($listing['TotalLotSize'])
                                    || $listing['TotalLotSize'] > $value ){
                                    $matched = false;
                                }
                            }elseif($forLease){
                                if(! isset($listing['SpacesToLease'])){
                                    $matched = false;
                                }else{
                                    $availableSpaceSize = $getAvailableSpaceSize($listing['SpacesToLease']);
                                    if ($availableSpaceSize[1] > $value){
                                        $matched = false;
                                    }
                                }
                            }
                            break;
                        case 'price_from':
                            if ($forSale){
                                if(!isset($listing['PropertyPrice'])
                                    || $listing['PropertyPrice'] < $value){
                                    $matched = false;
                                }
                            }elseif ($forLease){
                                if(! isset($listing['SpacesToLease'])){
                                    $matched = false;
                                }else{
                                    $spacePrice = $getSpacePrice($listing['SpacesToLease']);
                                    if ($spacePrice[0] < $value){
                                        $matched = false;
                                    }
                                }
                            }
                            break;
                        case 'price_to':
                            if ($forSale){
                                if(!isset($listing['PropertyPrice'])
                                    || $listing['PropertyPrice'] > $value){
                                    $matched = false;
                                }
                            }elseif ($forLease){
                                if(! isset($listing['SpacesToLease'])){
                                    $matched = false;
                                }else{
                                    $spacePrice = $getSpacePrice($listing['SpacesToLease']);
                                    if ($spacePrice[1] > $value){
                                        $matched = false;
                                    }
                                }
                            }
                            break;
                        case 'listing_types':
                            if (is_array($value) && !empty($value)){
                                for($i = 0; $i < count($value); ++$i){
                                    if ($value[$i] == strtolower($listing['ListingType'])){
                                        break;
                                    }
                                }
                                if($i >= count($listing['ListingType'])){
                                    $matched = false;
                                }
                            }
                            break;
                    }

                }
                if($matched){
                    $search_listings[] = $listing;
                }
            }else{
                $search_listings = $listings;
            }
        }

        if (is_array($search_listings) && !empty($search_listings))
        {
            // Save search listings to database
            update_option('buzztarget_saved_search_listings', $search_listings);
        }
        else
        {
            $search_vars['trans']['search_error'] = $this->text->__('ADVANCED_SEARCH_NO_RESULTS_FOUND');

            // Delete cached search values
            delete_option('buzztarget_saved_search_values');

            // Delete cached search listings
            delete_option('buzztarget_saved_search_listings');
        }
    }
}

/*
 * Map View
 */
if (isset($_GET['map_view']))
{
    $listings            = get_option('repl_listings');

    $listings = ($_POST['advanced_search_submit']) ? $search_listings : $listings;

    $themeOptions = get_option('buzztarget_theme_options');

    $vars = array(
        'listings'  => $listings,
        'map_view'  => true,
        'trans'     => array(
            'advanced_search' => array(
                'property_search' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_PROPERTY_SEARCH'),
                'keyword' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_KEYWORD'),
                'address' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_ADDRESS'),
                'address_line_1_placeholder' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_ADDRESS_ADDRESS_LINE_1_PLACEHOLDER'),
                'address_city_placeholder' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_ADDRESS_CITY_PLACEHOLDER'),
                'address_state_zip_placeholder' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_ADDRESS_STATE_ZIP_PLACEHOLDER'),
                'listing_type' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_LISTING_TYPE'),
                'property_type' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_PROPERTY_TYPE'),
                'size' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_SIZE'),
                'size_from_placeholder' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_SIZE_FROM_PLACEHOLDER'),
                'size_to_placeholder' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_SIZE_TO_PLACEHOLDER'),
                'price' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_PRICE'),
                'price_from_placeholder' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_PRICE_FROM_PLACEHOLDER'),
                'price_to_placeholder' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_PRICE_TO_PLACEHOLDER'),
                'reset_button_label' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_RESET_BUTTON_LABEL'),
                'search_button_label' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_SEARCH_BUTTON_LABEL'),
                'advanced_search' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH'),
            ),
            'list_view' => $this->text->__('ALL_LISTINGS_PAGE_LIST_VIEW_BUTTON_LABEL'),
            'map_view'  => $this->text->__('ALL_LISTINGS_PAGE_MAP_VIEW_BUTTON_LABEL'),
            'status'    => $this->text->__('ALL_LISTINGS_PAGE_PROPERTY_STATUS'),
            'details'   => $this->text->__('ALL_LISTINGS_PAGE_PROPERTY_DETAILS'),
        ),
        'theme_options' => $themeOptions,
        'theme_color' => $themeOptions['theme_color'],
        'theme_overlay_text_color' => $themeOptions['theme_color_overlay_text'],
        'property_url' => site_url() . '/property',
        'properties_url'             => site_url() . '/properties',
        'show_advanced_search_image' => $this->config->getValue('static_url') . 'images/show-advanced-search.png',
        'hide_advanced_search_image' => $this->config->getValue('static_url') . 'images/hide-advanced-search.png',
        //'saved' => $savedSearchValues,
    );

    $vars = array_merge_recursive($vars, $search_vars);

    /*
     * Pagination
     */
    $currentPage = $this->listingPagination->getCurrentPage();
    $totalPages = $this->listingPagination->getTotalPages();

    $_currentPage = $currentPage;
    $nextPage = (($_cPage = $_currentPage) + 1 > $totalPages) ? $_currentPage : $_cPage + 1;
    $previousPage = (($_cPage = $_currentPage) - 1 === 0) ? $_currentPage : $_cPage - 1;

    $start = $this->listingPagination->getStart();
    $end = $this->listingPagination->getEnd();

    $vars['pagination'] = array(
        'current_page' => $currentPage,
        'total_pages' => absint($totalPages),
        'next_page' => $nextPage,
        'previous_page' => $previousPage,
        'start' => $start,
        'end' => $end,
        'listings_current_page_url' =>  site_url() . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?map_view=true&current_page=',
    );

    $vars['url'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

}else{
/*
 * List View
 */
    if (isset($_GET['list_view']) && $_GET['list_view'] === 'main' && !isset($_POST['advanced_search_submit']))
    {
        // Delete cached search values
        delete_option('buzztarget_saved_search_values');

        // Delete cached search listings
        delete_option('buzztarget_saved_search_listings');
    }

    // Attempt to fetch cached listings.
    if (!$listings = get_option('repl_listings'))
        return;

    if(!is_array($search_listings)){
        $search_listings = array();
    }

    $broker_list = [];
    $property_type_list = [];
    $county_list = [];
    foreach($listings as $property){
        $listingAgents = $property['ListingAgents'];
        $propertyTypes = $property['PropertyTypes'];
        $county = $property['County'];
        foreach($listingAgents as $agent){
            $broker = $agent['FirstName'] . ' ' . $agent['LastName'];
            if (!in_array($broker, $broker_list)) {
                $broker_list[] = $broker;
            }
        }
        foreach($propertyTypes as $property){
            $propertyType = $property;
            if ($propertyType && !in_array($propertyType, $property_type_list)) {
                $property_type_list[] = $propertyType;
            }
        }
        if ($county && !in_array($county, $county_list)) {
            $county_list[] = $county;
        }
    }

    // Return listings matching search criteria if a search has been requested
    // otherwise just return the listings for the current page only.

    $listings = ($_POST['advanced_search_submit']) ? $search_listings : $listings;
    $all_properties = $listings;


    if(count($listings)){
        foreach($listings as $key => $val){
            $draft_page = get_post($listings[$key]['wp_page_id']);
            if($draft_page->post_status != 'publish'){
                unset($listings[$key]);
            }
        }
    }

    $themeOptions = get_option('buzztarget_theme_options');
    $is_limit_changed = 'false';
    if(isset($_GET['limit_per_page'])){
        $is_limit_changed = 'true';
        $listing_per_page = $_GET['limit_per_page'];
    }
    else{
        $listing_per_page = $themeOptions['default_listing_per_page'];
    }

    $allow_listing_per_page_change = $themeOptions['allow_listing_per_page_change'];
    $show_price_on_listing = $themeOptions['show_price_on_listing'];

    $show_filter_form = $themeOptions['advanced_search']['status'];
    $show_listing_type = $themeOptions['advanced_search']['listing_type'];
    $show_street = $themeOptions['advanced_search']['street'];
    $show_city = $themeOptions['advanced_search']['city'];
    $show_county = $themeOptions['advanced_search']['county'];
    $show_zip = $themeOptions['advanced_search']['zip'];
    $show_property_type = $themeOptions['advanced_search']['property_type'];
    $show_broker = $themeOptions['advanced_search']['broker'];
    $show_keyword = $themeOptions['advanced_search']['keyword'];
    $show_size_range = $themeOptions['advanced_search']['size_range'];
    $show_price_range = $themeOptions['advanced_search']['price_range'];

    $show_sort_by = $themeOptions['show_sort_by'];
    $is_sort_by_changed = 'false';
    if(isset($_GET['sort_by'])){
        $is_sort_by_changed = 'true';
        $default_sort_by = $_GET['sort_by'];
    }
    else{
        $default_sort_by = $themeOptions['default_sort_by'];
    }

    $listings = $this->listingSort->getSortListings(array_values($listings), $default_sort_by);

    $listings = $this->listingPagination->getCurrentPageListings(array_values($listings), $listing_per_page);


    $vars = array(
        'list_view' => true,
        'listings' => $listings,
        'all_properties' => $all_properties,
        'broker_list' => $broker_list,
        'property_type_list' => $property_type_list,
        'county_list' => $county_list,
        // Most text
        'trans' => array(
            'advanced_search' => array(
                'property_search' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_PROPERTY_SEARCH'),
                'keyword' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_KEYWORD'),
                'address' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_ADDRESS'),
                'address_line_1_placeholder' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_ADDRESS_ADDRESS_LINE_1_PLACEHOLDER'),
                'address_city_placeholder' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_ADDRESS_CITY_PLACEHOLDER'),
                'address_state_zip_placeholder' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_ADDRESS_STATE_ZIP_PLACEHOLDER'),
                'listing_type' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_LISTING_TYPE'),
                'property_type' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_PROPERTY_TYPE'),
                'size' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_SIZE'),
                'size_from_placeholder' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_SIZE_FROM_PLACEHOLDER'),
                'size_to_placeholder' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_SIZE_TO_PLACEHOLDER'),
                'price' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_PRICE'),
                'price_from_placeholder' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_PRICE_FROM_PLACEHOLDER'),
                'price_to_placeholder' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_PRICE_TO_PLACEHOLDER'),
                'reset_button_label' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_RESET_BUTTON_LABEL'),
                'search_button_label' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_SEARCH_BUTTON_LABEL'),
                'advanced_search' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH'),
            ),
            'list_view' => $this->text->__('ALL_LISTINGS_PAGE_LIST_VIEW_BUTTON_LABEL'),
            'map_view' => $this->text->__('ALL_LISTINGS_PAGE_MAP_VIEW_BUTTON_LABEL'),
            'status' => $this->text->__('ALL_LISTINGS_PAGE_PROPERTY_STATUS'),
            'details' => $this->text->__('ALL_LISTINGS_PAGE_PROPERTY_DETAILS'),
        ),
        'theme_options' => $themeOptions,
        'theme_color' => $themeOptions['theme_color'],
        'theme_overlay_text_color' => $themeOptions['theme_color_overlay_text'],
        'property_url' => site_url() . '/property',
        'properties_url'             => site_url() . '/properties',
        'listing_per_page' => $listing_per_page,
        'allow_listing_per_page_change' => $allow_listing_per_page_change,
        'show_price_on_listing' => $show_price_on_listing,
        'show_sort_by' => $show_sort_by,
        'default_sort_by' => $default_sort_by,
        'is_limit_changed' => $is_limit_changed,
        'is_sort_by_changed' => $is_sort_by_changed,
        'show_filter_form' => $show_filter_form,
        'show_listing_type' => $show_listing_type,
        'show_street' => $show_street,
        'show_city' => $show_city,
        'show_county' => $show_county,
        'show_zip' => $show_zip,
        'show_property_type' => $show_property_type,
        'show_broker' => $show_broker,
        'show_keyword' => $show_keyword,
        'show_size_range' => $show_size_range,
        'show_price_range' => $show_price_range,
        'show_advanced_search_image' => $this->config->getValue('static_url') . 'images/show-advanced-search.png',
        'hide_advanced_search_image' => $this->config->getValue('static_url') . 'images/hide-advanced-search.png',
        'theme_name' => str_replace(" ", "-", get_current_theme())
        // Saved search values
        //'saved' => $savedSearchValues,
    );

    $vars = array_merge_recursive($vars, $search_vars);

    /*
     * Pagination
     */

    $currentPage = $this->listingPagination->getCurrentPage();
    $totalPages = $this->listingPagination->getTotalPages();

    $_currentPage = $currentPage;
    $nextPage = (($_cPage = $_currentPage) + 1 > $totalPages) ? $_currentPage : $_cPage + 1;
    $previousPage = (($_cPage = $_currentPage) - 1 === 0) ? $_currentPage : $_cPage - 1;

    $start = $this->listingPagination->getStart();
    $end = $this->listingPagination->getEnd();


    parse_str($_SERVER['QUERY_STRING'], $query_string);
    $rdr_str = (array_key_exists('search', $query_string))? '?search=true&current_page=' : '?current_page=';

    $vars['pagination'] = array(
        'current_page' => $currentPage,
        'total_pages' => absint($totalPages),
        'next_page' => $nextPage,
        'previous_page' => $previousPage,
        'start' => $start,
        'end' => $end,
        'listings_current_page_url' =>  site_url() . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . $rdr_str,
    );

    $vars['url'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
}

function _normaliseString($string){
    return strtolower(preg_replace('/\s+/', ' ', preg_replace('/[^a-zA-Z0-9\s]/', '', $string)));
}

function in_multiarray($elem, $array) {
    foreach ($array as $key => $value) {
        if(is_array($value)){
            if(in_multiarray($elem, $value))
                return true;
        }
        else{
            if(strripos(_normaliseString($value), _normaliseString($elem))!==false) return true;
        }
    }
    return false;
}
echo $this->twig->render('listings.twig', $vars);