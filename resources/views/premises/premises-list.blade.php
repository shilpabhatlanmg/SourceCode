@extends('adminLayout')
@section('content')
@section('pageTitle')
    {{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

<!-- main content start-->
<div id="page-wrapper">
    @include('common.admin.breadcrumb')
    <div class="main-page">
        <h2 class="title1">Premises Management</h2>
        <div class="blank-page widget-shadow scroll" id="style-2 div1">
            @include('common.admin.flash-message')
            <div class="row">
                <button class="btn btn-default" onclick="window.location.href ='{{ route('premises.create') }}'">Create Premises</button>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h3 class="divider-sec text-right">@yield('pageTitle')</h3>
                    <table class="table table-bordered table-condensed" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Number of becons</th>
                                <th>Status</th>
                                <th style="width: 143px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($arrData)>0 && !empty($arrData))
                            @foreach($arrData as $key => $var)

                            <?php
                            $status = '';
                           
                            if ($var->status == 0) {
                                $status = "Inactive";
                                $label = "danger";
                            } else {
                                $status = "Active";
                                $label = "success";
                            }

                            ?>
                            <tr class="trCate">
                                <td>{{ ($arrData->currentpage()-1) * $arrData->perpage() + $key + 1 }}</td>
                                <td>{{ $var->name }}</td>
                                <td>{{ (($var->number_of_becons > 0)? $var->number_of_becons:'0') }}</td>
                                <td><span class="label label-{{ $label }}">{{ $status }}</span></td>
                                <td class="actions center">
                                    <a href="{{ route('premises.edit', ['id' => $var->id]) }}">
                                        <i class="fa fa-pencil" aria-hidden="true">&nbsp;Edit</i>
                                    </a>
                                    |
                                    <a href="" id="{{ $var->id }}" class="delete-record">
                                        <i class="fa fa-trash-o" aria-hidden="true">&nbsp;Delete</i>

                                    </a>
                                </td>
                            </tr>

                            {!!
                                Form::open(
                                array(
                                'name' => 'delete-form',
                                'id' => 'delete-form-'.$var->id,
                                'url' => 'admin/premises/'.(!empty($var) && is_object($var) && !empty($var->id) ? $var->id : ''),
                                'autocomplete' => 'off',
                                'class' => 'form-horizontal',
                                'style' => 'display:none',
                                'files' => false
                                )
                                )
                                !!}

                                {{ Form::hidden('id', (!empty($var) && is_object($var) && !empty($var->id) ? $var->id : '')) }}
                               
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
                                <tr>
                                    <td colspan="8" align="center"> {{ $arrData->render() }} </td>
                                </tr>
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
        <script type="text/javascript" src="{{asset('/admin_assets/js/app-js/delete-confirm.js')}}"></script>
    @endsection
