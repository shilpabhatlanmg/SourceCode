<?php 

namespace App\Traits;
use Response;

trait checkUserNotification
{
    /**
     *public method api response
     *Author: Pradeep Kumar
     *Creation Date: August 02, 2017
     *@param $api 
     *@return json arrary response of the current apis
     */
    public function returnDataApi($successMsg,$message,$data){
       //~ if(!empty($data)){
		   //~ $data = ['data' => $data];
	   //~ }
       $array = array('success'=>$successMsg,'message'=>$message,'data'=>$data);        
       return response()->json($array);
    }
}
?>
