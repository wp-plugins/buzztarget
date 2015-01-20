<?php

namespace BuzzTargetLive;

use Twig_Environment;

class Shortcodes
{
    protected $config;
    protected $twig;
    protected $text;
    protected $listingPagination;
    protected $listingSort;

    public function __construct(Config $config, Twig_Environment $twig, Text $text, ListingPagination $listingPagination, Request $request, ListingSort $listingSort)
    {
        $this->config = $config;
        $this->twig = $twig;
        $this->text = $text;
        $this->listingPagination = $listingPagination;
        $this->listingSort = $listingSort;
        $this->request = $request;

        add_shortcode('for-sale', array($this, 'showForSaleListings'));
        add_shortcode('for-lease', array($this, 'showForLeaseListings'));
        add_shortcode('all-listings', array($this, 'showAllListings'));
        add_shortcode('listings-map', array($this, 'showListingsMap'));
        add_shortcode('all-featured', array($this, 'showAllFeatured'));
        add_shortcode('all-broker-listings', array($this, 'showAllBrokerListings'));

    }

    /**
     * Displays all listings.
     *
     * Called when the all-listings shortcode is encountered on a page.
     *
     * @access public
     *
     * @since 1.0.0
     */
    public function showAllListings($atts)
    {
        global $shortcode;
        $shortcode = 'all_listing';
        $property_type_filter = (isset($atts['type'])) ? ucfirst($atts['type']) : false;
        require_once $this->config->getValue('public_path') . 'all-listings.php';
    }

    public function showListingsMap($atts)
    {
        global $shortcode;
        $shortcode = 'listings_map';
        $property_type_filter = (isset($atts['type'])) ? ucfirst($atts['type']) : false;
        $widgetWidth = (isset($atts['width'])) ? ucfirst($atts['width']) : 400;
        $widgetHeight = (isset($atts['height'])) ? ucfirst($atts['height']) : 400;
        require_once $this->config->getValue('public_path') . 'listings-map.php';
    }

    public function showAllFeatured($atts)
    {
        global $shortcode;
        $shortcode = 'all_featured';
        $featuredTitle = $atts['title'];
        $featuredClass = $atts['class'];
        $featuredNumberOfListingPerRow = (int)$atts['numberoflistingperrow'];

        require_once $this->config->getValue('public_path') . 'all-featured.php';
        return $cont;

    }

    public function showAllBrokerListings($atts)
    {
        global $shortcode;
        $shortcode = 'all_broker_listings';
        $brokerListingsTitle = $atts['title'];
        $brokerListingsClass = $atts['class'];
        $brokerListingsNumberOfListingPerPage = (int)$atts['numberoflistingperpage'];
        $brokerListingsNumberOfListingPerRow = (int)$atts['numberoflistingperrow'];
        $brokerEmail = $atts['brokeremail'];

        require_once $this->config->getValue('public_path') . 'all-broker-listings.php';
        return $cont;
    }

    public function showForLeaseListings($atts)
    {
        global $shortcode;
        $shortcode = 'lease_listing';
        $property_type_filter = ucfirst($atts['type']);
        require_once $this->config->getValue('public_path') . 'all-listings.php';
    }

    public function showForSaleListings($atts)
    {
        global $shortcode;
        $shortcode = 'sale_listing';
        $property_type_filter = ucfirst($atts['type']);
        require_once $this->config->getValue('public_path') . 'all-listings.php';
    }
}