<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * Custom primary key is set for the table
     * 
     * @var integer
     */
    protected $primaryKey = 'id';

    /**
     * Maintain created_at and updated_at automatically
     * 
     * @var boolean
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name',
    	'code',
    ];

    /**
     * @author Sandeep Kumar
     * @function: getAllCountry
     * @desc: get all country.
     */
    public static function getAllCountry()
    {
    	$arrCountry = Self::select('id', 'name', 'code')
    	->orderby('name')
    	->get();
    	return ($arrCountry ? $arrCountry : []);
    }
}
