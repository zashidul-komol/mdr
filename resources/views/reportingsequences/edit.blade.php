@extends('layouts.admin')
@section('title', 'Update Reporting Sequence')
@section('content')

<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li>
                <i class="fa fa-table" aria-hidden="true"></i>
                <a href="{{ route('reportingsequences.index') }}">
                    Reporting Sequence
                </a>
            </li>
            <li><a>Update</a></li>
        </ul>
    </div>
</div>

<div class="row animated fadeInRight">
    <div class="col-sm-12">

        <h4 class="section-subtitle">
            <b>Update Reporting Sequence</b>
        </h4>

        <span class="pull-right">
            {!! Html::decode(
                link_to_route(
                    'reportingsequences.index',
                    '<i class="fa fa-list"></i>',
                    [],
                    ['class' => 'btn btn-success btn-right-side']
                )
            ) !!}
        </span>

        <div class="panel">
            <div class="panel-content">

                {{ Form::model($reportingsequence, [
                    'route' => [
                        'reportingsequences.update',
                        $reportingsequence->id
                    ],
                    'method' => 'PUT',
                    'class' => 'form-horizontal'
                ]) }}

                {{-- User Name --}}
                <div class="form-group">
                    {{ Form::label(
                        'user_id',
                        'User Name:',
                        ['class' => 'control-label col-sm-2']
                    ) }}

                    <div class="col-md-6">
                        <input
                            type="text"
                            class="form-control"
                            value="{{ $reportingsequence->user->name ?? '' }}"
                            readonly>
                    </div>
                </div>

                {{-- Reporting To --}}
                @foreach(
                    $reportingsequence->reportingsequence_details
                    as $key => $detail
                )

                    <div class="form-group{{ $errors->has('details.'.$key.'.report_to') ? ' has-error' : '' }}">

                        {{ Form::label(
                            'details_'.$key.'_report_to',
                            'Reporting To:',
                            ['class' => 'control-label col-sm-2 require']
                        ) }}

                        <div class="col-md-6">

                            {{ Form::select(
                                'details['.$key.'][report_to]',
                                $users,
                                old(
                                    'details.'.$key.'.report_to',
                                    $detail->report_to
                                ),
                                [
                                    'id' => 'details_'.$key.'_report_to',
                                    'class' => 'form-control select2',
                                    'placeholder' => 'Select Reporting To'
                                ]
                            ) }}

                            {!! $errors->first(
                                'details.'.$key.'.report_to',
                                '<p class="text-danger">:message</p>'
                            ) !!}

                        </div>
                    </div>

                @endforeach

                {{-- Update Button --}}
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-2">

                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>

                        <a
                            href="{{ route('reportingsequences.index') }}"
                            class="btn btn-default">
                            Cancel
                        </a>

                    </div>
                </div>

                {{ Form::close() }}

            </div>
        </div>
    </div>
</div>

@endsection
