<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;
use Plivo;
use Event;
use App\Traits\ApiResponseTrait;


class ResendInvitationCron extends Command
{

    use ApiResponseTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resend:invitation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'if user not get otp for active then admin will send resent invitation after expiry otp';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $arr_user = User::getAllUserInvitationCron();

        //dd($arr_user->toArray());

        if (!empty($arr_user) && is_object($arr_user) && count($arr_user) > 0) {

            foreach ($arr_user as $user) {

                
                $t1 = Carbon::parse($user->otp_created_at);
                $t2 = Carbon::parse(date('Y-m-d H:i:s'));
                

                $timestamp = strtotime($t1); //1373673600

                // getting current date 
                $cDate = strtotime($t2);

                // Getting the value of old date + 24 hours
                $oldDate = $timestamp + 86400; // 86400 seconds in 24 hrs

                if($oldDate < $cDate)
                {

                    $otp = $this->getOtp();

                    $params = array(
                        'src' => \Config::get('constants.PLIVO_SOURCE_NUMBER'),
                        'dst' => $this->removeBraces($user->country_code . $user->contact_number),
                        'text' => strtr(\Config::get('constants.SECURITY_INVITE_SMS'), [
                            '{{MOBILE}}' => $user->country_code . ' ' . $this->removeBraces($user->contact_number),
                            '{{OTP}}' => $otp
                        ])
                    );

                    $eventData['to'] = $user->email;
                    $eventData['variable']['{name}'] = !empty($user->name) ? $user->name : '';
                    $eventData['variable']['{email}'] = !empty($user->email) ? $user->email : '';
                    $eventData['variable']['{otp}'] = $otp;
                    $eventData['variable']['{link}'] = link_to_route('login', 'Login Now!');
                    $eventData['template_code'] = 'SB007';

                    Event::fire('sendEmailByTemplate', collect($eventData));

                    if(Plivo::sendSMS($params)['status'] == '202'){

                        $requestUser['invitation_status'] = 'resend-invitation';
                        $requestUser['otp'] = $otp;

                        $user = User::storeOrUpdateData($requestUser, $user->id);

                        $this->info('OTP Sent your mobile or email id');

                    } else {

                        $this->info('OTP Sent your mail id');
                        
                    }

                }
            }
        }


        
    }
}
