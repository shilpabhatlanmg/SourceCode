<?php
namespace App\Components;
// Server file
class PushNotifications {
	// (Android)API access key from Google API's Console.
	private static $API_ACCESS_KEY = 'AIzaSyCJjqfNLULWVsbgl6r548NayEbtk38LzYA';
	// (iOS) Private key's passphrase.
	//private static $passphrase = 'joashp';
	private static $passphrase = 'Welcome123';
	// (Windows Phone 8) The name of our push channel.
    private static $channelName = "joashp";
	// Change the above three vriables as per your app.
    // Sends Push notification for Android users
	public function android($data, $reg_id) {
	  //$url = 'https://android.googleapis.com/gcm/send';
	     $url = 'https://fcm.googleapis.com/fcm/send';
	     $message = array(
	            'title' => $data['title'],
	            'message' => $data['mdesc'],
	            'subtitle' => '',
	            'tickerText' => '',
	            'msgcnt' => 1,
	            'vibrate' => 1
	     );
	     $headers = array(
	        	'Authorization: key=' .self::$API_ACCESS_KEY,
	        	'Content-Type: application/json'
	     );
	     $fields = array(
	       'registration_ids' => array($reg_id),
	       'data' => $message,
	     );
		 $response = self::useCurl($url, $headers, json_encode($fields));
		 return $response;

    }


	/*public function firebaseNotification($token, $title, $notificationType, $body,$userName,$incidentType,$badge){
			$fcmUrl = 'https://fcm.googleapis.com/fcm/send';
      $token = $token;
      $bd = json_decode($body);
      if($notificationType =='chat'){
           $titlechat = strtr(\Config::get('constants.NOTIFICATION_TITLE_CHAT'), ['{{MOBILE}}' => $userName]);
         $body = $bd->lastMessage;
      } else {
          $titlechat = \Config::get('constants.NOTIFICATION_TITLE_INCIDENT');
					if(gettype($title) == "integer"){
						$titlechat .= '-'.$title;
					}
          $body = strtr(\Config::get('constants.NOTIFICATION_BODY'), [
                  '{{MOBILE}}' => $userName,
                  '{{INCIDENT_TYPE}}' => $incidentType,
                  '{{LOCATION}}' => $bd->location
           ]);
      }

      $notification = [
          'title' => $titlechat,
          'body' => $body,
          'sound' => true,
          'badge'=> $badge
      ];

      $extraNotificationData = ["data" => ['payload'=>$bd,"notification-type" => $notificationType]];
      $fcmNotification = [
          'to'  => $token,
          'notification' => $notification,
          'data' => $extraNotificationData,
      ];

      $headers = [
          'Authorization: key = AIzaSyABIsLp4H2ZoLuqmNES95gKqCLowyNXpJU',
          'Content-Type: application/json'
      ];

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$fcmUrl);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
      $result = curl_exec($ch);
      curl_close($ch);
      return true;
  }*/

public function firebaseNotification($token, $title, $notificationType, $body,$userName,$incidentType,$badge){
			$fcmUrl = 'https://fcm.googleapis.com/fcm/send';
      $token = $token;
      $bd = json_decode($body);
      if($notificationType =='chat'){
           $titlechat = strtr(\Config::get('constants.NOTIFICATION_TITLE_CHAT'), ['{{MOBILE}}' => $userName]);
         $body = $bd->lastMessage;
      } else {
          $titlechat = \Config::get('constants.NOTIFICATION_TITLE_INCIDENT');
					/*if(gettype($title) == "integer"){
						$titlechat .= '-'.$title;
					}*/
          $body = strtr(\Config::get('constants.NOTIFICATION_BODY'), [
                  '{{MOBILE}}' => $userName,
                  '{{INCIDENT_TYPE}}' => $incidentType,
                  '{{LOCATION}}' => $bd->location
           ]);
      }

      $notification = [
          'title' => $titlechat,
          'body' => $body,
          'sound' => "iphone_notification.mp3",
          'badge'=> $badge
      ];

      $extraNotificationData = ["data" => ['payload'=>$bd,"notification-type" => $notificationType]];
      $fcmNotification = [
          'to'  => $token,
          'notification' => $notification,
          'data' => $extraNotificationData,
      ];

      $headers = [
          'Authorization: key = AIzaSyABIsLp4H2ZoLuqmNES95gKqCLowyNXpJU',
          'Content-Type: application/json'
      ];

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$fcmUrl);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
      $result = curl_exec($ch);
      curl_close($ch);
      return true;
  }

  /*

  incicent report by sandeep kumar
  */
  /*public function firebaseNotificationIncidenReport($gcmRegIds, $messagearray){


        //firebase server url to send the curl request

          $url = 'https://fcm.googleapis.com/fcm/send';
   
          //building headers for the request
          $headers = array(
              'Authorization: key = AIzaSyABIsLp4H2ZoLuqmNES95gKqCLowyNXpJU',
              'Content-Type: application/json'
          );
  
          //Initializing curl to open a connection
          $ch = curl_init();
   
          //Setting the curl url
          curl_setopt($ch, CURLOPT_URL, $url);
          
          //setting the method as post
          curl_setopt($ch, CURLOPT_POST, true);
  
          //adding headers 
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   
          //disabling ssl support
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          
          
         // $gcmRegIds = array($reg_token);
         // print_r($gcmRegIds);exit;
         // $messagearray = array("message" => $message , "title" => $title , "image" => $image);
          
          //adding the fields in json format 
           $fields = array(
               'registration_ids' => $gcmRegIds,
               'data' => $messagearray,
          );
          
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
   
          //finally executing the curl request 
          $result = curl_exec($ch);
          if ($result === FALSE) { 
             die('Curl failed: ' . curl_error($ch));
          }
    
           header("Location: https://www.demonuts.com/Demonuts/JsonTest/Tennis/sendMultipleFCM.php");
               //  exit();
          //Now close the connection
          curl_close($ch);
   
          //and return the result 
          //return $result;
          return true;

        }*/
    
    
    function sendNotificationFCM() {

        $token = "dMtEwLqAvBU:APA91bGYfpJrzUNnnKdXyxwFzh_SkfzVrxpCCfL0BSP8dZ8lM9Hslqu3RTYuJyQemK9ZxkbtuJIDUYNBSrU9sz-XrqDf1zLHJRtzhlG1KC7fjr2Q6mVL2WIO8bK_UBRxjp8rN93xc5kB";
        $id = "4";
        $apiKey = "AIzaSyABIsLp4H2ZoLuqmNES95gKqCLowyNXpJU";
        $messageText = "testtt";
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $apiKey
        );

        $notification = [
            'title' => "infooo ",
            'body' => "dsfdsfdsfds",
            'sound' => true,
            'badge' => 0
        ];
        $fcmNotification = [
            'to' => $token,
            'notification' => $notification
        ];


        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode($fcmNotification)
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        dd($response);
        return $response;
    }

}
?>
