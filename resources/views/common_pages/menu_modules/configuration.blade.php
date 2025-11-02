<li class=" has-child-item{{ check_menu_active($current_location,config('myconfig.menu.configaration')) }}">
    <a><i class="fa fa-sitemap" aria-hidden="true"></i><span>Configuration</span></a>
     <ul class="nav child-nav level-1">

        <!-- Designation Managements start-->
        @if(isMenuRender(['DesignationsController@create','DesignationsController@index'],$menu_list))
            <li class="has-child-item{{ check_menu_active($current_location,['DesignationsController']) }}">
                <a><span>Designation Setup</span></a>
                 <ul class="nav child-nav level-2">

                    @if(isMenuRender('DesignationsController@create',$menu_list))
                        <li @if($current_location=='DesignationsController@create') class="active-item" @endif><a href="{{ route('designations.create',[]) }}">Add Designation</a></li>
                    @endif
                    @if(isMenuRender('DesignationsController@index',$menu_list))
                        <li @if($current_location=='DesignationsController@index') class="active-item" @endif><a href="{{ route('designations.index',[]) }}">Designation Lists</a></li>
                    @endif
                </ul>
            </li>
        @endif
        <!-- Designation Managements end-->

        <!-- Department Managements start-->
        @if(isMenuRender(['DepartmentsController@create','DepartmentsController@index'],$menu_list))
            <li class="has-child-item{{ check_menu_active($current_location,['DepartmentsController']) }}">
                <a><span>Department Setup</span></a>
                 <ul class="nav child-nav level-2">

                    @if(isMenuRender('DepartmentsController@create',$menu_list))
                        <li @if($current_location=='DepartmentsController@create') class="active-item" @endif><a href="{{ route('departments.create',[]) }}">Add Department</a></li>
                    @endif
                    @if(isMenuRender('DepartmentsController@index',$menu_list))
                        <li @if($current_location=='DepartmentsController@index') class="active-item" @endif><a href="{{ route('departments.index',[]) }}">Department Lists</a></li>
                    @endif
                </ul>
            </li>
        @endif
        <!-- Department Managements end-->

        <!-- Section Managements start-->
        @if(isMenuRender(['SectionsController@create','SectionsController@index'],$menu_list))
            <li class="has-child-item{{ check_menu_active($current_location,['SectionsController']) }}">
                <a><span>Section Setup</span></a>
                 <ul class="nav child-nav level-2">

                    @if(isMenuRender('SectionsController@create',$menu_list))
                        <li @if($current_location=='SectionsController@create') class="active-item" @endif><a href="{{ route('sections.create',[]) }}">Add Section</a></li>
                    @endif
                    @if(isMenuRender('SectionsController@index',$menu_list))
                        <li @if($current_location=='SectionsController@index') class="active-item" @endif><a href="{{ route('sections.index',[]) }}">Section Lists</a></li>
                    @endif
                </ul>
            </li>
        @endif
        <!-- Section Managements end-->

         <!-- Employee Managements start-->
        @if(isMenuRender(['EmployeesController@create','EmployeesController@index'],$menu_list))
            <li class="has-child-item{{ check_menu_active($current_location,['EmployeesController']) }}">
                <a><span>Employee Setup</span></a>
                 <ul class="nav child-nav level-2">

                    @if(isMenuRender('EmployeesController@create',$menu_list))
                        <li @if($current_location=='EmployeesController@create') class="active-item" @endif><a href="{{ route('employees.create',[]) }}">Add Employee</a></li>
                    @endif
                    @if(isMenuRender('EmployeesController@index',$menu_list))
                        <li @if($current_location=='EmployeesController@index') class="active-item" @endif><a href="{{ route('employees.index',[]) }}">Employee Lists</a></li>
                    @endif
                    @if(isMenuRender('EmployeesController@uploadEmployee',$menu_list))
                        <li @if($current_location=='EmployeesController@uploadEmployee') class="active-item" @endif><a href="{{ route('employees.uploads',[]) }}">Employee Upload</a></li>
                    @endif
                </ul>
            </li>
        @endif
        <!-- Employee Managements end-->

        <!-- Office Locations Managements start-->
        @if(isMenuRender(['OfficeLocationsController@create','OfficeLocationsController@index'],$menu_list))
            <li class="has-child-item{{ check_menu_active($current_location,['OfficeLocationsController']) }}">
                <a><span>Office Location Setup</span></a>
                 <ul class="nav child-nav level-2">

                    @if(isMenuRender('OfficeLocationsController@create',$menu_list))
                        <li @if($current_location=='OfficeLocationsController@create') class="active-item" @endif><a href="{{ route('officelocations.create',[]) }}">Add Office Location</a></li>
                    @endif
                    @if(isMenuRender('OfficeLocationsController@index',$menu_list))
                        <li @if($current_location=='OfficeLocationsController@index') class="active-item" @endif><a href="{{ route('officelocations.index',[]) }}">Office location Lists</a></li>
                    @endif
                </ul>
            </li>
        @endif
        <!-- Office locations Managements end-->

        <!-- Distributor Managements start-->
        @if(isMenuRender(['DistributorsController@create','DistributorsController@index'],$menu_list))
            <li class="has-child-item{{ check_menu_active($current_location,['DistributorsController']) }}">
                <a><span>Distributor Setup</span></a>
                 <ul class="nav child-nav level-2">

                    @if(isMenuRender('DistributorsController@create',$menu_list))
                        <li @if($current_location=='DistributorsController@create') class="active-item" @endif><a href="{{ route('distributors.create',[]) }}">Add Distributor</a></li>
                    @endif
                    @if(isMenuRender('DistributorsController@index',$menu_list))
                        <li @if($current_location=='DistributorsController@index') class="active-item" @endif><a href="{{ route('distributors.index',[]) }}">Distributor Lists</a></li>
                    @endif
                </ul>
            </li>
        @endif
        <!-- Distributor Managements end-->

        <!-- Month Entry start-->
        @if(isMenuRender(['HolidaysController@create','HolidaysController@index'],$menu_list))
            <li class="has-child-item{{ check_menu_active($current_location,['HolidaysController']) }}">
                <a><span>Holiday Setup</span></a>
                 <ul class="nav child-nav level-2">

                    @if(isMenuRender('HolidaysController@create',$menu_list))
                        <li @if($current_location=='HolidaysController@create') class="active-item" @endif><a href="{{ route('holidays.create',[]) }}">Add Monthly Holiday</a></li>
                    @endif
                    @if(isMenuRender('HolidaysController@index',$menu_list))
                        <li @if($current_location=='HolidaysController@index') class="active-item" @endif><a href="{{ route('holidays.index',[]) }}">Monthly Holiday Lists</a></li>
                    @endif
                </ul>
            </li>
        @endif
        <!-- Month Entry End-->

    </ul>
</li>
