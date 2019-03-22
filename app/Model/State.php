<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class State extends Model
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
        'country_id'
    ];

    /**
     * @author Sandeep Kumar
     * @function: getAllState
     * @desc: get state by id.
     */
    public static function getAllState($id = false)
    {
        if(isset($id) && !empty($id)){
            $arrState = Self::select('id', 'name')
            ->where('id', $id)
            ->orderby('name')
            ->first();
            
        }else{
            $arrState = Self::select('id', 'name')
            ->select('id', 'name')
            ->where('country_id', 230)
            ->orderby('name')
            ->get();
        }

        
        return ($arrState ? $arrState : []);
    }
}
