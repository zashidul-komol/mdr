@extends('layouts.admin')

<style type="text/css">
  input:valid {
    background-color: #99ff99;
}
select:valid {
    background-color: #99ff99;
}
textarea:valid {
    background-color: #99ff99;
}
</style>
@section('content')
<div class="content-header">
    <div class="text-center">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="#"><h4 class="section-subtitle"><b>MDR Monthly Attendance</b></h4></a></li>
        </ul>
    </div>
</div>
    <div class="panel">
            <div class="panel-content">
                <div class="table-responsive">
                        <div class="form-group">
                          <label for="inputName" class="col-sm-1 ">Region</label>

                          <div class="col-sm-2">
                            {{Form::text('name',$RegionName,array('class' => 'form-control', 'readonly' => 'true'))}}
                              {!! $errors->first('name', '<p class="text-danger">:message</p>' ) !!}
                          </div>
                          <label for="inputName" class="col-sm-1 ">Date</label>

                          <div class="col-sm-2">
                             {{Form::text('name',$TodayDate, array('class' => 'form-control', 'readonly' => 'true'))}}
                              {!! $errors->first('name', '<p class="text-danger">:message</p>' ) !!}
                          </div>
                          <label for="inputName" class="col-sm-1 ">Month</label>

                          <div class="col-sm-2">
                             {{Form::text('name',$MonthName,array('class' => 'form-control' , 'readonly' => 'true'))}}
                              {!! $errors->first('name', '<p class="text-danger">:message</p>' ) !!}
                          </div>
                          <label for="inputName" class="col-sm-1 ">Year</label>

                          <div class="col-sm-2">
                             {{Form::text('name',$year,array('class' => 'form-control' , 'readonly' => 'true'))}}
                              {!! $errors->first('name', '<p class="text-danger">:message</p>' ) !!}
                          </div>
                      </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                        <thead>
                      <tr>
                        <th width="2%" align="left"></th>
                        <th width="15%" align="left">DB Name</th>
                        <th width="15%" align="left">MDR Name</th>
                        <th width="7%" align="left">Rocket No</th>
                        <th width="10%" align="left">Working Days</th>
                        <th width="7%" align="left">Salary</th>
                        <th width="7%" align="left">TA</th>
                        <th width="7%" align="left">DA</th>
                        <th width="10%" align="left">Mobile Bill</th>
                        <th width="10%" align="left">Gross Salary</th>
                        <th width="10%">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                        @php ($i=1)
                        @foreach ($MdrInformations as $data)
                      <tr>
                        <td>{{$i}}</td>
                        <td>{{$data->distributors->distributorName  ??  ''}}</td>
                        <td>{{$data->applicant_name  ??  ''}}</td>
                        <td>{{$data->applicant_mobile  ??  ''}}</td>
                        <td>{{Form::text('working_days',null,array('class' => 'form-control'))}}</td>
                        <td>{{Form::text('salary',null, array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('travelling_allowance',null,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('dearness_allowance',null,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('mobile_bill',null,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('gross_salary',null,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::select('status',[''=>'--Please Select Active/Inactive--']+['active'=>'Active', 'inactive'=>'Inactive'],null,array('class' => 'form-control'))}}</td>
                      </tr>
                        @php ($i=$i+1)
                        @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
    </div>

    
@endsection

@component('common_pages.selectize')
@include('common_pages.max_length')
<script src="{{ asset('vendor/bootstrap_date-picker/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">

        $('.datepicker').datepicker({ format: "yyyy-mm-dd",todayHighlight: true,autoclose:true});

        //get shops or distributor
        function getExecutiveDepotShop(depotId){
          $('#shop-list').html('');
          $.ajax({
              type: 'Get',
              url:"{{ route('ajax.getShops') }}",
              data:{depot_id:depotId,distributor:1}
            }) .done(function(response) {
             $('#shop-list').html(response);
           //Select2 basic example
             $.fn.select2.defaults.set( "theme", "bootstrap" );
              $(".select2").select2({
                 // placeholder: function(){
                 //     $(this).data('placeholder');
                 // },
                 allowClear: true
             });
            if('{{old('shop_id')}}'){
              $("#shop_id").val('{{old('shop_id')}}').change();
            }

          })
          .fail(function(response) {
          });
        }

            $('#submit').prop("disabled", true);
            $('input:checkbox').click(function() {
             if ($(this).is(':checked')) {
             $('#submit').prop("disabled", false);
             } else {
             if ($('.checks').filter(':checked').length < 1){
             $('#submit').attr('disabled',true);}
             }
            });
    </script>
    @slot('css')
     <!--Date picker-->
     <link rel="stylesheet" href="{{ asset('vendor/bootstrap_date-picker/css/bootstrap-datepicker3.min.css') }}">
    @endslot
@endcomponent

