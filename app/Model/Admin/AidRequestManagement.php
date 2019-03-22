<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class AidRequestManagement extends Model
{
	use Sortable;

    /**
     * set the table name which is belong this model
     *
     * @var array
     */
    protected $table = 'aid_request_management';

    /**
     * Custom primary key is set for the table
     * 
     * @var integer
     */
    protected $primaryKey = 'organization_id';

    /**
     * Maintain created_at and updated_at automatically
     * 
     * @var boolean
     */
    public $timestamps = true;
    public $sortable = ['premise_name', 'location_name', 'contact_number', 'incident_type_id', 'organization_id'];

    /**
     * getAidRequest
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getAidRequest()
    {

        $pageSize = \Config::get('constants.AdminPageSize');
        $organisationId = !empty(request()->organization_id) ? \Crypt::decryptString(request()->organization_id) : '';
        $incidentTypeId = !empty(request()->incident_type_id) ? \Crypt::decryptString(request()->incident_type_id) : '';
        $strSearchTerm = !empty(request()->search) ? request()->search : '';

        $query = Self::select('organization_id', 'org_name', 'premise_name','location_name', 'incident_type', 'contact_number', 'created_at');




        if(!empty($organisationId)){
            
                $query->where('organization_id', $organisationId);
                
            

        }


        if(!empty($incidentTypeId)){

            $query->where('incident_type_id', $incidentTypeId);

        }

        if(!empty(request()->from_date) && !empty(request()->to_date)){
            $query->whereBetween($convert, [$from, $to]);

        }

        $arr_record = $query->sortable()->paginate($pageSize);

        return ($arr_record ? $arr_record : []);
            

        }
    
}
