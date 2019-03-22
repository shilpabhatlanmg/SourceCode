<?php

namespace App\Model\Admin\EmailTemplate;

use Illuminate\Database\Eloquent\Model;

class EmailTemplateAttachment extends Model
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
    protected $fillable = [];

    /**
     * @author Sandeep Kumar
     * @function: storeData
     * @param $email_templat_id
     * @param $inputFiles
     * @desc: Store record into database.
     */
    protected $guarded = [];

    public static function storeData($email_templat_id, $inputFiles)
    {
        $data = [];
        if (!empty($inputFiles)) {
            foreach ($inputFiles as $file) {

                $filename = $file->getClientOriginalName();
                $file->storeAs('public/email_template_attachments', $filename);

                $input = [];
                $input['email_template_id'] = $email_templat_id;
                $input['name'] = $filename;
                $input_batch[] = $input;
            }
            // For Bulk Insertion
            $data = self::insert($input_batch);
        }
        return $data;
    }

    /**
     * @author Sandeep Kumar
     * @function: deleteEmailAttachment
     * @param $attachment_id
     * @desc: delete email attachement.
     */
    public static function deleteEmailAttachment($attachment_id)
    {
        $data = self::destroy($attachment_id);
        return $data;
    }
}
