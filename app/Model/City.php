<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * @author Sandeep Kumar
     * @function: getAllCityByStateID
     * @param: $varStateID
     * @param: $city_id
     * @desc: get all city by state id.
     */
    public static function getAllCityByStateID($varStateID, $city_id = false) {
        
        if(isset($city_id) && !empty($city_id)){
            $where_condition = array('id' => $city_id);
            
            $arrCity = Self::select('id', 'name')
                ->where($where_condition)
                ->first();
            
        } else {
            $where_condition = array('state_id' => $varStateID);
            $arrCity = Self::select('id', 'name')
                ->where($where_condition)
                ->get();
            
        }        
        return($arrCity ?: false);
    }
}
