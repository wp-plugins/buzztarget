<?php

namespace BuzzTargetLive;

class ListingPagination
{
    protected $currentPage;
    protected $totalPages;
    protected $start;
    protected $end;

    /**
     * Returns listings that are within a limit i.e. the current page.
     *
     * @access protected
     *
     * @since 1.1.0
     *
     * @param array $listings An array containing the listing(s).
     *
     * @return array The current page's listing(s).
     */
    public function getCurrentPageListings(array $listings, $limit = 9)
    {

        $listingsCount = count($listings);

        $totalPages = ($listingsCount > $limit) ? $listingsCount / $limit : 1;

        if (is_float($totalPages))
            $totalPages = ceil($totalPages);

        $currentPage = (isset($_GET['current_page'])) ? absint($_GET['current_page']) : 1;

        $end = $limit * $currentPage;

        $start = absint($end - $limit);

        $currentPageListings = array();

        for ($i = $start; $i < $end; $i++)
        {
            if (isset($listings[$i]))
            {
                $currentPageListings[] = $listings[$i];
            }
        }

        $this->currentPage  = $currentPage;
        $this->totalPages   = $totalPages;
        $this->start        = $start;
        $this->end          = $end;

        return $currentPageListings;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getTotalPages()
    {
        return $this->totalPages;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function getEnd()
    {
        return $this->end;
    }
}