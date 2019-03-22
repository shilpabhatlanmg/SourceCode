<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>@yield('title')</title>
    </head>
    <body>
        <table width="600" border="0" cellspacing="0" cellpadding="0" style="margin:auto;border: 1px solid #f5f5f5;margin: auto;background: rgb(238, 238, 238) none repeat scroll 0% 0%;" >
            <tr>
                <td>
                    <table align="center" width="100%" style="border-bottom: 4px solid #172e43; background: #fff;">
                        <tr>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><table align="center" width="100%">
                        <tr>
                            <td style="height:25px"></td>
                        </tr>
                        <tr>
                            <td align="center" width="100%">
                                <img src="{{ asset('/public/assets/images/logo2.png') }}" style="with:200px;" />
                            </td>
                        </tr>
                        <tr>
                            <td style="color: #000; font-family: normal; font-size: 17px; padding: 0 14px;">
                                @if(isset($data['message']) && !empty($data['message']))
                                {!! @$data['message'] !!}
                                @else
                                @yield('content')
                                @endif
                            </td>
                        </tr>
                        <!--<tr>
                            <td style="display: inline-block; float: left; width: 100%; margin: 15px 0px 0px;
                                background: rgb(238, 238, 238) none repeat scroll 0% 0%; color: rgb(136, 136, 136);padding: 15px 0px 0px; font-family: normal;">
                                <strong style="margin:0px 25px;font-weight: 600;color: #122333;font-size: 14px;;">Please Note:</strong>
                                <ol style="line-height: 23px; font-size: 12px;">
                                    <li>This is a system generated email. Please do not reply to this email.</li>
                                    <li>By using our website, you agree to terms of use, privacy policy and disclaimer mentioned on our website.
                                    </li>
                                </ol>
                            </td>
                        </tr>-->
                    </table>
                </td>
            </tr>
            <tr>
                <td><table width="100%">
                        <tr style="background: #eeeeee none repeat scroll 0 0;;font-family: arial;font-size: 12px;height: 24px; text-align: center;">
                            <td style="background: #203747;height: 25px; width: 50%"><a href="{{ route('terms.conditions') }}" style="color: #fff;text-decoration: none;">Terms & Conditions </a></td>
                            <td style="background: #203747;height: 25px;"><a href="{{ route('privacy.policy') }}" style="color: #fff;text-decoration: none;">Privacy Policy </a></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="display: inline-block; margin: 15px 0px 0px;
                                background: rgb(238, 238, 238) none repeat scroll 0% 0%; color: rgb(136, 136, 136);padding: 15px 0px 0px; font-family: normal;">
                                <strong style="margin:0px 25px;font-weight: 600;color: #122333;font-size: 14px;;">Please Note:</strong>
                                <ol style="line-height: 23px; font-size: 12px;">
                                    <li>This is a system generated email. Please do not reply to this email.</li>
                                    <li>By using our website, you agree to terms of use, privacy policy and disclaimer mentioned on our website.
                                    </li>
                                </ol>
                            </td>
                        </tr>
                        <tr style="font-family: arial;font-size: 12px;height: 24px; text-align: center;">
                            <td align="center" colspan="2">© 2019 ProtectApp™. All Rights Reserved</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- <tr style="height: 20px;">
                <td></td>
            </tr>
            <tr>
                <td>
                    <table align="center" style="color:#203747;
                           display: inline-block;font-family: normal;
                           font-size: 13px;
                           margin-right: 10px;
                           vertical-align: middle;background:#fff;width:100%;">
                        <tbody>
                            <tr height="60">
                                <td width="10">&nbsp;</td>
                                <td>&copy; Till today</td> -->
                                <!-- <td><a href="#"><img src="{{ asset('/public/assets/images/logo2.png') }}"></a></td>-->

                                <!-- <td>© 2019 ProtectApp™. All Rights Reserved</td>
                                <td width="170">&nbsp;</td>
                                <td><a href="#"><img src="{{ asset('/public/assets/images/fb.svg') }}" width="25px"></a></td>
                                <td><a href="#"><img src="{{ asset('/public/assets/images/tw.svg') }}" width="25px"></a></td>
                                <td><a href="#"><img src="{{ asset('/public/assets/images/gp.svg') }}" width="25px"></a></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr> -->
        </table>
    </body>
</html>
