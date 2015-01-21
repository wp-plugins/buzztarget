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


$property_type_disabled = !!$property_type_filter;
if ($property_type_filter) {
    $_POST['property_type'] = $property_type_filter;
    $_POST['advanced_search_submit'] = true;
}

$theme_options = get_option('buzztarget_theme_options');

if (isset($_POST['advanced_search_submit']) || isset($_GET['search']))
{
    if(isset($_GET['search']) && !isset($_POST['advanced_search_submit'])){
        $_POST = unserialize($_SESSION["wp-search"]);
    }else{
        $_SESSION["wp-search"] = serialize($_POST);
    }

    // $_POST keys
    $post_keys = array(
        'address_line_1',
        'address_line_2',
        'county',
        'address_zip_state',
        'property_type',
        'broker',
        'keyword',
        'size_from', 'size_to',
        'total_size_from', 'total_size_to',
        'total_size_by',
        'price_from', 'price_to'
    );
    // $_POST filter for key(s)
    $post_filters = 'wp_strip_all_tags';
    // Get $_POST values from key(s)
    list($address_line1,
        $address_line2,
        $county,
        $address_zip_state,
        $property_type,
        $broker,
        $keyword,
        $size_from, $size_to,
        $total_size_from, $total_size_to,
        $total_size_by,
        $price_from, $price_to) = $this->request->getPostValues($post_keys, $post_filters);

    // Fetch listing types separately
    list($listingTypes) = $this->request->getPostValues(array('listing_types'));

    if($size_from){
        $size_from = (float) $size_from;
    }
    if($size_to){
        $size_to = (float) $size_to;
    }
    if($total_size_from){
        $total_size_from = (float) $total_size_from;
    }
    if($total_size_to){
        $total_size_to = (float) $total_size_to;
    }
    if($price_from){
        $price_from = (float) $price_from;
    }
    if($price_to){
        $price_to = (float) $price_to;
    }
    // Saves form submission values
    $search_vars['saved'] = array(
        'address_line_1' => $address_line1,
        'address_line_2' => $address_line2,
        'county' => $county,
        'address_zip_state' => $address_zip_state,
        'property_type' => $property_type,
        'broker' => $broker,
        'keyword' => $keyword,
        'size_from' => $size_from,
        'size_to' => $size_to,
        'total_size_from' => $total_size_from,
        'total_size_to' => $total_size_to,
        'total_size_by' => $total_size_by,
        'price_from' => $price_from,
        'price_to' => $price_to,
        'listing_types' => $listingTypes
    );

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

    if(isset($search_criteria)) {
        $listings = Listings::where($search_criteria);
    } else {
        $listings = Listings::all();
    }

    if($listings->count() < 1) {
        $search_vars['trans']['search_error'] = $this->text->__('ADVANCED_SEARCH_NO_RESULTS_FOUND');
    }
} else {
    $listings = Listings::all();
}

if($listings->count()){
    foreach($listings as $key => $val){
        $draft_page = get_post($val['wp_page_id']);
        if($draft_page->post_status != 'publish'){
            $listings->remove($key);
        }
    }
}

$properties = clone $listings;

$is_limit_changed = 'false';
if(isset($_GET['limit_per_page'])){
    $is_limit_changed = 'true';
    $listing_per_page = $_GET['limit_per_page'];
}
else{
    $listing_per_page = $theme_options['default_listing_per_page'];
}

$allow_listing_per_page_change = $theme_options['allow_listing_per_page_change'];
$show_price_on_listing = $theme_options['show_price_on_listing'];

$show_filter_form = $theme_options['advanced_search']['status'];
$show_listing_type = $theme_options['advanced_search']['listing_type'];
$show_street = $theme_options['advanced_search']['street'];
$show_city = $theme_options['advanced_search']['city'];
$show_county = $theme_options['advanced_search']['county'];
$show_zip = $theme_options['advanced_search']['zip'];
$show_property_type = $theme_options['advanced_search']['property_type'];
$show_broker = $theme_options['advanced_search']['broker'];
$show_keyword = $theme_options['advanced_search']['keyword'];
$show_size_range = $theme_options['advanced_search']['size_range'];
$show_total_size_range = $theme_options['advanced_search']['total_size_range'];
$show_price_range = $theme_options['advanced_search']['price_range'];

if ($property_type_disabled) {
    $show_property_type = 'off';
}

$show_sort_by = $theme_options['show_sort_by'];
$is_sort_by_changed = 'false';
if(isset($_GET['sort_by'])){
    $is_sort_by_changed = 'true';
    $default_sort_by = $_GET['sort_by'];
}
else{
    $default_sort_by = $theme_options['default_sort_by'];
}

$listings->order($default_sort_by);

$listings->paginate((isset($_GET['current_page'])) ? $_GET['current_page'] : 1, $listing_per_page);

$map_options = get_option('buzztarget_map_options');

$show_map_legend = false;
// Temporarily disabled the displaying of map legend
/*
foreach($map_options['markers'] as $marker) {
    if(isset($marker) && strlen($marker) > 0)
        $show_map_legend = true;
}
*/

foreach($properties as $listing) {
    $listing->getMapIcon($map_options);
}

$filter_values = Listings::getSearchParameters();

$selected_total_size_by = $theme_options['show_size_in_acres'];
if(isset($search_criteria) && $search_criteria['total_size_by'])
    $selected_total_size_by = $search_criteria['total_size_by'];

$vars = array(
    'list_view' => true,
    'all_properties' => $properties,
    'listings' => $listings,
    'broker_list' => $filter_values['broker'],
    'property_type_list' => $filter_values['property_type'],
    'county_list' => $filter_values['county'],
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
            'total_size_from_placeholder' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_TOTAL_SIZE_FROM_PLACEHOLDER'),
            'total_size_to_placeholder' => $this->text->__('ALL_LISTINGS_PAGE_ADVANCED_SEARCH_TOTAL_SIZE_TO_PLACEHOLDER'),
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
    'map_options' => $map_options,
    'show_map_legend' => $show_map_legend,
    'theme_options' => $theme_options,
    'theme_color' => (isset($theme_options['theme_color'])) ? $theme_options['theme_color'] : NULL,
    'theme_overlay_text_color' => (isset($theme_options['theme_color_overlay_text'])) ? $theme_options['theme_color_overlay_text'] : NULL,
    'property_url' => site_url() . '/property',
    'properties_url' => site_url() . '/properties',
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
    'show_total_size_range' => $show_total_size_range,
    'show_price_range' => $show_price_range,
    'selected_total_size_by' => $selected_total_size_by,
    'show_advanced_search_image' => $this->config->getValue('static_url') . 'images/show-advanced-search.png',
    'hide_advanced_search_image' => $this->config->getValue('static_url') . 'images/hide-advanced-search.png',
    'theme_name' => str_replace(" ", "-", get_current_theme())
    // Saved search values
    //'saved' => $savedSearchValues,
);

$vars = array_merge_recursive($vars, $search_vars);

$current_page = $listings->getCurrentPage();
$total_pages = $listings->getTotalPages();

$_current_page = $current_page;
$next_page = (($_c_page = $_current_page) + 1 > $total_pages) ? $_current_page : $_c_page + 1;
$previous_page = (($_c_page = $_current_page) - 1 === 0) ? $_current_page : $_c_page - 1;

$start = $listings->getStart();
$end = $listings->getEnd();


parse_str($_SERVER['QUERY_STRING'], $query_string);
$rdr_str = (array_key_exists('search', $query_string))? '?search=true&current_page=' : '?current_page=';

$vars['pagination'] = array(
    'current_page' => $current_page,
    'total_pages' => absint($total_pages),
    'next_page' => $next_page,
    'previous_page' => $previous_page,
    'start' => $start,
    'end' => $end,
    'listings_current_page_url' =>  site_url() . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . $rdr_str,
);

$vars['url'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo $this->twig->render('listings.twig', $vars);
?>