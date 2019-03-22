@extends('admin.subscription.create', ['objData' => $objData])

@section('editMethod')

	{{ method_field('PUT') }}

@stop

@section('pageTitle')

	{{ (isset($title) && !empty($title) ? $title : '') }}

@stop