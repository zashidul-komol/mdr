@extends('layouts.admin')
@section('title', 'Application List')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Application List</a></li>
            <li><a>Application List</a></li>
        </ul>
    </div>
</div>
<div class="tabs">
    <ul class="nav nav-tabs">
        <li><a href="{{ route('requisitions.index') }}">New</a></li>
        <li><a href="{{ route('requisitions.submitted') }}">Submitted</a></li>
        <li class="{{'active'}}"><a href="{{ route('requisitions.processing') }}">Processing</a></li>
        <li><a href="{{ route('requisitions.returned') }}">Returned</a></li>
        <li><a href="{{ route('requisitions.cancelled') }}">Cancelled</a></li>
        <li><a href="{{ route('requisitions.approved') }}">Approved</a></li>
        <li><a href="{{ route('requisitions.activelist') }}">Active MDR</a></li>
        <li><a href="{{ route('requisitions.inactive') }}">Inactive MDR</a></li>
        <li><a href="{{ route('requisitions.officerActiveMDR') }}">Officer's MDR</a></li>
    </ul>
</div>
<div class="row animated fadeInRight">
    <div class="col-sm-12">
        <h4 class="section-subtitle">
            <b>Application List</b>
        </h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('dashboards.index','<i >Back</i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
       
        <div class="panel">
            <div class="panel-content">
                 <div class="table-responsive">
                    <table id="datatable" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                        <thead>
                      <tr>
                        <th width="5%">SI NO.</th>
                        <th width="10%">Application No.</th>
                        <th width="10%">MDR ID</th>
                        <th width="25%">Region Name</th>
                        <th width="25%">Depot Name</th>
                        <th width="25%">Distributor Name</th>
                        <th width="25%">Applicant Name</th>
                        <th width="20%">Applicant Education</th>
                        <th width="15%">Application Date</th>
                        <th width="5%">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @php ($i=1)
                        @foreach ($reportToApplications as $data)
                      <tr>
                        <td>{{$i}}</td>
                        <td>{{$data->id}}</td>
                        <td>{{$data->mdrInformation[0]->mdr_idcard  ??  ''}}</td>
                        <td>{{$data->region->name  ??  ''}}</td>
                        <td>{{$data->depot->name  ??  ''}}</td>
                        <td>{{$data->distributor->distributorName  ??  ''}}</td>
                        <td>{{$data->application_details[0]->applicant_name  ??  ''}}</td>
                        <td>{{$data->application_details[0]->applicant_education  ??  ''}}</td>
                        <td>{{$data->created_at  ??  ''}}</td>
                        <td>                               
                            <a class="fa fa-eye fa-x" style="cursor:pointer" onclick="showModal({{ $data->id }})"></a>                          
                        </td>
                      </tr>
                        @php ($i=$i+1)
                        @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
        <!-- Modal for problem entry start-->
        @include('common_pages.common_modal',['modalTitle'=>'Applicant Details', 'modalSize'=>'modal-lg'])
         <!-- Modal for problem entry end-->
    </div>
</div>
@endsection
@component('common_pages.data_table_script')
@slot('css')
    <style>
      .dropup, .dropdown{
        display: inline-block;
      }
      .dropdown-menu{
        right: 0;
        left:auto;
      }
    </style>
@endslot
<script>

  $(function(){

        "use strict";
        var table = $('.data-table').DataTable({
            //"aaSorting": [],  /* Disable initial sort Older version*/
            "order": [], /* No ordering applied by DataTables during initialisation */
            "pageLength": 25,
            "columnDefs": [ {
              "targets": 'no-sort',
              "orderable": false,
              "order": []
            } ]
        });

   // Handle click on "Select all" control
      $('#datatable-select-all').on('click', function(){
         // Get all rows with search applied
         var rows = table.rows({ 'search': 'applied' }).nodes();
         // Check/uncheck checkboxes for all rows in the table
         $('input[type="checkbox"]', rows).prop('checked', this.checked);
      });

      // Handle click on checkbox to set state of "Select all" control
      $('#datatable tbody').on('change', 'input[type="checkbox"]', function(){
         // If checkbox is not checked
         if(!this.checked){
            var el = $('#datatable-select-all').get(0);
            // If "Select all" control is checked and has 'indeterminate' property
            if(el && el.checked && ('indeterminate' in el)){
               // Set visual state of "Select all" control
               // as 'indeterminate'
               el.indeterminate = true;
            }
         }
      });
  });
</script>
@section('vuescript')
<script>
        function showModal(id,){
            laravelObj.common=id;
            var modalBody=$('#modal-body');
            modalBody.css('padding-top',0);
            modalBody.html('');
            $.ajax({
                type: 'Get',
                url:"{{ route('ajax.items.getRequisitionProcessing') }}",
                data:{id:id}
            }).done(function(response) {
                 modalBody.html(response);
                 $.fn.select2.defaults.set( "theme", "bootstrap" );
                 $(".select2").select2({
                     placeholder: function(){
                         $(this).data('placeholder');
                     },
                    allowClear: true
                });
            }).fail(function(response) {
                console.log(response);
            });
            $('#common-modal').modal('show');
        };

      
    </script>
@stop

@slot('css')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2-bootstrap.min.css') }}">
@endslot

@include('common_pages.max_length')
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
@endcomponent

