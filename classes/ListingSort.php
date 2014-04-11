<?php

namespace BuzzTargetLive;

class ListingSort
{

    public function getSortListings(array $listings, $sort_by_field)
    {
        $list = false;
        switch ($sort_by_field) {
            case "price_a_z":
                $field = 'PropertyPrice';
                $reverse = false;
                break;
            case "price_z_a":
                $field = 'PropertyPrice';
                $reverse = true;
                break;
            case "date_a_z":
                $field = 'Updated';
                $reverse = false;
                break;
            case "date_z_a":
                $field = 'Updated';
                $reverse = true;
                break;
            case "size_a_z":
                $field = 'TotalLotSize';
                $reverse = false;
                break;
            case "size_z_a":
                $field = 'TotalLotSize';
                $reverse = true;
                break;
            case "broker_a_z":
                $field = 'ListingAgents';
                $reverse = false;
                $list = true;
                break;
            case "broker_z_a":
                $field = 'ListingAgents';
                $reverse = true;
                $list = true;
                break;
            case "county_a_z":
                $field = 'County';
                $reverse = false;
                break;
            case "county_z_a":
                $field = 'County';
                $reverse = true;
                break;
        }

        $arr_has_no_property = [];
        $arr_has_property = [];
        for ($i=0; $i<count($listings); $i++) {
            if(isset($listings[$i][$field])){
                $arr_has_property[] = $listings[$i];
            }
            else{
                $arr_has_no_property[] = $listings[$i];
            }
        }
        $size =count($arr_has_property);
        for ($i=0; $i<$size; $i++) {
            for ($j=0; $j<$size-1-$i; $j++) {
                if ($list == true){
                    if ($arr_has_property[$j+1][$field][0] < $arr_has_property[$j][$field][0]) {
                        $this->swap($arr_has_property, $j, $j+1);
                    }
                }
                else{
                    if ($arr_has_property[$j+1][$field] < $arr_has_property[$j][$field]) {
                        $this->swap($arr_has_property, $j, $j+1);
                    }
                }
            }
        }

        if($reverse == true){
            $arr_has_property = array_reverse($arr_has_property);
        }
        $listings = array_merge($arr_has_property, $arr_has_no_property);

        return $listings;
    }

    public function swap(&$arr, $a, $b) {
        $tmp = $arr[$a];
        $arr[$a] = $arr[$b];
        $arr[$b] = $tmp;
    }
}