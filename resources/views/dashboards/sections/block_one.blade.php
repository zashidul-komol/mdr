<div class="col-md-6">
    <span class="pull-left">
            <a class="btn btn-success btn-left-side" href="{{ route('requisitions.create',[]) }}">Application form for MDR</a>
    </span>
    <h4 class="section-subtitle"><b>MDR Application Status</b> At A Glance</h4>
    <div class="panel">
           <div class="row dash-box-height SixBox">
                <div class="col-md-4">
                    <a href="{{ route('requisitions.index',[1]) }}">
                        <div class="dash-box-heightIn">
                            <h4 class="subtitle">New Application</h4>
                            <div class="row">
                                <div class="col-xs-6">
                                    <h5 class="title color-primary"> {{number_format($countPendingReq)}}</h5>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <img class="svg" src="{{ asset('storage/images/dashboard-icon/purchase.png') }}" alt="dfno">
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('requisitions.approved',[1]) }}">
                        <div class="dash-box-heightIn">
                            <h4 class="subtitle">Approved</h4>
                            <div class="row">
                                <div class="col-xs-6">
                                    <h5 class="title color-primary"> {{number_format($countApprovedReq)}}</h5>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <img class="svg" src="{{ asset('storage/images/dashboard-icon/inject.png') }}" alt="dfno">
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('requisitions.cancelled',[1]) }}">
                        <div class="dash-box-heightIn">
                            <h4 class="subtitle">Cancelled</h4>
                            <div class="row">
                                <div class="col-xs-6">
                                    <h5 class="title color-primary"> {{number_format($countCancelReq)}}</h5>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <img class="svg" src="{{ asset('storage/images/dashboard-icon/stock.png') }}" alt="dfno">
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('requisitions.processing',[1]) }}">
                        <div class="dash-box-heightIn">
                            <h4 class="subtitle">Processing</h4>
                            <div class="row">
                                <div class="col-xs-6">
                                    <h5 class="title color-primary"> {{number_format($countProcessingReq)}}</h5>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <img class="svg" src="{{ asset('storage/images/dashboard-icon/in_sip.png') }}" alt="dfno">
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('requisitions.activelist',[1]) }}">
                        <div class="dash-box-heightIn">
                            <h4 class="subtitle">Active MDR</h4>
                            <div class="row">
                                <div class="col-xs-6">
                                    <h5 class="title color-primary"> {{number_format($countActiveMDR)}}</h5>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <img class="svg" src="{{ asset('storage/images/dashboard-icon/inject.png') }}" alt="dfno">
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('requisitions.inactive',[1]) }}">
                        <div class="dash-box-heightIn">
                            <h4 class="subtitle">Inactive MDR</h4>
                            <div class="row">
                                <div class="col-xs-6">
                                    <h5 class="title color-primary"> {{number_format($countInactiveMDR)}}</h5>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <img class="svg" src="{{ asset('storage/images/dashboard-icon/inject.png') }}" alt="dfno">
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('mdrattendances.attendanceList',[1]) }}">
                        <div class="dash-box-heightIn">
                            <h4 class="subtitle">Depot Attendance List</h4>
                            <div class="row">
                                <div class="col-xs-6">
                                    <h5 class="title color-primary"> {{number_format($DepotAttendanceList)}}</h5>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <img class="svg" src="{{ asset('storage/images/dashboard-icon/inject.png') }}" alt="dfno">
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('requisitions.officerActiveMDR',[1]) }}">
                        <div class="dash-box-heightIn">
                            <h4 class="subtitle">{{$OfficerName}}'s Active MDR</h4>
                            <div class="row">
                                <div class="col-xs-6">
                                    <h5 class="title color-primary"> {{number_format($countOfficerActiveMDR)}}</h5>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <img class="svg" src="{{ asset('storage/images/dashboard-icon/inject.png') }}" alt="dfno">
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
    </div>

     <!-- Modal for problem entry start-->
        @include('common_pages.common_modal',['modalTitle'=>'Application for Market Development Representative', 'modalSize'=>'modal-lg'])
         <!-- Modal for problem entry end-->
</div>
<script>
        function showModal(serial,){
            laravelObj.common=serial;
            var modalBody=$('#modal-body');
            modalBody.css('padding-top',0);
            modalBody.html('');
            $.ajax({
                type: 'Get',
                url:"{{ route('ajax.applications.getMDRApplication') }}",
                data:{serial:serial}
            }).done(function(response) {
                 modalBody.html(response);
                 $.fn.select2.defaults.set( "theme", "bootstrap" );
                 $(".select2").select2({
                    // placeholder: function(){
                    //     $(this).data('placeholder');
                    // },
                    allowClear: true
                });
            }).fail(function(response) {
                console.log(response);
            });
            $('#common-modal').modal('show');
        };
</script>