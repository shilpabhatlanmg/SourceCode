<!DOCTYPE html>
<html lang="en" class="">
    <head>
        <meta charset="utf-8">
        <title>{{ !empty($title) ? $title : '' }}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-touch-fullscreen" content="yes">
        <meta name="author" content="bringit">
        <link rel="icon" href="{{ asset('/assets/images/favicon.png') }}" type="" sizes="16x16">
        <link type='text/css' href='http://fonts.googleapis.com/css?family=Roboto:300,400,400italic,500' rel='stylesheet'>
        <link type='text/css'  href="https://fonts.googleapis.com/icon?family=Material+Icons"  rel="stylesheet"> 
        <link href="{{ asset('assets/plugins/progress-skylo/skylo.css') }}" type="text/css" rel="stylesheet">
        <style>
            .dv-cls{
                background: #ccc none repeat scroll 0 0;
                margin: 5% auto;
                padding: 10%;
                text-align: center;
                width: 43%;
            }
        </style>
    </head>

    <body class="focused-form animated-content">
        <div class="dv-cls">
            <p>
                @if($offline_message && $offline_message != '')
                {{$offline_message}}
                @else
                We will be right back soon...
                @endif
            </p>
        </div>
    </body>
</html>



