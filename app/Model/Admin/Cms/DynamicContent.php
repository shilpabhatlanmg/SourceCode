<?php

namespace App\Model\Admin\Cms;

use Illuminate\Database\Eloquent\Model;

class DynamicContent extends Model
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
    protected $hidden = ['created_at', 'updated_at'];

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
        'slug',
        'title',
        'content',
        'status',
    ];

    /**
     * @author Sandeep Kumar
     * @function: getAllContent
     * @desc: get all content.
     */
    public static function getAllContent()
    {
        $pageSize = \Config::get('constants.AdminPageSize');
        $arrData = self::select('id', 'slug', 'title', 'content', 'status')
        ->orderby('slug', 'asc')
        ->paginate($pageSize);
        return ($arrData ? $arrData : []);
    }

    /**
     * @author Sandeep Kumar
     * @function: getContentBySlug
     * @param $varPageSlug
     * @desc: get all content by slug.
     */
    public static function getContentBySlug($varPageSlug)
    {
      $arrPageData = self::where('dynamic_contents.slug', '=', $varPageSlug)->first();

      return ($arrPageData ? $arrPageData : []);
  }

    /**
     * @author Sandeep Kumar
     * @function: updatePageBySlug
     * @param $varPageID
     * @param $arrPageData
     * @desc: update page by slug.
     */
    public static function updatePageBySlug($varPageID, $arrPageData)
    {
        $varUpdatePage = self::where(['slug' => $varPageID])->update($arrPageData);
        return ($varUpdatePage ?: false);
    }
}
