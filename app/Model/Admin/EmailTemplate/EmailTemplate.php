<?php

namespace App\Model\Admin\EmailTemplate;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{

    use \Illuminate\Database\Eloquent\SoftDeletes;

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
    protected $fillable = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    
    /**
     * @author Sandeep Kumar
     * @function: getEmailTemplateById
     * @param $id
     * @desc: get email by template id.
     */
    public static function getEmailTemplateById($id)
    {
        $data = self::where(['id' => $id])->orWhere(['template_code' => $id])->with(['relEmailTemplateAttachment'])->first();
        return $data;
    }


    /**
     * @author Sandeep Kumar
     * @function: storeData
     * @param $input
     * @param $id
     * @desc: store record into database.
     */
    public static function storeData($input, $id)
    {
        try {
            $data = self::updateOrCreate(['id' => (int) $id], $input);
        } catch (\Exception $e) {
            $data['errors'] = $e->getMessage();
        }
        return $data;
    }

    /**
     * @author Sandeep Kumar
     * @function: getAllEmailTemplates
     * @desc: get all email templates.
     */
    public static function getAllEmailTemplates()
    {
        try {
            \DB::statement(\DB::raw('set @rownum=0'));
            $data = self::select(\DB::raw('@rownum  := @rownum  + 1 AS rownum'), "tbl_email_template.*")->with(['relEmailTemplateAttachment'])->orderBy('id', 'desc')->get();
        } catch (\Exception $e) {
            $data['errors'] = $e->getMessage();
        }
        return $data;
    }

    /**
     * @author Sandeep Kumar
     * @function: deleteData
     * @param $data_ids
     * @desc: delete record from database.
     */
    public static function deleteData($data_ids)
    {
        try {
            $data = self::destroy($data_ids);
        } catch (\Exception $e) {
            $data = $e->getMessage();
        }
        return $data;
    }

    /**
     * @author Sandeep Kumar
     * @function: relEmailTemplateAttachment
     * @desc: get attachment email.
     */
    public function relEmailTemplateAttachment()
    {
        return $this->hasMany(EmailTemplateAttachment::class, 'email_template_id');
    }

    /**
     * @author Sandeep Kumar
     * @function: getEmailTemplatesByIds
     * @param $ids
     * @desc: get email by template id.
     */
    public static function getEmailTemplatesByIds($ids)
    {
        $data = self::whereIn(['id' => $ids])->with(['relEmailTemplateAttachment'])->get();
        return $data;
    }
}
