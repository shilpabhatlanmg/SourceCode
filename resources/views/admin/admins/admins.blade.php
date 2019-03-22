@extends('adminLayout')
@section('content')
@section('pageTitle')
{{ (isset($title) && !empty($title) ? $title : '') }}
@endsection
<style>
    .space a {
    margin-right: 4px;
}
</style>

<div id="page-wrapper">
    @include('common.admin.breadcrumb')

    <div class="main-page">
        <div class="tables">

            <h4>Admin Users</h4>

            <div class=" bs-example widget-shadow">

                @include('common.admin.flash-message')
                <div class="row">

                        {{ Form::open(array('method' => 'get', 'id'=>'organisation_id')) }}
                        <div class="col-md-3 col-sm-3 col-xs-6 srch-left full-wid">
                            {{ Form::text('search', app('request')->input('search'), ['placeholder'=>'Search...', 'class'=>'form-control']) }}
                        </div>

                        <div class="col-lg-3  col-md-4 col-xs-6 col-sm-4 pd-btn full-wid2">
                            {{ Form::submit('Submit', ['class'=>'btn btn-primary']) }}
                            <a href="{{url('/admin/admin-user')}}" class="btn btn-default">Reset</a>
                        </div>
                          

                        {{Form::close()}}

                         <a href="{{url('/admin/create-admin-user') }} "> <button type="button" class="btn btn-default cr_btn">Create Admin User</button></a>

                        <!--<button class="btn btn-default cr_btn" onclick="window.location.href ='{{ route('location.create') }}'">Create Location</button>-->

                    </div>
                <div class="table-responsive">

                    <table class="table table-bordered table-condensed cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>

                                <th>@sortablelink('name', 'Name')</th>
                                <th>@sortablelink('email', 'Email Id')</th>
                                 <th>@sortablelink('phone', 'Contact Number')</th>
                                 <th>@sortablelink('status', 'Status')</th>
                                <th style="width: 143px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($adminorganisation)>0 && !empty($adminorganisation))
                            @foreach($adminorganisation as $key => $var)

                            @php

                            if ($var->status == 'Inactive') {
                                $status = "Inactive";
                                $label = "danger";
                                $sts = 'Active';
                            } else if($var->status == 'Active') {
                                $status = "Active";
                                $label = "success";
                                $sts = 'Inactive';
                            }else {

                                $status = "Inactive";
                                $label = "danger";
                                $sts = 'Active';

                            }

                            @endphp

                            <tr class="trCate">
                                <td>{{ ($adminorganisation->currentpage()-1) * $adminorganisation->perpage() + $key + 1 }}</td>
                                <td>
                                    <div class ="nowrap">
                                        {{ (!empty($var) && is_object($var) && !empty($var->name) ? $var->name : '') }}
                                    </div>
                                </td>
                                <td>
                                    <div class ="nowrap">
                                        {{ (!empty($var) && is_object($var) && !empty($var->email) ? $var->email : '') }}
                                    </div>
                                </td>
                                <td>
                                    <div class ="nowrap">
                                        {{ (!empty($var) && is_object($var) && !empty($var->phone) ? $var->phone : '') }}
                                    </div>
                                </td> 
                                

                                @php
                                    $encryptId = \Crypt::encryptString($var->id);
                                @endphp

                                <td>
                                    <span class="label label-{{ $label }} sts" style="cursor: pointer;" data-sts="{{ $sts }}" title="click to make {{ $sts }}"  data-id="{{ !empty($var) && !empty($var->id) ? $encryptId : '' }}" data-type="admin-user">{{ $status }}</span>
                                </td>

                                <td class="actions center btn-2 action-center">
                                    <div class="btn-group space" style="width:90px!important">

                                    <a href="{{ route('admin-user.edit', ['id' => $encryptId]) }}" class="btn btn-primary btn-xs" title="Edit">
                                        <i class="fa fa-pencil" aria-hidden="true">&nbsp;</i>

                                    </a>
                                    
                                     <a href="{{ route('admin-user.show', ['id' => $encryptId ] ) }}" class="btns br3  view-record" title="View">
                                        <i class="fa fa-eye" aria-hidden="true">&nbsp;</i>
                                     </a>    

                                    <a href="" id="{{ $encryptId }}" class="btn btn-danger btn-xs delete-record" title="Delete">
                                        <i class="fa fa-trash-o" aria-hidden="true">&nbsp;</i>

                                    </a>
                                </div>
                                </td>
                            </tr>

                            {!!
                                Form::open(
                                array(
                                'name' => 'delete-form',
                                'id' => 'delete-form-'.$encryptId,
                                'url' => 'admin/admin-user/'.(!empty($var) && is_object($var) && !empty($var->id) ? $encryptId : ''),
                                'autocomplete' => 'off',
                                'class' => 'form-horizontal',
                                'style' => 'display:none',
                                'files' => false
                                )
                                )
                                !!}

                                {{ Form::hidden('id', (!empty($var) && is_object($var) && !empty($var->id) ? $encryptId : '')) }}

                                {{ method_field('DELETE') }}

                                {!! Form::close() !!}

                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8" align='center'>
                                        <span class="label label-danger">No Record Found !!!</span>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
    @section('addtional_css')
    @endsection
    @section('jscript')
    <script type="text/javascript" src="{{asset('/public/admin_assets/js/app-js/delete-confirm.js')}}"></script>
    @endsection
