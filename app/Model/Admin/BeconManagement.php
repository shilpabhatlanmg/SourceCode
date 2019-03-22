<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class BeconManagement extends Model {

    //use Sortable;

    /**
     * set the table name which is belong this model
     *
     * @var array
     */
    protected $table = 'beacon_management';

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
    
    //public $sortable = ['id', 'organizations_name', 'contact_number', 'location_name', 'premise_name'];

    
    public function getOrganizationName() {
        return $this->belongsTo('App\Admin', 'organization_id');
    }

    public function getPremiseName() {
        return $this->belongsTo('App\Model\Admin\Premise', 'premise_id');
    }

    public function getLocationName() {
        return $this->belongsTo('App\Model\Admin\Location', 'location_id');
    }

     

}
