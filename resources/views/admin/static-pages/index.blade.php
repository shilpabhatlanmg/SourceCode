@extends('adminLayout')

@section('content')

@section('pageTitle')

{{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')

    <div class="main-page">
        <div class="tables">

            <h4>Static Page Management</h4>

            <div class="table-responsive bs-example widget-shadow">

                <h4>Static Page List</h4>
                

                @include('common.admin.flash-message')
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th style="width: 143px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($arrData)>0 && !empty($arrData))
                            @foreach($arrData as $key => $var)

                            <?php
                            $status = '';
                            if ($var->status == 'Inactive') {
                                $status = "Inactive";
                                $label = "danger";
                                $sts = 'Active';
                            } else {
                                $status = "Active";
                                $label = "success";
                                $sts = 'Inactive';
                            }

                            ?>

                            <tr class="trCate">
                                <td>{{ ($arrData->currentpage()-1) * $arrData->perpage() + $key + 1 }}</td>
                                <td>
                                    
                                    {{ (isset($var) && !empty($var) && is_object($var) ? $var->page_title : '') }}
                                
                                </td>
                                <td><span class="label label-{{ $label }}">{{ $status }}</span></td>
                                
                            <!--<td class="status{{!empty($var) && is_object($var) && !empty($var->id) ? $var->id : '' }}">
                                <span class="label label-{{ $label }} sts" style="cursor: pointer;" data-sts="{{ $sts }}" title="click to make {{ $sts }}"  data-id="{{ !empty($var) && is_object($var) && !empty($var->id) ? $var->id : '' }}" data-type="security">{{ $status }}</span>
                            </td>-->

                            <td class="actions eye2 center">

                                <a href="{{ url('admin/edit-page', ['id' => $var->slug_url]) }}" class="btn btn-primary btn-xs" title="Edit">
                                    <i class="fa fa-pencil" aria-hidden="true">&nbsp;</i>

                                </a>
                            </td>
                        </tr>

                        @endforeach
                        @else
                        <tr>
                            <td colspan="6" align='center'>
                                <span class="label label-danger">No Record Found !!!</span>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        {{ $arrData->render() }}
    </div>
</div>
</div>
@endsection

@section('addtional_css')
@endsection

@section('jscript')
@endsection