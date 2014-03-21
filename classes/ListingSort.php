<?php

namespace BuzzTargetLive;

class ListingSort
{

    public function getSortListings(array $listings, $sort_by_field, $asc=SORT_ASC)
    {
//        $show_list_sort=array();
//        for ($i=0; $i<count($listings); $i++){
//            $show_list_sort[] = $listings[$i];
//            sort($show_list_sort[$key]);
//        }
//        return $show_list_sort;
//        $listingsCount = count($listings);
//        $key = $sort_by_field;
//        $sort_flags = array(SORT_ASC, SORT_DESC);
//        $cmp = function(array $a, array $b) use ($key, $asc, $sort_flags) {
//            if(!is_array($key)) { //just one key and sort direction
//
//                if($a[$key] == $b[$key]) return 0;
//                return ($asc==SORT_ASC xor $a[$key] < $b[$key]) ? 1 : -1;
//            } else { //using multiple keys for sort and sub-sort
//                foreach($key as $sub_key => $sub_asc) {
//                    //array can come as 'sort_key'=>SORT_ASC|SORT_DESC or just 'sort_key', so need to detect which
//                    if(!in_array($sub_asc, $sort_flags)) { $sub_key = $sub_asc; $sub_asc = $asc; }
//
//                    if($a[$sub_key] == $b[$sub_key]) continue;
//                    return ($sub_asc==SORT_ASC xor $a[$sub_key] < $b[$sub_key]) ? 1 : -1;
//                }
//                return 0;
//            }
//        };
//        $result = usort($listings, $cmp);
        return $listings;
    }
}