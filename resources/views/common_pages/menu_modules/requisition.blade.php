<li class=" has-child-item{{ check_menu_active($current_location,config('myconfig.menu.requisition')) }}">
    <a><i class="fa fa-wpforms" aria-hidden="true"></i><span>Application Module</span></a>
     <ul class="nav child-nav level-1">
        <!-- Reporting Sequence Managements start-->
        @if(isMenuRender(['ReportingsequencesController@create','ReportingsequencesController@index'],$menu_list))
            <li class="has-child-item{{ check_menu_active($current_location,['ReportingsequencesController']) }}">
                <a><span>Reporting Sequence</span></a>
                 <ul class="nav child-nav level-2">

                    @if(isMenuRender('ReportingsequencesController@create',$menu_list))
                        <li @if($current_location=='ReportingsequencesController@create') class="active-item" @endif><a href="{{ route('reportingsequences.create',[]) }}">Add Reporting</a></li>
                    @endif
                    @if(isMenuRender('ReportingsequencesController@index',$menu_list))
                        <li @if($current_location=='ReportingsequencesController@index') class="active-item" @endif><a href="{{ route('reportingsequences.index',[]) }}">Reporting Lists</a></li>
                    @endif
                </ul>
            </li>
        @endif
        <!-- Reporting Sequence Managements end-->

        <!-- Reporting Sequence TA/DA start-->
        @if(isMenuRender(['TadaReportingsequencesController@create','TadaReportingsequencesController@index'],$menu_list))
            <li class="has-child-item{{ check_menu_active($current_location,['TadaReportingsequencesController']) }}">
                <a><span>TA/DA Reporting</span></a>
                 <ul class="nav child-nav level-2">

                    @if(isMenuRender('TadaReportingsequencesController@create',$menu_list))
                        <li @if($current_location=='TadaReportingsequencesController@create') class="active-item" @endif><a href="{{ route('tada_reportingsequences.create',[]) }}">Add TA/DA Reporting</a></li>
                    @endif
                    @if(isMenuRender('TadaReportingsequencesController@index',$menu_list))
                        <li @if($current_location=='TadaReportingsequencesController@index') class="active-item" @endif><a href="{{ route('tada_reportingsequences.index',[]) }}">TA/DA Reporting Lists</a></li>
                    @endif
                </ul>
            </li>
        @endif
        <!-- Reporting Sequence TA/DA end-->

        <!-- Application Managements start-->
        @if(isMenuRender(['RequisitionsController@create','RequisitionsController@index'],$menu_list))
            <li class="has-child-item{{ check_menu_active($current_location,['RequisitionsController']) }}">
                <a><span>Manage Application</span></a>
                 <ul class="nav child-nav level-2">

                    @if(isMenuRender('RequisitionsController@create',$menu_list))
                        <li @if($current_location=='RequisitionsController@create') class="active-item" @endif><a href="{{ route('requisitions.create',[]) }}">Apply Application</a></li>
                    @endif
                    @if(isMenuRender('RequisitionsController@index',$menu_list))
                        <li @if($current_location=='RequisitionsController@index') class="active-item" @endif><a href="{{ route('requisitions.index',[]) }}">Application Lists</a></li>
                    @endif
                </ul>
            </li>
        @endif
        <!-- Application Managements end-->

        <!-- MDR Attendance System start-->
        @if(isMenuRender(['MDRAttendancesController@create','MDRAttendancesController@index', 'MDRAttendancesController@download'],$menu_list))
            <li class="has-child-item{{ check_menu_active($current_location,['MDRAttendancesController']) }}">
                <a><span>MDR Attendance</span></a>
                 <ul class="nav child-nav level-2">

                    @if(isMenuRender('MDRAttendancesController@create',$menu_list))
                        <li @if($current_location=='MDRAttendancesController@create') class="active-item" @endif><a href="{{ route('mdrattendances.create',[]) }}">Active MDR Attendance Entry</a></li>
                    @endif
                    @if(isMenuRender('MDRAttendancesController@inactiveMDRcreate',$menu_list))
                        <li @if($current_location=='MDRAttendancesController@inactiveMDRcreate') class="active-item" @endif><a href="{{ route('mdrattendances.inactiveMDRcreate',[]) }}">Inactive MDR Attendance Entry</a></li>
                    @endif
                    @if(isMenuRender('MDRAttendancesController@index',$menu_list))
                        <li @if($current_location=='MDRAttendancesController@index') class="active-item" @endif><a href="{{ route('mdrattendances.index',[]) }}">Attendance View</a></li>
                    @endif
                    @if(isMenuRender('MDRAttendancesController@AttendanceList',$menu_list))
                        <li @if($current_location=='MDRAttendancesController@AttendanceList') class="active-item" @endif><a href="{{ route('mdrattendances.attendanceList',[]) }}">Attendance List</a></li>
                    @endif
                    @if(isMenuRender('MDRAttendancesController@download',$menu_list))
                        <li @if($current_location=='MDRAttendancesController@download') class="active-item" @endif><a href="{{ route('mdrattendances.download',[]) }}">Download Attendance</a></li>
                    @endif
                    @if(isMenuRender('MDRAttendancesController@downloadTopSheet',$menu_list))
                        <li @if($current_location=='MDRAttendancesController@downloadTopSheet') class="active-item" @endif><a href="{{ route('mdrattendances.downloadTopSheet',[]) }}">Attendance-Top Sheet</a></li>
                    @endif
                    
                </ul>
            </li>
        @endif
        <!-- MDR Attendance System end-->
    </ul>
</li>
