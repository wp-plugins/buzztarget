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
$featured = array();
foreach($listings as $key => $val){
    if($listings[$key]['IsFeatured'] == true){
        $featured[] = $listings[$key];
    }
}
$featuredNumberOfListingPerRow = $featuredNumberOfListingPerRow ? $featuredNumberOfListingPerRow : 3;
if ($featuredNumberOfListingPerRow < 3){
    $featuredNumberOfListingPerRow = 3;
}
if ($featuredNumberOfListingPerRow > 6){
    $featuredNumberOfListingPerRow = 6;
}

$count_of_slides = ceil(count($featured) / $featuredNumberOfListingPerRow);

for($i=0; $i<$count_of_slides; $i++){
    for( $j=$i*$featuredNumberOfListingPerRow; $j<$i*$featuredNumberOfListingPerRow+$featuredNumberOfListingPerRow; $j++){
        if(isset($featured[$j])){
            $featured_partial[$i][] = $featured[$j];
        }
    }
}
$listings = $featured_partial;

$themeOptions = get_option('buzztarget_theme_options');

$vars = array(
    'listings' => $listings,
    'featured_title' => $featuredTitle,
    'featured_class' => $featuredClass,
    'numberPerRow' => $featuredNumberOfListingPerRow,
    'count_of_slides' => $count_of_slides,
    'theme_options' => $themeOptions,
    'theme_color' => $themeOptions['theme_color'],
    'theme_overlay_text_color' => $themeOptions['theme_color_overlay_text'],
    'property_url' => site_url() . '/property',
    'properties_url' => site_url() . '/properties',
    'theme_name' => str_replace(" ", "-", get_current_theme())
);

parse_str($_SERVER['QUERY_STRING'], $query_string);
$vars['url'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


$cont = $this->twig->render('featured.twig', $vars);