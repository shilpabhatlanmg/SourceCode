<?php

namespace App\Traits;

use Response;

trait ApiResponseTrait
{
    /**
     *public method api response
     *Author: Pradeep Kumar
     *Creation Date: August 02, 2017
     *@param $api
     *@return json arrary response of the current apis
     */
    public function returnDataApi($successMsg, $message, $data)
    {
        //~ if(!empty($data)){
        //~ $data = ['data' => $data];
        //~ }
        $array = array('success'=>$successMsg,'message'=>$message,'data'=>$data);
        return response()->json($array);
    }

    public function returnDataApifailure($successMsg, $message, $data)
    {
        //~ if(!empty($data)){
        //~ $data = ['data' => $data];
        //~ }
        $array = array('success'=>$successMsg,'data'=>$data);
        return response()->json($array);
    }


    public function changeNumber($chng)
    {
        $conactNumber = '('.$chng;
        $conactNumber = substr_replace($conactNumber, ')', 4, 0);
        $conactNumber = substr_replace($conactNumber, ' ', 5, 0);
        $conactNumber = substr_replace($conactNumber, '-', 9, 0);
        return $conactNumber;
    }


    public function removeBraces($conactNumber)
    {
        return str_replace('+', '', str_replace('-', '', str_replace(' ', '', str_replace(')', '', str_replace('(', '', $conactNumber)))));
    }

    public function getImagePath($image)
    {
        return (trim($image) != '') && file_exists(public_path()."/storage/admin_assets/images/profile_image/".$image)?env('APP_URL')."public/storage/admin_assets/images/profile_image/".$image:'';
    }

    public function getOtp(){
      //return 1111;
      return mt_rand(1000, 9999);
    }
}
