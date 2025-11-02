<?php
/*====================Ajax part start==============*/
Route::group(['middleware' => 'auth'], function () {
	Route::get('get-stocks', 'AjaxController@getStocks')->name('ajax.stocks.get');
	Route::get('get-stock-details/{param}', 'AjaxController@getStockDetails')->name('ajax.stocks.details');
	Route::get('get-allocation-details/{param}', 'AjaxController@getAllocationDetails')->name('ajax.allocation.details');
	Route::get('allocation-receive/{param}', 'AjaxController@depotStockAccept')->name('ajax.allocation.receive');
	Route::get('get-allocations', 'AjaxController@getAllocations')->name('ajax.allocation.index');
	Route::get('get-depot-allocations/{stockId?}', 'AjaxController@getDepotAllocations')->name('ajax.depotAllocation.index');
	Route::get('get-items/{param}', 'AjaxController@getItems')->name('ajax.items.index');

	Route::get("stock-transfer-show/{stock_transfer_id}", array(
		'uses' => 'AjaxController@stockTransferShow',
		'as' => 'inventories.stockTransferShow',
	));

	Route::get("stock-transfer-edit/{from_depot}/{transfer_id}", array(
		'uses' => 'AjaxController@stockTransferEdit',
		'as' => 'inventories.stockTransferEdit',
	));
	Route::get("get-df-code-lists", array(
		'uses' => 'AjaxController@dfcodeLists',
		'as' => 'ajax.inventories.dfcodeLists',
	));
});
/*====================Ajax part end==============*/

/*====================Permission part start==============*/
Route::group(['middleware' => ['auth', 'auth.access']], function () {
	

   


});
/*====================Permission part end==============*/

?>