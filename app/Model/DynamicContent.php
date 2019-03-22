<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DynamicContent extends Model
{
    
    
	/**
     * @author Sandeep Kumar
     * @function: getContentBySlug
     *@param $slug
     * @desc: get content by slug.
     */
    public static function getContentBySlug($slug) {
        
        
            $where_condition = array('slug' => $slug);
            $arrContent = Self::select('*')
                ->where($where_condition)
                ->first();
            
               
        return($arrContent ?: false);
    }
}
