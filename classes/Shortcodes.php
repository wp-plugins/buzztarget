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
    public function showAllListings()
    {
        global $shortcode;
        $shortcode = 'all_listing';
        require_once $this->config->getValue('public_path') . 'all-listings.php';
    }

    public function showForLeaseListings()
    {
        global $shortcode;
        $shortcode = 'lease_listing';
        require_once $this->config->getValue('public_path') . 'all-listings.php';
    }

    public function showForSaleListings()
    {
        global $shortcode;
        $shortcode = 'sale_listing';
        require_once $this->config->getValue('public_path') . 'all-listings.php';
    }

    /**
     * Sorts the listings accordingly when a search has been requested.
     *
     * @access protected
     *
     * @since 1.1.0
     *
     * @param array $repl_listings The listings to sort.
     *
     * @return string Sort type.
     */
    protected function sortListings(array &$repl_listings)
    {
        // If the user is searching/sorting, default is 'abcdef' = Alphabetically (A-Z)
        $sort = (isset($_GET['sort'])) ? wp_strip_all_tags($_GET['sort']) : 'abcdef';
        switch ($sort)
        {
            // Sort alphabetically descending
            case 'abcdesc':
                usort($repl_listings, function ($a, $b) {
                    return strcmp($a['Property']['PropertyName'], $b['Property']['PropertyName']);
                });
            break;
            // Sort alphabetically ascending
            case 'abcasc':
                usort($repl_listings, function ($a, $b) {
                    if ($a['Property']['PropertyName'] == $b['Property']['PropertyName'])
                        return 0;
                    return ($a['Property']['PropertyName'] < $b['Property']['PropertyName']) ? 1 : -1;
                });
            break;
            // Sort by price descending
            case 'pricedesc':
                usort($repl_listings, function ($a, $b) {
                    if ($a['PropertyPrice'] == $b['PropertyPrice'])
                        return 0;
                    return ($a['PropertyPrice'] < $b['PropertyPrice']) ? 1 : -1;
                });
            break;
            // Sort by price ascending
            case 'priceasc':
                usort($repl_listings, function ($a, $b) {
                    if ($a['PropertyPrice'] == $b['PropertyPrice'])
                        return 0;
                    return ($a['PropertyPrice'] < $b['PropertyPrice']) ? -1 : 1;
                });
            break;
            // Default - sort by keys.
            default:
                ksort($repl_listings);
            break;
        }
        return $sort;
    }

    /**
     * Filters out all listing types except the one specified in $type.
     *
     * @access protected
     *
     * @since 1.1.0
     *
     * @param array  $listings Array of listing(s).
     * @param string $type     The type you want to keep, rest will be removed.
     *
     * @return array Array of listings containing only the listing type
     *               you specified.
     */
    protected function filterListingTypesExceptType(array $listings, $type)
    {
        $typeListings = array();

        foreach ($listings as $listing)
        {
            if (isset($listing['ListingType']) && $listing['ListingType'] === $type)
            {
                $typeListings[] = $listing;
            }
        }
        unset($listing);

        return $typeListings;
    }

    /**
     * Displays for sale listings.
     *
     * Called when the "for-sale" shortcode is encountered on a page.
     *
     * @access public
     *
     * @since 1.0.0
     */
    public function displayForSaleListings()
    {
        global $post;

        if (!$repl_listings = get_option('repl_listings'))
            return;

        // Filter out any properties that are not for sale.
        $repl_listings = $this->filterListingTypesExceptType($repl_listings, 'ForSale');

        // Pagination - fetch current page's listings.
        $repl_listings = $this->listingPagination->getCurrentPageListings($repl_listings);

        // No for sale listings
        if (!$repl_listings)
        {
            $variables = array(
                'listings' => false,
                'no_for_sale_listings' => $this->text->__('PUBLIC_LISTINGS_NO_FOR_SALE_LISTINGS')
            );
            echo $this->twig->render('for-sale-listings.twig', $variables);
            return;
        }

        /*
         * Sort the listings.
         */
        $sort = $this->sortListings($repl_listings);


        // Get property name the user searched for, if any
        $propertyName = (isset($_GET['propertyName'])) ? wp_strip_all_tags($_GET['propertyName']) : '';
        // Search for the property in our cached properties, we only want to display _that_ property on the listings.
        if ($propertyName)
        {
            $propertyName = str_replace('+', ' ', $propertyName);
            foreach ($repl_listings as $listing)
            {
                if ($listing['Property']['PropertyName'])
                {
                    if (strtolower($listing['Property']['PropertyName']) === strtolower($propertyName))
                    {
                        $repl_listings = array($listing);
                        break;
                    }
                }
            }
            unset($listing);
        }
        $options = get_option('repl_options');

        $current_page = $this->listingPagination->getCurrentPage();
        $total_pages = $this->listingPagination->getTotalPages();
        $start = $this->listingPagination->getStart();
        $end = $this->listingPagination->getEnd();

        // Variables that will be pushed to our template
        $variables = array(
            // Searching listings
            'search_listings_heading'               => $this->text->__('PUBLIC_LISTINGS_SEARCH_LISTINGS_HEADING'),
            'search_listings_property_name_ex'      => $this->text->__('PUBLIC_LISTINGS_SEARCH_LISTINGS_PROPERTY_NAME_EXAMPLE'),
            'search_listings_sort_abc_desc'         => $this->text->__('PUBLIC_LISTINGS_SEARCH_LISTINGS_SORT_ABC_DESC'),
            'search_listings_sort_abc_asc'          => $this->text->__('PUBLIC_LISTINGS_SEARCH_LISTINGS_SORT_ABC_ASC'),
            'search_listings_sort_price_desc'       => $this->text->__('PUBLIC_LISTINGS_SEARCH_LISTINGS_SORT_PRICE_DESC'),
            'search_listings_sort_price_asc'        => $this->text->__('PUBLIC_LISTINGS_SEARCH_LISTINGS_SORT_PRICE_ASC'),
            'search_listings_submit_button_text'    => $this->text->__('PUBLIC_LISTINGS_SEARCH_LISTINGS_SUBMIT_BUTTON_TEXT'),
            'selected'                              => $sort,
            'property_name'                         => $propertyName,

            // Listings
            'listings'                              => $repl_listings,
            'listings_count'                        => count($repl_listings),
            'for_sale_listings_title_color'         => (isset($options['for_sale_title_color'])) ? $options['for_sale_title_color'] : '#4089ac',
            'property_url'                          => site_url() . '/property',
            'properties_url'                        => site_url() . '/properties',
            'listings_image'                        => $this->config->getValue('static_url') . 'images/listings',
            'read_more'                             => $this->text->__('PUBLIC_LISTINGS_READ_MORE'),
            'price'                                 => $this->text->__('PUBLIC_LISTINGS_PRICE'),
            'cap_rate'                              => $this->text->__('PUBLIC_LISTINGS_CAP_RATE'),
            'status'                                => $this->text->__('PUBLIC_LISTINGS_STATUS'),
            'details'                               => $this->text->__('PUBLIC_LISTINGS_DETAILS'),
            'pagination' => array(
                'url' => site_url() . '/for-sale/?current_page=',
                'previous_page' => ($current_page === 1) ? 1 : absint($current_page - 1),
                'next_page' => ($total_pages === 1) ? 1 : absint($current_page + 1),
                'total_pages' => $total_pages,
                'current_page' => $current_page,
                'start' => $start,
                'end' => $end
            )
        );
        echo $this->twig->render('for-sale-listings.twig', $variables);
    }

    /**
     * Displays for lease listings.
     *
     * Called when the 'for-lease' shortcode is encountered on a page.
     *
     * @access public
     */
    public function displayForLeaseListings()
    {
        global $post;
        // Attempt to fetch cached listings
        if (!$repl_listings = get_option('repl_listings'))
            return;

        // Filter out any properties that are not for lease.
        $repl_listings = $this->filterListingTypesExceptType($repl_listings, 'ForLease');

        // Pagination - fetch current page's for-lease listings.
        $repl_listings = $this->listingPagination->getCurrentPageListings($repl_listings);

        // No for sale listings
        if (!$repl_listings)
        {
            $variables = array(
                'listings' => false,
                'no_for_lease_listings' => $this->text->__('PUBLIC_LISTINGS_NO_FOR_LEASE_LISTINGS')
            );
            echo $this->twig->render('for-lease-listings.twig', $variables);
            return;
        }

        /*
         * Sort the listings.
         */
        $sort = $this->sortListings($repl_listings);

        // Get property name the user searched for, if any
        $propertyName = (isset($_GET['propertyName'])) ? wp_strip_all_tags($_GET['propertyName']) : '';

        // Search for the property in our cached properties, 
        // we only want to display _that_ property on the listings.
        if ($propertyName)
        {
            $propertyName = str_replace('+', ' ', $propertyName);
            foreach ($repl_listings as $listing)
            {
                if ($listing['Property']['PropertyName'])
                {
                    if (strtolower($listing['Property']['PropertyName']) === strtolower($propertyName))
                    {
                        $repl_listings = array($listing);
                        break;
                    }
                }
            }
            unset($listing);
        }

        $options = get_option('repl_options');

        $current_page = $this->listingPagination->getCurrentPage();
        $total_pages = $this->listingPagination->getTotalPages();
        $start = $this->listingPagination->getStart();
        $end = $this->listingPagination->getEnd();
        
        // Variables that will be pushed to our template
        $variables = array(
            // Searching listings
            'search_listings_heading'               => $this->text->__('PUBLIC_LISTINGS_SEARCH_LISTINGS_HEADING'),
            'search_listings_property_name_ex'      => $this->text->__('PUBLIC_LISTINGS_SEARCH_LISTINGS_PROPERTY_NAME_EXAMPLE'),
            'search_listings_sort_abc_desc'         => $this->text->__('PUBLIC_LISTINGS_SEARCH_LISTINGS_SORT_ABC_DESC'),
            'search_listings_sort_abc_asc'          => $this->text->__('PUBLIC_LISTINGS_SEARCH_LISTINGS_SORT_ABC_ASC'),
            'search_listings_sort_price_desc'       => $this->text->__('PUBLIC_LISTINGS_SEARCH_LISTINGS_SORT_PRICE_DESC'),
            'search_listings_sort_price_asc'        => $this->text->__('PUBLIC_LISTINGS_SEARCH_LISTINGS_SORT_PRICE_ASC'),
            'search_listings_submit_button_text'    => $this->text->__('PUBLIC_LISTINGS_SEARCH_LISTINGS_SUBMIT_BUTTON_TEXT'),
            'selected'                              => $sort,
            'property_name'                         => $propertyName,

            // Listings
            'listings'                              => $repl_listings,
            'listings_count'                        => count($repl_listings),
            'for_lease_listings_title_color'        => (isset($options['for_lease_title_color'])) ? $options['for_lease_title_color'] : '#4089ac',
            'property_url'                          => site_url() . '/property',
            'properties_url'                        => site_url() . '/properties',
            'listings_image'                        => $this->config->getValue('static_url') . 'images/listings',
            'read_more'                             => $this->text->__('PUBLIC_LISTINGS_READ_MORE'),
            'rental_rate_label'                     => $this->text->__('PUBLIC_LISTINGS_LEASE_RENTAL_RATE'),
            'available_label'                       => $this->text->__('PUBLIC_LISTINGS_LEASE_AVAILABLE'),
            'status'                                => $this->text->__('PUBLIC_LISTINGS_STATUS'),
            'details'                               => $this->text->__('PUBLIC_LISTINGS_DETAILS'),
            'pagination' => array(
                'url' => site_url() . '/for-lease/?current_page=',
                'previous_page' => ($current_page === 1) ? 1 : absint($current_page - 1),
                'next_page' => ($total_pages === 1) ? 1 : absint($current_page + 1),
                'total_pages' => $total_pages,
                'current_page' => $current_page,
                'start' => $start,
                'end' => $end
            )
        );
        echo $this->twig->render('for-lease-listings.twig', $variables);
    }
}