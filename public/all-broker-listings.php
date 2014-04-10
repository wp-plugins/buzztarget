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
$broker_listings = [];
foreach($listings as $key => $val){
    if(isset($listings[$key]['ListingAgents'])){
        foreach($listings[$key]['ListingAgents'] as $broker_key => $broker_val){
            if($listings[$key]['ListingAgents'][$broker_key]['Email'] == $brokerEmail){
                $broker_listings[] = $listings[$key];
            }
        }
    }
}

$count_of_pages = ceil(count($broker_listings) / $brokerListingsNumberOfListingPerRow);

for($i=0; $i<$count_of_pages; $i++){
    for( $j=$i*$brokerListingsNumberOfListingPerRow; $j<$i*$brokerListingsNumberOfListingPerRow+$brokerListingsNumberOfListingPerRow; $j++){
        if(isset($broker_listings[$j])){
            $broker_listings_partial[$i][] = $broker_listings[$j];
        }
    }
}
$listings = $broker_listings_partial;

$themeOptions = get_option('buzztarget_theme_options');

$vars = array(
    'listings' => $listings,
    'broker_listings_title' => $brokerListingsTitle,
    'broker_listings_class' => $brokerListingsClass,
    'numberPerRow' => $brokerListingsNumberOfListingPerRow,
    'count_of_slides' => $count_of_pages,
    'theme_options' => $themeOptions,
    'theme_color' => $themeOptions['theme_color'],
    'theme_overlay_text_color' => $themeOptions['theme_color_overlay_text'],
    'property_url' => site_url() . '/property',
    'properties_url' => site_url() . '/properties',
);

parse_str($_SERVER['QUERY_STRING'], $query_string);
$vars['url'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


echo $this->twig->render('broker-listings.twig', $vars);