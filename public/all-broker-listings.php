<?php
namespace BuzzTargetLive;

if (!$listings = get_option('repl_listings'))
    return;

if(count($listings)){
    foreach($listings as $key => $val){
        $draft_page = get_post($listings[$key]['wp_page_id']);
        if($draft_page->post_status != 'publish'){
            unset($listings[$key]);
        }
    }
}
$broker_listings = array();
foreach($listings as $key => $val){
    if(isset($listings[$key]['ListingAgents'])){
        foreach($listings[$key]['ListingAgents'] as $broker_key => $broker_val){
            if($listings[$key]['ListingAgents'][$broker_key]['Email'] == $brokerEmail){
                $broker_listings[] = $listings[$key];
            }
        }
    }
}
if ($brokerListingsNumberOfListingPerPage < 9){
    $brokerListingsNumberOfListingPerPage = 9;
}
$count_of_pages = ceil(count($broker_listings) / $brokerListingsNumberOfListingPerPage);

$listings = $this->listingPagination->getCurrentPageListings(array_values($broker_listings), $brokerListingsNumberOfListingPerPage);

$themeOptions = get_option('buzztarget_theme_options');

$brokerListingsNumberOfListingPerRow = $brokerListingsNumberOfListingPerRow ? $brokerListingsNumberOfListingPerRow : 3;
if ($brokerListingsNumberOfListingPerRow < 3){
    $brokerListingsNumberOfListingPerRow = 3;
}
if ($brokerListingsNumberOfListingPerRow > 6){
    $brokerListingsNumberOfListingPerRow = 6;
}
if ($brokerListingsNumberOfListingPerRow > 6){
    $brokerListingsNumberOfListingPerRow = 6;
}

$vars = array(
    'listings' => $listings,
    'broker_listings_title' => $brokerListingsTitle,
    'broker_listings_class' => $brokerListingsClass,
    'numberPerPage' => $brokerListingsNumberOfListingPerPage,
    'numberPerRow' => $brokerListingsNumberOfListingPerRow,
    'count_of_slides' => $count_of_pages,
    'theme_options' => $themeOptions,
    'theme_color' => $themeOptions['theme_color'],
    'theme_overlay_text_color' => $themeOptions['theme_color_overlay_text'],
    'property_url' => site_url() . '/property',
    'properties_url' => site_url() . '/properties',
    'theme_name' => str_replace(" ", "-", get_current_theme())
);

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

$cont = $this->twig->render('broker-listings.twig', $vars);