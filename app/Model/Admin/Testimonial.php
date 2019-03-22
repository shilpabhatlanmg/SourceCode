<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Testimonial extends Model
{
    use SoftDeletes;
    use Sortable;

    /**
     * Custom primary key is set for the table
     * 
     * @var integer
     */
    protected $primaryKey = 'id';
    public $sortable = ['id', 'feedback_date', 'author_rating', 'author_email', 'status', 'created_at'];

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
    protected $fillable = [
        'author_image',
        'content',
        'author_rating',
        'occupation',
        'author_email',
        'feedback_date',
        'status',
    ];

    /**
     * getAllRecord
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getAllRecord()
    {
        $pageSize = \Config::get('constants.AdminPageSize'); 
        $arrData = self::select('id', 'author_rating', 'author_email', 'occupation', 'content', 'author_image', 'feedback_date', 'status');
        
        $arrData = $arrData->sortable(['id' => 'desc'])->paginate($pageSize);
        
        

        return ($arrData ? $arrData : []);
    }


    /**
     * storeOrUpdateData
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function storeOrUpdateData($input, $user_id = false) {

        $user = self::updateOrCreate(['id' => (int) $user_id], $input);
        return $user;
    }
    

    /**
     * getRecordByID
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getRecordByID($varID)
    {
        $arrRecord = Self::select('*')
        ->select('*')
        ->where("id", "=", $varID)
        ->first();
        return ($arrRecord ? $arrRecord : []);
    }

    /**
     * deleteRecord
     * @param 
     * @return array
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function deleteRecord($id)
    {
        $varDeleteID = Self::where('id', '=', $id)->delete();
        return ($varDeleteID ?: false);
    }
}
