<?php

namespace App\Model\Admin\Cms;

use Illuminate\Database\Eloquent\Model;
use DB;

class StaticPage extends Model
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
        'page_title',
        'meta_tag',
        'meta_desc',
        'content',
        'slug_url',
        'status',
    ];

    /**
     * getAllStaticPages
     * @return array arrPage
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getAllStaticPages()
    {

        $pageSize = \Config::get('constants.AdminPageSize');
        
        if (isset($status) && !empty($status)) {

            $arrData = self::select('id', 'page_title', 'meta_tag', 'meta_desc', 'content', 'slug_url', 'status')
            ->where('status', $status)
            ->orderby('id', 'desc')
            ->get();
        } else {
            $arrData = self::select('id', 'page_title', 'meta_tag', 'meta_desc', 'content', 'slug_url', 'status')
            ->orderby('id', 'desc')
            ->paginate($pageSize);
        }

        return ($arrData ? $arrData : []);
    }

    /**
     * Get Page Details By ID
     * @param integer $varPageID
     * @return array arrPageData
     * @since 0.1
     * @author Sandeep Kumar
     * 
     */
    public static function getPageByID($varPageID)
    {
        $arrPageData = DB::table('static_pages')
        ->select('static_pages.*')
        ->where('static_pages.id', '=', $varPageID)
        ->first();
        return ($arrPageData ? $arrPageData : []);
    }

    /**
     * getPageBySlug
     * @param 
     * @return 
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function getPageBySlug($varPageSlug)
    {
        /* $arrPageData = DB::table('static_pages')
          ->select('static_pages.*')
          ->where('static_pages.slug_url', '=', $varPageSlug)
          ->first(); */


          $arrPageData = self::where('static_pages.slug_url', '=', $varPageSlug)->first();

          return ($arrPageData ? $arrPageData : []);
      }

    /**
     * Update Page
     * @param integer $varPageID
     * @param array $arrPageData
     * @return mixed int or boolean
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function updatePage($varPageID, $arrPageData)
    {

        $varUpdatePage = self::find((int) $varPageID)->update($arrPageData);
        return ($varUpdatePage ?: false);
    }

    /**
     * updatePageBySlug
     * @param 
     * @param array $arrPageData
     * @return mixed int or boolean
     * @since 0.1
     * @author Sandeep Kumar
     */
    public static function updatePageBySlug($varPageID, $arrPageData)
    {
        $varUpdatePage = self::where(['slug_url' => $varPageID])->update($arrPageData);
        return ($varUpdatePage ?: false);
    }
}
