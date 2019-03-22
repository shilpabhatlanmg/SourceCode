@extends('adminLayout')

@section('content')

@section('pageTitle')

{{ (isset($title) && !empty($title) ? $title : '') }}

@endsection

<div id="page-wrapper">
    @include('common.admin.breadcrumb')

    <div class="main-page">
        <div class="tables">

            <h4>Dynamic Content Management</h4>

            <div class="table-responsive bs-example widget-shadow">

                <h4>Dynamic Content List</h4>

                @include('common.admin.flash-message')
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th style="width: 143px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($arrData)>0 && !empty($arrData))
                            @foreach($arrData as $key => $var)

                            <tr class="trCate">
                                <td>{{ ($arrData->currentpage()-1) * $arrData->perpage() + $key + 1 }}</td>
                                <td>
                                    <div class ="nowrap">
                                        {{ (!empty($var) && is_object($var) && !empty($var->title) ? $var->title : 'Not Available') }}
                                    </div>
                                </td>
                                <td style="background-color:#6495ed;">
                                    <div class ="nowrap" style="background-color:#6495ed;width:300px !important;">
                                     {!! (!empty($var) && is_object($var) && !empty($var->content) ? $var->content : '') !!}
                                 </div>

                                 </td>

                                <td class="actions eye2 center">

                                    <a href="{{ route('edit.content', ['id' => $var->slug]) }}" class="btn btn-primary btn-xs" title="Edit">
                                        <i class="fa fa-pencil" aria-hidden="true">&nbsp;</i>

                                    </a>
                                </td>
                            </tr>

                            @endforeach
                            @else
                            <tr>
                                <td colspan="4" align='center'>
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
