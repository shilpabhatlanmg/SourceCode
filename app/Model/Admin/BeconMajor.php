<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class BeconMajor extends Model {

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
     * @var array
     */
    protected $fillable = [];

    /**
     * getOrganizationName
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public function getOrganizationName() {
        return $this->hasOne('App\Admin', 'becon_major_id');
    }

}
