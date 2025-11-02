<li class=" has-child-item{{ check_menu_active($current_location,config('myconfig.menu.merchandiser')) }}">
    <a><i class="fa fa-wpforms" aria-hidden="true"></i><span>Merchandiser Module</span></a>
     <ul class="nav child-nav level-1">

        <!-- Application Managements start-->
        @if(isMenuRender(['MerchandisersController@create','MerchandisersController@index'],$menu_list))
            <li class="has-child-item{{ check_menu_active($current_location,['MerchandisersController']) }}">
                <a><span>Manage Application</span></a>
                 <ul class="nav child-nav level-2">

                    @if(isMenuRender('MerchandisersController@create',$menu_list))
                        <li @if($current_location=='MerchandisersController@create') class="active-item" @endif><a href="{{ route('merchandisers.create',[]) }}">Apply Application</a></li>
                    @endif
                    @if(isMenuRender('MerchandisersController@index',$menu_list))
                        <li @if($current_location=='MerchandisersController@index') class="active-item" @endif><a href="{{ route('merchandisers.index',[]) }}">Application Lists</a></li>
                    @endif
                </ul>
            </li>
        @endif
        <!-- Application Managements end-->

        <!-- MDR Attendance System start-->
        @if(isMenuRender(['MerchandisersController@create','MerchandisersController@index', 'MerchandisersController@download'],$menu_list))
            <li class="has-child-item{{ check_menu_active($current_location,['MerchandisersController']) }}">
                <a><span>Merchandiser Attendance</span></a>
                 <ul class="nav child-nav level-2">

                    @if(isMenuRender('MerchandisersController@createMerchandiser',$menu_list))
                        <li @if($current_location=='MerchandisersController@createMerchandiser') class="active-item" @endif><a href="{{ route('merchandiserattendances.createMerchandiser',[]) }}">Merchandiser Attendance Entry</a></li>
                    @endif
                    @if(isMenuRender('MerchandisersController@index',$menu_list))
                        <li @if($current_location=='MerchandisersController@index') class="active-item" @endif><a href="{{ route('merchandiserattendances.index',[]) }}">Attendance View</a></li>
                    @endif
                    @if(isMenuRender('MerchandisersController@AttendanceList',$menu_list))
                        <li @if($current_location=='MerchandisersController@AttendanceList') class="active-item" @endif><a href="{{ route('merchandiserattendances.attendanceList',[]) }}">Attendance List</a></li>
                    @endif
                    @if(isMenuRender('MerchandisersController@download',$menu_list))
                        <li @if($current_location=='MerchandisersController@download') class="active-item" @endif><a href="{{ route('merchandiserattendances.download',[]) }}">Download Attendance</a></li>
                    @endif
                    @if(isMenuRender('MerchandisersController@downloadTopSheet',$menu_list))
                        <li @if($current_location=='MerchandisersController@downloadTopSheet') class="active-item" @endif><a href="{{ route('merchandiserattendances.downloadTopSheet',[]) }}">Attendance-Top Sheet</a></li>
                    @endif
                    
                </ul>
            </li>
        @endif
        <!-- MDR Attendance System end-->
    </ul>
</li>
