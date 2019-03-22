<?php
use Illuminate\Database\Seeder;

class EmailTemplateTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email_template = new App\Model\Admin\EmailTemplate\EmailTemplate();
        $email_template->name = 'Email Subscription';
        $email_template->template_code = 'SB001';
        $email_template->subject = 'Activate Email Subscription';
        $email_template->message = '<p>Hi&nbsp;{email}<p>A new message for you. Check it.</p><p>{link}</p><p>&nbsp;</p><p>Regards</p><p>www.protectapp.com</p>';
        $email_template->status = '1';
        $email_template->save();

        $email_template = new App\Model\Admin\EmailTemplate\EmailTemplate();
        $email_template->name = 'Contact Us';
        $email_template->template_code = 'SB002';
        $email_template->subject = 'Contact Us';
        $email_template->message = '<p>Hi&nbsp;{name}<p>Your contact details have been sent successfully.</p>';
        $email_template->status = '1';
        $email_template->save();

        $email_template = new App\Model\Admin\EmailTemplate\EmailTemplate();
        $email_template->name = 'User Signup';
        $email_template->template_code = 'SB003';
        $email_template->subject = 'User Signup';
        $email_template->message = '<p>Hi&nbsp;{email}<p>A new message for you. Check it.</p><p>{link}</p><p>&nbsp;</p><p>Regards</p><p>www.protectapp.com</p>';
        $email_template->status = '1';
        $email_template->save();

        $email_template = new App\Model\Admin\EmailTemplate\EmailTemplate();
        $email_template->name = 'User Activated';
        $email_template->template_code = 'SB004';
        $email_template->subject = 'User Signup Welcome Message';
        $email_template->message = '<p>Hi&nbsp;{name}<p><p>Email-:&nbsp;{email}<p>A new message for you. Check it.</p><p>{link}</p><p>&nbsp;</p><p>Regards</p><p>www.protectapp.com</p>';
        $email_template->status = '1';
        $email_template->save();

        $email_template = new App\Model\Admin\EmailTemplate\EmailTemplate();
        $email_template->name = 'Password Change';
        $email_template->template_code = 'SB005';
        $email_template->subject = 'Password Change Request';
        $email_template->message = '<p>Hi&nbsp;{name}<p><p>Email&nbsp;{email}<p>A new message for you.</p> <p>Password has been changed. </p><p>User Email -: {user_email}</p><p> New password is -: {password} </p><p>Check it.</p><p>{link}</p><p>&nbsp;</p><p>Regards</p><p>www.protectapp.com</p>';
        $email_template->status = '1';
        $email_template->save();
        
        $email_template = new App\Model\Admin\EmailTemplate\EmailTemplate();
        $email_template->name = 'User Activated';
        $email_template->template_code = 'SB006';
        $email_template->subject = 'User Activated';
        $email_template->message = '<p>Hi&nbsp;{name}<p><p>Email-:&nbsp;{email}<p>A new message for you. Check it.</p><p>{link}</p>&nbsp;<p>Your User has been activated.</p><p>&nbsp;</p><p>Regards</p><p>www.protectapp.com</p>';
        $email_template->status = '1';
        $email_template->save();

        $email_template = new App\Model\Admin\EmailTemplate\EmailTemplate();
        $email_template->name = 'Otp';
        $email_template->template_code = 'SB007';
        $email_template->subject = 'Protect App OTP';
        $email_template->message = '<p>Hi&nbsp;{name}<p><p>Email-:&nbsp;{email}<p>A new message for you. Check it.</p><p>{link}</p>&nbsp;<p>Your Otp is <b>{otp}</b></p><p>&nbsp;</p><p>Regards</p><p>www.protectapp.com</p>';
        $email_template->status = '1';
        $email_template->save();
    }
}
