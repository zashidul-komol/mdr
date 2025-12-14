<?php

return [
	'status' => [
		'active' => 'Active',
		'inactive' => 'Inactive',
	],
	'rating' => [
		'1' => '1',
		'2' => '2',
		'3' => '3',
		'4' => '4',
		'5' => '5',
	],
	'appearance' => [
		'Appearance-1' => 'Appearance-1',
		'Appearance-2' => 'Appearance-2',
		'Appearance-3' => 'Appearance-3',
		'Appearance-4' => 'Appearance-4',
		'Appearance-5' => 'Appearance-5',
	],
	'availability' => [
		'yes' => 'Yes',
		'no' => 'No',
	],
	'menu' => [
	    'configaration' => ['SiteSettingsController', 'LocationsController', 'ZonesController', 'DesignationsController' , 'DepartmentsController', 'OfficeLocationsController', 'RegionsController','StagingsController', 'SectionsController', 'VehiclesController', 'MachinesController', 'EmployeesController', 'CategoriesController', 'SubcategoriesController', 'ProductsController', 'MeasurementsController'],
		'user' => ['RolesController', 'RegisterController', 'DistributorsController'],
		'merchandiser' => ['MerchandisersController'],
	    'requisition' => ['RequisitionsController', 'ReportingsequencesController','TadaReportingsequencesController', 'MDRAttendancesController'],
		'report' => ['InventoryReportsController', 'ServiceReportsController'],
		
	],
	'payment_modes' => [
		'without_rent' => 'Without Rent',
		'concession' => 'Concession',
		'full_paid' => 'Full Paid',
	],
	'shop_category' => [
		'a' => 'A',
		'b' => 'B',
		'c' => 'C',
		'd' => 'D',
	],
	'Product_tags' => [
		'machines' => 'Machine',
		'vehicles' => 'Vehicle',
		'employees' => 'Employee',
		'staionaries' => 'Stationery',
	],
	'freeze_service_status' => [
	],
	'application_type' => [
		'requisition' => 'Requisition',
		'return' => 'Return',
		'transfer' => 'Transfer',
		'service' => 'Service',
	],
	'application_status' => [
		'draft' => 'Draft',
		'new' => 'New',
		'pending' => 'Pending',
		'on_hold' => 'On Hold',
		'processing' => 'Processing',
		'approved' => 'Approved',
		'completed' => 'Completed',
		'cancelled' => 'Cancelled',
	],
	'freeze_status' => [
		'fresh' => 'Fresh',
		'beta' => 'Beta',
		'old' => 'Old',
		'damage' => 'Damage',
		'disposed' => 'Disposed',
	],
	'boolArr' => [
		'0' => 'No',
		'1' => 'Yes',
	],
	'staging' => [
		'requisition' => [
			'hold',
			'approve',
			'cancel',
			'validate',
		],
		'return' => [
			'hold',
			'approve',
			'cancel',
		],
		'damage_application' => [
			'hold',
			'approve',
		],
	],
	'payment_methods' => [
		'bkash' => 'bKash',
		'bank' => 'Bank',
		'cash' => 'Cash',
	],
	'requisition_file' => [
		'money_receipt',
		'deed_paper',
	],
	'shop_file' => [
		'proprietor_picture',
		'trade_license_copy',
		'nid_copy',
	],
	'other_company_df' => [
		'Igloo' => 'Igloo',
		'Lovello' => 'Lovello',
		'Bellissimo' => 'Bellissimo',
		'Kwality' => 'Kwality',
		'Bloop' => 'Bloop',
		'Savoy' => 'Savoy',
		'Others' => 'Others',
	],
    'promotional_sms_group' => [
        'sales_team'=>'sales_team',
        'distributors' => 'distributors',
        'outlets' => 'outlets'
    ]
]
?>