<?php


Route::group(['middleware' => 'web'], function() {

    
    
    Route::group(['middleware' => ['language']], function() {
        
        Route::group(['middleware' => ['webcart']], function() {
            
            Route::get('/thank-you', 'RazorpayController@thankYou')->name('thank-you');

             
                    // Authentication Routes
                    Route::auth();
                     
                    

                    // Manage Routes
                    Route::group(['middleware' => ['auth', 'manage'], 'as' => 'manage.', 'prefix' => 'manage'], function () {

                        // Manage Index Route
                        Route::get('/', 'ManageController@index')->name('index');
                        Route::post('/', 'ManageController@index')->name('index');

                        // Manage Users Routes
                        Route::resource('/users', 'ManageUsersController', ['except' => [
                            'show', 'destroy'
                        ]]);
                        Route::get('/users/{id}', 'ManageUsersController@deleteUser')->name('users.deleteuser');
                        // Manage Categories Routes
                        Route::resource('/categories', 'ManageCategoriesController', ['except' => [
                         'show'
                        ]]);
                        Route::delete('/delete/categories', 'ManageCategoriesController@deleteCategories');

                        // Manage Brands Routes
                        Route::resource('/brands', 'ManageBrandsController', ['except' => [
                        'create', 'show'
                        ]]);
                        Route::delete('/delete/brands', 'ManageBrandsController@deleteBrands');

                        // Manage Pages Routes
                        Route::resource('/pages', 'ManagePagesController', ['except' => [
                            'show'
                        ]]);
                        Route::delete('/delete/pages', 'ManagePagesController@deletePages');
                        Route::get('/page/schemes', 'ManagePagesController@Schemes')->name('pages.schemes');
                        Route::post('/page/schemes/savescheme', 'ManagePagesController@saveScheme')->name('pages.schemes.savescheme');
                        Route::patch('/page/schemes/updatescheme/{id}', 'ManagePagesController@updateScheme')->name('pages.schemes.updatescheme');


                        Route::get('/page/certifications', 'ManagePagesController@Certifications')->name('pages.certifications');
                        Route::post('/page/certifications/savecertifications', 'ManagePagesController@saveCertifications')->name('pages.certifications.savecertifications');
                        Route::patch('/page/schemes/updatecertifications/{id}', 'ManagePagesController@updateCertifications')->name('pages.certifications.updatecertifications');
                        
                        // Manage Testimonials Routes
                        Route::resource('/testimonials', 'ManageTestimonialsController', ['except' => [
                            'show'
                        ]]);
                        Route::delete('/delete/testimonials', 'ManageTestimonialsController@deleteTestimonials');

                        // Manage Products Routes
                        Route::resource('/products', 'ManageProductsController', ['except' => [
                            'show'
                        ]]);
                        Route:: get('/vendor_product','ManageProductsController@vendorProduct')->name('products.vendor_product');
                        Route:: get('/vendor_product/edit/{id}','ManageProductsController@vendorProductEdit')->name('products.vendor_product_edit');
                        Route:: patch('/update_vendor_product/{id}','ManageProductsController@updateVendorProduct');
                        Route:: get('/delete_vendor_product/{id}','ManageProductsController@deleteVendorProduct')->name('products.delete_vendor_product');
                        Route:: post('/approve/approved_products', 'ManageProductsController@approvedAllProducts');
                        // Manage Products Routes
                        Route::get('/products/getsize', 'ManageProductsController@getSize')->name('products.getsize');
                        Route::get('/products/saveGroup', 'ManageProductsController@saveGroup')->name('products.saveGroup');


                        Route::get('/products/getsizegroup', 'ManageProductsController@getSizeGroup')->name('products.getsizegroup');

                        Route::get('/products/{id}','ManageProductsController@destroy')->name('products.destroy');
                        Route::delete('/delete/products', 'ManageProductsController@deleteProducts');
                        
                        Route::patch('/more-images/product/{id}', 'ManageProductsController@storeMoreImages');
                        Route::get('/existing-product/{id}', 'ManageProductsController@getExistingProduct');

                        Route::post('/products/upload_multiple_image', 'ManageProductsController@uploadMultipleImage')->name('products.upload_multiple_image');

                        Route::get('/getPrority/{id}','ManageProductsController@getPrority')->name('getPrority');
                        Route::get('/getProrityRate/{id}','ManageProductsController@getProrityRate')->name('getProrityRate');
                        Route::get('/getPlatinumProrityRate/{id}','ManageProductsController@getPlatinumProrityRate')->name('getPlatinumProrityRate');
                        Route::get('/styleajax', 'ManageProductsController@getStyleAjax')->name('styleajax');
                        Route::get('/checkproductwebid', 'ManageProductsController@getCheckProductJNWEBIDExist')->name('checkproductwebid');
                        Route::get('/checkvendorproductwebid', 'ManageProductsController@getCheckVendorProductJNWEBIDExist')->name('checkvendorproductwebid');
                        
                        // Manage Vendors Routes
                        Route::get('/vendor/dashboard', 'ManageVendorsController@dashboard')->name('vendor.dashboard');
                        Route::get('/vendor/payments', 'ManageVendorsController@payments')->name('vendor.payments');
                        Route::resource('/vendors', 'ManageVendorsController');
                        Route::get('/vendor/vendors_request', 'ManageVendorsController@vendorRequest')->name('vendor.vendor_requests');
                        Route::delete('/delete/vendors', 'ManageVendorsController@deleteVendors');
                        Route::get('/delete_vendor/{id}', 'ManageVendorsController@destroy')->name('vendors.delete_vendor');
                        Route::post('/vendor/payment', 'ManageVendorsController@vendorPayment')->name('vendor.payment');
                        Route::post('/vendor/payment/update-status', 'ManageVendorsController@updatePaymentStatus')->name('vendor.updatePaymentStatus');

                        Route::get('/vendor/payment/paypal/return', 'ManageVendorsController@vendorPaymentPaypalReturn')->name('vendor.payment.paypal.return');
                        Route::get('/vendor/payment/paypal/cancel', 'ManageVendorsController@vendorPaymentPaypalCancel')->name('vendor.payment.paypal.cancel');

                        Route::post('/vendor/submit-payout-request', 'ManageVendorsController@submitPayoutRequest')->name('vendor.submit-payout-request');
                        Route::post('/vendor/settings/payments', 'ManageVendorsController@updatePaymentSettings')->name('vendor.settings.payments');
                        
                         // Manage Number of Product Routes
                         Route::resource('/number_of_products', 'ManageNumberOfProductsController', ['except' => [
                            'create', 'show'
                            ]]);

                        // Manage Specifications Routes
                        Route::resource('/specification-types', 'ManageSpecificationTypesController', ['except' => [
                        'create', 'show'
                        ]]);
                        Route::delete('/delete/specifications-types', 'ManageSpecificationTypesController@deleteSpecificationTypes');

                        Route::get('/export_category_csv', 'ManageNumberOfProductsController@exportCategoryCSV')->name('number_of_products.export_category_csv');
                        Route::get('/export_style_csv', 'ManageNumberOfProductsController@exportStyleCSV')->name('number_of_products.export_style_csv');


                        // Manage Comparision Group Route
                        Route::resource('/comparision-group', 'ManageComparisionGroupsController');
                        Route::delete('/delete/comparision-group', 'ManageComparisionGroupsController@deleteComparisionGroups');
                        // Manage Sales Route
                        Route::get('/sales', 'ManageProductsController@sales')->name('products.sales');

                        // Manage Orders Routes
                        Route::resource('/orders', 'ManageOrdersController', ['except' => [
                        'create', 'store'
                        ]]);

                        Route::get('/destroy/{id}', 'ManageOrdersController@destroy')->name('orders.destroy');
                        Route::delete('/delete/orders', 'ManageOrdersController@deleteOrders');

                        // Manage Invoices Route
                        Route::get('/invoices', 'ManageOrdersController@invoices')->name('orders.invoices');

                        // Manage Pending Orders Route
                        Route::get('/pending-orders', 'ManageOrdersController@pending')->name('orders.pending');

                        // Manage Roles Routes
                        Route::resource('/roles', 'ManageRolesController', ['except' => [
                            'show'
                        ]]);
                        Route::delete('/delete/roles', 'ManageRolesController@deleteRoles');
                        
                        Route::resource('/templates', 'ManageTemplateController');

                        // Manage Customers Routes
                        Route::resource('/customers', 'ManageCustomersController', ['except' => [
                        'create', 'store', 'show'
                        ]]);
                        Route::delete('/delete/customers', 'ManageCustomersController@deleteCustomers');

                        Route::get('/customers/promote_vendor/{id}', 'ManageCustomersController@promoteCustomer')->name('customers.promote');

                        // Manage Customers Addresses Routes
                        Route::resource('/customer-address', 'ManageAddressesController', ['only' => [
                            'edit', 'update', 'destroy'
                        ]]);

                        Route::get('/customer/{id}/orders', 'ManageCustomersController@viewUserOrders')->name('customer.orders');

                        // Manage Coupons Routes
                        Route::resource('/coupons', 'ManageCouponsController', ['except' => [
                            'show'
                        ]]);
                        Route::delete('/delete/coupons', 'ManageCouponsController@deleteCoupons');

                        // Manage Deals Routes
                        Route::resource('/deals', 'ManageDealsController', ['except' => [
                            'show'
                        ]]);
                        Route::delete('/delete/deals', 'ManageDealsController@deleteDeals');

                        // Manage Banners Routes
                        Route::resource('/banners', 'ManageBannersController', ['except' => [
                            'show'
                        ]]);
                        Route::delete('/delete/banners', 'ManageBannersController@deleteBanners');

                        // Manage Sections Routes
                        Route::resource('/sections', 'ManageSectionsController', ['except' => [
                            'show'
                        ]]);
                        Route::delete('/delete/sections', 'ManageSectionsController@deleteSections');

                        // Manage Product Discounts Routes
                        Route::resource('/product-discounts', 'ManageProductDiscountsController', ['except' => [
                        'show'
                        ]]);
                        Route::delete('/delete/product-discounts', 'ManageProductDiscountsController@deleteProductDiscounts');

                        // Manage Product Discounts Routes
                        Route::resource('/collection', 'CollectionController', ['except' => [
                            'show'
                        ]]);
                        
                        Route::resource('/home_banner', 'ManageHomeBannerController', ['except' => [
                            'show'
                        ]]);
                        

                        // Manage Product Silver Item Routes
                        Route::resource('/silveritem', 'ManageSilverItemController', ['except' => [
                            'show'
                        ]]);
                        Route::get('/delete_silver_item/{id}', 'ManageSilverItemController@destroy')->name('silveritem.delete_silver_item');
                        Route::delete('/bulk_silver_item_delete', 'ManageSilverItemController@bulkSilverItemDelete');

                        // Manage Reviews Routes
                        Route::resource('/reviews', 'ManageReviewsController', ['except' => [
                        'create', 'store', 'update'
                        ]]);
                        Route::post('/set-status/reviews', 'ManageReviewsController@setReviewsStatus');

                        // Manage Settings Routes
                        Route::get('/settings', 'ManageSettingsController@index')->name('settings.index');
                        Route::get('/enquires', 'ManageSettingsController@Enquires')->name('settings.enquires');
                        Route::get('/view_enquiry/{id}', 'ManageSettingsController@viewEnquires')->name('settings.view_enquiry');
                        Route::get('/delete_enquiry/{id}', 'ManageSettingsController@deleteEnquires')->name('settings.delete_enquiry');
                        Route::delete('/bulk_enquiry_delete', 'ManageSettingsController@bulkEnquiryDelete');
                        
                        Route::patch('/settings/update-store', 'ManageSettingsController@updateStore')->name('settings.updateStore');
                        Route::patch('/settings/update-theme', 'ManageSettingsController@updateTheme')->name('settings.updateTheme');
                        Route::patch('/settings/update-live-chat', 'ManageSettingsController@updateLiveChat')->name('settings.updateLiveChat');
                        Route::patch('/settings/update-tax-shipping', 'ManageSettingsController@updateTaxShipping')->name('settings.updateTaxShipping');
                        Route::patch('/settings/update-vendor', 'ManageSettingsController@updateVendor')->name('settings.updateVendor');
                        Route::patch('/settings/update-admin-panel', 'ManageSettingsController@updateAdminPanel')->name('settings.updateAdminPanel');
                        Route::patch('/settings/update-google-recaptcha', 'ManageSettingsController@updateRecaptcha')->name('settings.updateRecaptcha');
                        Route::patch('/settings/update-google-map', 'ManageSettingsController@updateGoogleMap')->name('settings.updateGoogleMap');
                        Route::patch('/settings/update-google-analytics', 'ManageSettingsController@updateGoogleAnalytics')->name('settings.updateGoogleAnalytics');
                        Route::patch('/settings/update-site-map', 'ManageSettingsController@updateSiteMap')->name('settings.updateSiteMap');
                        Route::patch('/settings/update-referral', 'ManageSettingsController@updateReferral')->name('settings.updateReferral');
                        Route::get('/settings/profile', 'ManageSettingsController@profile')->name('settings.profile');
                        Route::patch('/settings/update-profile', 'ManageSettingsController@updateProfile')->name('settings.updateProfile');
                        Route::get('/settings/price-setting', 'ManageSettingsController@priceSetting')->name('settings.pricesetting');
                        Route::patch('/settings/update-price-setting', 'ManageSettingsController@updatePriceSetting')->name('settings.updatepricesetting');
                        Route::get('/settings/shop-by-metal-stone', 'ManageSettingsController@shopByMetalStone')->name('settings.shop_by_metal_stone');
                        Route::get('/settings/create_shopbymetal', 'ManageSettingsController@createShopByMetal')->name('settings.create_shop_by_metal');
                        Route::post('/settings/store_shop_by_metal', 'ManageSettingsController@storeShopByMetalStone')->name('settings.store_shop_by_metal');
                        Route::get('/settings/edit_shop_by_metal_stone/{id}', 'ManageSettingsController@editShopByMetalStone')->name('settings.edit_shop_by_metal_stone');
                        Route::put('/settings/update_shop_by_metal', 'ManageSettingsController@updateShopByMetal')->name('settings.update_shop_by_metal');
                        Route::get('/settings/delete_shop_by_metal/{id}', 'ManageSettingsController@deleteShopByMetal')->name('settings.delete_shop_by_metal');
                        Route::delete('/settings/bulk_shop_by_metal_delete', 'ManageSettingsController@bulkShopByMetalDelete');
                        // Manage Template Settings Routes
                        Route::get('/settings/template', 'ManageAppSettingsController@template')->name('settings.template');
                        Route::patch('/settings/update-template', 'ManageAppSettingsController@updateTemplate')->name('settings.updateTemplate');

                        // Manage Certificate Settings Routes
                        Route::get('/settings/certificate', 'ManageSettingsController@certificate')->name('settings.certificate');
                        Route::get('/settings/create_certificate', 'ManageSettingsController@createCertificate')->name('settings.create_certificate');
                        Route::post('/settings/store_certificate', 'ManageSettingsController@storeCertificate')->name('settings.store_certificate');
                        Route::get('/settings/edit_certificate/{id}', 'ManageSettingsController@editCertificate')->name('settings.edit_certificate');
                        Route::put('/settings/update_certificate', 'ManageSettingsController@updateCertificate')->name('settings.update_certificate');
                        Route::get('/settings/delete_certificate/{id}', 'ManageSettingsController@deleteCertifcate')->name('settings.delete_certificate');
                        Route::delete('/settings/bulk_delete', 'ManageSettingsController@bulkCertificateDelete');

                        // Manage Metal Settings Routes
                        Route::get('/settings/metal', 'ManageSettingsController@Metal')->name('settings.metal');
                        Route::get('/settings/create_metal', 'ManageSettingsController@createMetal')->name('settings.create_metal');
                        Route::post('/settings/store_metal', 'ManageSettingsController@storeMetal')->name('settings.store_metal');
                        Route::get('/settings/edit_metal/{id}', 'ManageSettingsController@editMetal')->name('settings.edit_metal');
                        Route::put('/settings/update_metal', 'ManageSettingsController@updateMetal')->name('settings.update_metal');
                        Route::get('/settings/delete_metal/{id}', 'ManageSettingsController@deleteMetal')->name('settings.delete_metal');
                        Route::delete('/settings/bulk_metal_delete', 'ManageSettingsController@bulkMetalDelete');

                        // Manage Metal Purity Settings Routes
                        Route::get('/settings/puirty', 'ManageSettingsController@Metalpuirty')->name('settings.puirty');
                        Route::get('/settings/create_metal_puirty', 'ManageSettingsController@createMetalPuirty')->name('settings.create_metal_puirty');
                        Route::post('/settings/store_puirty', 'ManageSettingsController@storeMetalPuirty')->name('settings.store_puirty');
                        Route::get('/settings/edit_puirty/{id}', 'ManageSettingsController@editMetalPuirty')->name('settings.edit_puirty');
                        Route::put('/settings/update_metal_puirty', 'ManageSettingsController@updateMetalPuirty')->name('settings.update_metal_puirty');
                        
                        Route::get('/settings/delete_metal_puirty/{id}', 'ManageSettingsController@deleteMetalPuirty')->name('settings.delete_metal_puirty');
                        Route::delete('/settings/bulk_metal_puirty_delete', 'ManageSettingsController@bulkMetalPuirtyDelete');

                        // Manage Metal Purity Settings Routes
                        Route::get('/settings/catalog', 'ManageSettingsController@getCatalog')->name('settings.catalog');
                        Route::get('/settings/create_catalog', 'ManageSettingsController@createCatalog')->name('settings.create_catalog');
                        Route::post('/settings/store_catalog', 'ManageSettingsController@storeCatalog')->name('settings.store_catalog');
                        Route::get('/settings/edit_catalog/{id}', 'ManageSettingsController@editCatalog')->name('settings.edit_catalog');
                        Route::put('/settings/update_catalog/{id}', 'ManageSettingsController@updateCatalog')->name('settings.update_catalog');
                        Route::get('/settings/delete_catalog/{id}', 'ManageSettingsController@deleteCatalog')->name('settings.delete_catalog');
                        Route::delete('/settings/bulk_catalog_delete', 'ManageSettingsController@bulkCatalogDelete');
                        

                        // Manage Pincode Settings Routes
                        Route::get('/settings/pincode', 'ManageSettingsController@getPincode')->name('settings.pincode');
                        Route::get('/settings/create_pincode', 'ManageSettingsController@createPincode')->name('settings.create_pincode');
                        Route::post('/settings/store_pincode', 'ManageSettingsController@storePincode')->name('settings.store_pincode');
                        Route::get('/settings/edit_pincode/{id}', 'ManageSettingsController@editPincode')->name('settings.edit_pincode');
                        Route::put('/settings/update_pincode/{id}', 'ManageSettingsController@updatePincode')->name('settings.update_pincode');
                        Route::get('/settings/delete_pincode/{id}', 'ManageSettingsController@deletePincode')->name('settings.delete_pincode');
                        Route::delete('/settings/bulk_pincode_delete', 'ManageSettingsController@bulkPincodeDelete');

                        // Manage Style Settings Routes
                        Route::get('/settings/style', 'ManageSettingsController@getStyle')->name('settings.style');
                        Route::get('/settings/create_style', 'ManageSettingsController@createStyle')->name('settings.create_style');
                        Route::post('/settings/store_style', 'ManageSettingsController@storeStyle')->name('settings.store_style');
                        Route::get('/settings/edit_style/{id}', 'ManageSettingsController@editStyle')->name('settings.edit_style');
                        Route::put('/settings/update_style/{id}', 'ManageSettingsController@updateStyle')->name('settings.update_style');
                        Route::get('/settings/delete_style/{id}', 'ManageSettingsController@deleteStyle')->name('settings.delete_style');
                        Route::delete('/settings/bulk_style_delete', 'ManageSettingsController@bulkStyleDelete');
                        
                        
                        
                        
                        
                        

                        
                        
                        
                        
                        
                        

                        // Manage Payment Settings Routes
                        Route::get('/settings/payment', 'ManageAppSettingsController@payment')->name('settings.payment');
                        Route::patch('/settings/payment-cod', 'ManageAppSettingsController@updatePaymentCOD')->name('payment-settings.updatePaymentCOD');
                        Route::patch('/settings/payment-paypal', 'ManageAppSettingsController@updatePaymentPaypal')->name('payment-settings.updatePaymentPaypal');
                        Route::patch('/settings/payment-stripe', 'ManageAppSettingsController@updatePaymentStripe')->name('payment-settings.updatePaymentStripe');
                        Route::patch('/settings/payment-razorpay', 'ManageAppSettingsController@updatePaymentRazorpay')->name('payment-settings.updatePaymentRazorpay');
                        Route::patch('/settings/payment-instamojo', 'ManageAppSettingsController@updatePaymentInstamojo')->name('payment-settings.updatePaymentInstamojo');
                        Route::patch('/settings/payment-payumoney', 'ManageAppSettingsController@updatePaymentPayUmoney')->name('payment-settings.updatePaymentPayUmoney');
                        Route::patch('/settings/payment-payubiz', 'ManageAppSettingsController@updatePaymentPayUbiz')->name('payment-settings.updatePaymentPayUbiz');
                        Route::patch('/settings/payment-banktransfer', 'ManageAppSettingsController@updatePaymentBankTransfer')->name('payment-settings.updatePaymentBankTransfer');
                        Route::patch('/settings/wallet-use', 'ManageAppSettingsController@updatePaymentWallet')->name('payment-settings.updatePaymentWallet');
                        Route::patch('/settings/cashback', 'ManageAppSettingsController@updatePaymentCashback')->name('payment-settings.updatePaymentCashback');

                        // Manage Delivery Settings Routes
                        Route::get('/settings/delivery', 'ManageAppSettingsController@delivery')->name('settings.delivery');
                        Route::patch('/settings/delivery-template', 'ManageAppSettingsController@updateDeliveryTemplate')->name('settings.updateDeliveryTemplate');
                        Route::patch('/settings/delivery-delhivery', 'ManageAppSettingsController@updateDeliveryDelhivery')->name('delivery-settings.updateDeliveryDelhivery');


                        Route::patch('/settings/payment-paystack', 'ManageAppSettingsController@updatePaymentPaystack')->name('payment-settings.updatePaymentPaystack');
                        Route::patch('/settings/payment-paytm', 'ManageAppSettingsController@updatePaymentPaytm')->name('payment-settings.updatePaymentPaytm');
                        Route::patch('/settings/payment-pesapal', 'ManageAppSettingsController@updatePaymentPesapal')->name('payment-settings.updatePaymentPesapal');

                        // Manage Business Settings Routes
                        Route::get('/settings/business', 'ManageAppSettingsController@business')->name('settings.business');
                        Route::patch('/settings/business', 'ManageAppSettingsController@updateBusiness')->name('settings.updateBusiness');

                        // Manage Email Settings Routes
                        Route::get('/settings/email', 'ManageAppSettingsController@email')->name('settings.email');
                        Route::patch('/settings/email-template', 'ManageAppSettingsController@updateEmailTemplate')->name('settings.updateEmailTemplate');
                        Route::patch('/settings/email-smtp', 'ManageAppSettingsController@updateEmailSmtp')->name('settings.updateEmailSmtp');
                        Route::patch('/settings/email-mailgun', 'ManageAppSettingsController@updateEmailMailgun')->name('settings.updateEmailMailgun');
                        Route::post('/test/email', 'ManageTestController@testEmail')->name('test.email');

                        // Manage SMS Settings Routes
                        Route::get('/settings/sms', 'ManageAppSettingsController@sms')->name('settings.sms');
                        Route::patch('/settings/sms-template', 'ManageAppSettingsController@updateSMSTemplate')->name('settings.updateSMSTemplate');
                        Route::patch('/settings/sms-otp', 'ManageAppSettingsController@updateSMSOtp')->name('settings.updateSMSOtp');
                        Route::patch('/settings/sms-msgclub', 'ManageAppSettingsController@updateSMSMsgClub')->name('settings.updateSMSMsgClub');
                        Route::patch('/settings/sms-pointsms', 'ManageAppSettingsController@updateSMSPointSMS')->name('settings.updateSMSPointSMS');
                        Route::patch('/settings/sms-nexmo', 'ManageAppSettingsController@updateSMSNexmo')->name('settings.updateSMSNexmo');
                        Route::patch('/settings/sms-textlocal', 'ManageAppSettingsController@updateSMSTextlocal')->name('settings.updateSMSTextlocal');
                        Route::patch('/settings/sms-twilio', 'ManageAppSettingsController@updateSMSTwilio')->name('settings.updateSMSTwilio');
                        Route::patch('/settings/sms-ebulk', 'ManageAppSettingsController@updateSMSeBulk')->name('settings.updateSMSeBulk');
                        Route::post('/test/sms', 'ManageTestController@testSMS')->name('test.sms');

                        // Manage CSS Editor Settings Routes
                        Route::get('/settings/css-editor', 'ManageAppSettingsController@cssEditor')->name('settings.css');
                        Route::patch('/settings/store-css', 'ManageAppSettingsController@updateStoreCSS')->name('settings.updateStoreCSS');
                        Route::patch('/settings/admin-css', 'ManageAppSettingsController@updateAdminCSS')->name('settings.updateAdminCSS');
                        Route::patch('/settings/panel-css', 'ManageAppSettingsController@updatePanelCSS')->name('settings.updatePanelCSS');

                        // Manage Subscribers Settings Routes
                        Route::get('/settings/subscribers', 'ManageAppSettingsController@subscribers')->name('settings.subscribers');
                        Route::patch('/settings/subscribers', 'ManageAppSettingsController@updateSubscribers')->name('settings.updateSubscribers');
                        Route::patch('/settings/mailchimp', 'ManageAppSettingsController@updateMailChimp')->name('settings.updateMailChimp');
                        Route::patch('/settings/subscribersDetails', 'ManageAppSettingsController@updateSubsDetails')->name('settings.updateSubsDetails');

                        // Manage Reports Routes
                        Route::get('/reports/product-sales', 'ManageReportsController@product_sales')->name('reports.product_sales');
                        Route::post('/reports/product-sales', 'ManageReportsController@product_sales');

                        // Manage Subscribers Routes
                        Route::get('/subscribers', 'ManageSubscribersController@index')->name('subscribers');
                        Route::post('/subscribers/import', 'ManageSubscribersController@importSubscribers')->name('subscribers.import');
                        Route::delete('/delete/subscribers', 'ManageSubscribersController@deleteSubscribers');

                        // Manage Shipments Routes
                        Route::resource('/shipments', 'ManageShipmentsController', ['except' => [
                        'show'
                        ]]);
                        Route::delete('/delete/shipments', 'ManageShipmentsController@deleteShipments');

                        Route::resource('/delivery-location', 'ManageDeliveryLocationController', ['except' => [
                        'show'
                        ]]);
                        Route::delete('/delete/delivery-locations', 'ManageDeliveryLocationController@deleteDeliveryLocation');

                        // File Download Route
                        Route::get('/download/{filename}', 'ManageProductsController@download')->name('download');

                        // Ajax Routes
                        Route::get('/ajax/user/get-user-data/{user_name}', ['uses'=>'ManageUsersController@getUserData']);
                        Route::get('/ajax/specifications/category/{category_id}', ['uses'=>'ManageCategoriesController@getSpecifications']);
                        Route::get('/settings/enablePaymentMethod/{payment_method}', 'ManageAppSettingsController@enablePaymentMethod')->name('payment-settings.enablePaymentMethod');
                        Route::get('/settings/enableDeliveryMethod/{delivery_method}', 'ManageAppSettingsController@enableDeliveryMethod')->name('delivery-settings.enableDeliveryMethod');
                        
                        

                    });

                    

                    // Front Routes
                    Route::group(['as'=>'front.'], function () {
                       
                        // product filter by price
                        Route::get('/category/{slug}', 'FrontCategoryController@show')->name('category.show');
                        Route::post('/price_filter', 'FrontCategoryController@ajaxFilterPrice')->name('price_filter');
                        Route::post('/search_price_filter', 'FrontController@ajaxFilterPrice')->name('search_price_filter');
                        Route::post('/price_sorting', 'FrontCategoryController@ajaxSortingPrice')->name('price_sorting');
                        Route::post('/search_price_sorting', 'FrontController@ajaxSortingPrice')->name('search_price_sorting');
                        Route::post('/metal_filter', 'FrontCategoryController@ajaxMetalFilter')->name('metal_filter');
                        Route::post('/type_filter', 'FrontCategoryController@ajaxTypeFilter')->name('type_filter');
                        Route::post('/purity_filter', 'FrontCategoryController@ajaxPurityFilter')->name('purity_filter');
                        Route::post('/search_purity_filter', 'FrontController@ajaxPurityFilter')->name('search_purity_filter');
                        Route::post('/gender_filter', 'FrontCategoryController@ajaxGenderFilter')->name('gender_filter');
                        Route::post('/search_gender_filter', 'FrontController@ajaxGenderFilter')->name('search_gender_filter');
                        Route::post('/offer_filter', 'FrontCategoryController@ajaxOfferFilter')->name('offer_filter');
                        Route::post('/search_offer_filter', 'FrontController@ajaxOfferFilter')->name('search_offer_filter');
                        
                        // verify otp
                        Route::post('/verify_otp', 'Auth\RegisterController@verifyOtp')->name('verify_otp');
                        
                        // Front Catalog Route
                    
                        Route::get('/catalog', 'FrontCatalogController@index')->name('catalog');

                        // Front Index Route
                        Route::get('/', 'FrontController@index')->name('index');
                        Route::get('/schemes', 'FrontPageController@Schemes')->name('schemes');
                        Route::get('/lang/{language?}', 'FrontController@index')->name('index');
                        Route::get('/category/{type?}/{slug?}','FrontController@filterMetal')->name('filter_metal');
                        Route::get('/filter/{type}/{min?}/{max?}','FrontController@filterPrice')->name('filter_price');

                        // front customer route

                        Route::get('/customer', 'FrontCustomersController@customer')->name('customers.customer');
                        Route::get('/show/{id}', 'FrontCustomersController@showCustomer')->name('customers.showcustomer');
                        Route::patch('/delete_customer/{id}', 'FrontCustomersController@delete_customer')->name('customers.delete_customer');
                        
                        // Front Products Route
                        Route::get('/products', 'FrontController@products')->name('products');
                        

                        // Front Search Route
                        Route::get('/search', 'FrontController@search')->name('search');
                        Route::get('/search/autocomplete', 'FrontController@autocomplete');

                        // Register Controller Route
                        Route::get('/checkValidReferralCode', 'Auth\RegisterController@checkValidReferralCode')->name('register.checkValidReferralCode');

                        // Front Page Route
                        Route::get('/{slug}', 'FrontPageController@show')->where('slug', '(?>[\w-]+)(?<!cart|contact-us|account-overview|orders|addresses|wallet-history|referrals|laravel-filemanager)')->name('page.show');

                        // Front Product Route
                        Route::get('/product/sizeguide', 'FrontProductController@getRingSizeGuide')->name('product.sizeguide');
                        Route::get('/product/{slug}', 'FrontProductController@show')->name('product.show');
                        
                        Route::post('/product/getproductprice', 'FrontProductController@getProductPrice')->name('product.getproductprice');
                       
                        Route::post('/enquiry', 'FrontProductController@sendEnquiry')->name('enquiry');
                       
                        // Front Brand Route
                        Route::get('/brand/{slug}', 'FrontBrandController@show')->name('brand.show');

                        // Front Category Route
                        

                        // Front Deal Route
                        Route::get('/deal/{slug}', 'FrontDealController@show')->name('deal.show');

                        // Front Vendor Route
                        Route::get('/shop/{slug}', 'FrontVendorController@show')->name('vendor.show');

                        // Front Cart Routes
                        Route::resource('/cart', 'FrontCartController', ['except' => [
                        'create', 'store', 'show', 'edit'
                        ]]);
						Route::get('/becategory/{slug}', 'FrontCartController@becategory')->name('cart.becategory');
						Route::post('/beprice_sorting', 'FrontCartController@beajaxSortingPrice')->name('beprice_sorting');
                        Route::get('/cart/ajax','FrontCartController@ajaxCartData');
                        Route::get('/cart/refreshCartPage','FrontCartController@refreshCartPage');
                        Route::patch('/cart/add/{id}', 'FrontCartController@add')->name('cart.add');
                        Route::patch('/cart/buy_now/{id}', 'FrontCartController@buy_now')->name('cart.buy_now');
                        // Route::patch('/cart/update/{id}/{quantity}', 'FrontCartController@update')->name('cart.update');
                        Route::patch('/cart/update', 'FrontCartController@update')->name('cart.update');
                        Route::get('/cart/empty', 'FrontCartController@emptyCart')->name('cart.emptyCart');
                        Route::get('/cart/cartCount', 'FrontCartController@cartCount')->name('cart.cartCount');

                        // For Ajax Request
                        Route::get('/cart/add/{id}', 'FrontCartController@add')->name('cart.add');
                        Route::get('/cart/update/{id}/{quantity}', 'FrontCartController@updateAjax')->name('cart.updateAjax');

                        // Front Contact Form Routes
                        Route::get('/contact-us', 'FrontContactFormController@contactForm')->name('contact');
                        Route::post('/contact-us', 'FrontContactFormController@sendEmail');
                        Route::get('/ajax/checkShippingAvailability/{pincode}/{id}', ['uses'=>'ManageDeliveryLocationController@checkShippingAvailability']);

                        Route::group(['middleware' => ['auth']], function() {

                            // Front Settings Route
                            Route::get('/settings/profile', 'FrontSettingsController@profile')->name('settings.profile');
                            Route::patch('/settings/updateProfile', 'FrontSettingsController@updateProfile')->name('settings.updateProfile');

                            // Front Wishlist Routes
                            Route::post('/products/{product}/favourites', 'FrontProductController@store')->name('product.favourite.store');
                            Route::get('products/wishlist', 'FrontProductController@wishlist')->name('product.favourite.index');
                            Route::delete('/products/{product}/favourites', 'FrontProductController@destroy')->name('product.favourite.destroy');
                            Route::get('/products/favourites', 'FrontProductController@destroyWishlist')->name('product.favourite.destroywishlist');

                            // Front Customers/Addresses Routes
                            Route::resource('/customers', 'FrontCustomersController', ['except' => [
                                'index', 'edit', 'create', 'show'
                            ]]);
                            Route::post('/addresses', 'FrontCustomersController@startPaymentSession')->name('addresses.session');
                            Route::get('/addresses', 'FrontCustomersController@index')->name('addresses.index');
                            Route::get('/addresses/edit/{id}', 'FrontCustomersController@edit')->name('addresses.edit');

                            // Front Orders Routes
                            Route::resource('/orders', 'FrontOrdersController', ['except' => [
                                'create', 'store', 'edit', 'update', 'destroy'
                            ]]);
                            
                            Route::get('/wallet-history','FrontCustomersController@walletHistory')->name('wallet-history.index');
                            Route::patch('/orders/{id}', 'FrontOrdersController@hide');
                            Route::get('/edit/{id}', 'FrontOrdersController@edit')->name('orders.edit');
                            Route::patch('/update/{id}', 'FrontOrdersController@update')->name('orders.update');
                            Route::get('/referrals','FrontCustomersController@userReferrals')->name('referrals.index');
                            Route::get('/referrals/generate-referral-code','FrontCustomersController@generateUserReferralCode')->name('referrals.generateUserReferralCode');

                            // Front Account Overview Route
                            Route::get('account-overview', 'FrontController@account')->name('account');

                            // Front Reviews Routes
                            Route::resource('/reviews', 'FrontReviewsController', ['only' => ['store', 'edit', 'update']]);

                            // Front Coupons Routes
                            Route::post('/coupons', 'FrontCouponsController@checkCoupon');

                            // File Download Route
                            Route::post('/download', 'FrontOrdersController@download');

                            // Front Vendor Profile Route
                            Route::get('/vendor/profile', 'FrontVendorController@profile')->name('vendor.profile');
                            Route::patch('/vendor/updateProfile', 'FrontVendorController@updateProfile')->name('settings.updateProfile');

                            // Callback Url
                            Route::post('/paytm/payment/status', 'PaytmController@paymentCallback');
                            Route::get('/paystack/paystack-callback', 'PaystackController@paystackCallback');
                        });

                        // Ajax Routes
                        Route::get('/ajax/{type}/{slug?}', ['uses'=>'FrontController@products']);
                        Route::get('/ajax/product/get-variant/{product_id}/{variant_keys}/{value_keys}', ['uses'=>'FrontProductController@getVariantData']);
                        Route::get('/ajax/reviews-approved/product/{product_id}', ['uses'=>'FrontReviewsController@reviews']);

                        

                    });

                    // Checkout Routes
                    Route::group(['middleware' => ['auth'], 'as' => 'checkout.', 'prefix' => 'checkout'], function() {
                        
                        // Shipping Details Route
                        Route::get('/shipping-details', 'CheckoutController@shipping')->name('shipping');

                        // Payment Routes
                        Route::get('/refreshCheckoutPage', 'CheckoutController@refreshCheckoutPage')->name('payment.refreshCheckoutPage');
                        Route::get('/payment', 'CheckoutController@payment')->name('payment');
                        Route::post('/process-payment', 'CheckoutController@processPayment')->name('payment.process');
                        Route::get('/process-payment', 'CheckoutController@processPaymentGet');

                        // Change Shipping Address Route
                        Route::post('/change-shipping', 'CheckoutController@changeShippingAddress')->name('shipping.change');

                    });

                    // Payments Routes
                    Route::group(['middleware' => ['auth']], function() {

                        // Paypal Routes
                        Route::get('paypal/ec-checkout', 'PaypalController@getExpressCheckout');
                        Route::get('paypal/ec-checkout-success', 'PaypalController@getExpressCheckoutSuccess');
                        Route::get('paypal/ec-checkout-cancel', 'PaypalController@getExpressCheckoutCancel');

                        // Razorpay Route
                        Route::post('razorpay/payment', 'RazorpayController@payment')->name('razorpay.payment');
                        //Route::post('razorpay/failed', 'RazorpayController@failedPayment')->name('razorpay.failed');
                        // Stripe Route
                        Route::post('stripe/payment', 'StripeController@payment')->name('stripe.payment');

                        // Instamojo Routes
                        Route::get('instamojo/payment/response', 'InstamojoController@response')->name('instamojo.payment.response');

                        // PayU Routes
                        Route::get('payu/payment', 'PayUController@payment')->name('payu.payment');
                        Route::get('payu/payment/status', 'PayUController@status')->name('payu.status');
                        // Payment gateways callback Url

                        // Paystack
                        Route::post('/paystack/paystack-handle-webhook', 'PaystackController@paystackWebhook')->name('paystack.paystackWebhook');
                        Route::get('/paystack/paystack-callback', 'PaystackController@paystackCallback')->name('paystack.paystackCallback');

                        // Pesapal
                        Route::get('/pesapal/donePayment', 'PesapalController@paymentSuccess')->name('pesapal.paymentSuccess');
                        Route::get('/pesapal/paymentConfirmation', 'PesapalController@paymentConfirmation')->name('pesapal.paymentConfirmation');
                        
                        // Paytm
                        Route::post('/paytm/paytm-callback', 'PaytmController@paytmCallback')->name('paytm.paytmCallback');

                        // SMS OTP Verification
                        Route::post('auth/sms/sendOtp', 'SendVerificationSMS@sendOtp')->name('auth.sms.sendOtp');
                        Route::get('auth/sms/verify-form', ['as' => 'auth.sms.verify.form', 'uses' => 'SendVerificationSMS@verifyForm']);
                        Route::post('auth/sms/verifyOtp', 'SendVerificationSMS@verifyOtp')->name('auth.sms.verifyOtp');
                        
                        
                    });

                    // Newsletter Route
                    Route::post('subscribe', 'NewsletterController@addEmailToList');
                    Route::get('subscribe', 'NewsletterController@subscribe');
                    Route::get('subscribe/confirm', 'NewsletterController@confirm')->name('subscribe.confirm');

                    // Captcha Route
                    Route::get('/secure/captcha', 'CaptchaController@getCaptcha');

                    // Email Activation
                    Route::get('auth/activate', 'Auth\ActivationController@activate')->name('auth.activate');
                    Route::get('auth/activate/resend', 'Auth\ActivationResendController@showResendForm')->name('auth.activate.resend');
                    Route::post('auth/activate/resend', 'Auth\ActivationResendController@resend');
					
					Route::get('auth/activation', 'Auth\ActivationController@activation')->name('auth.activation');

                  

        });
        // Webcart Activate
            Route::get('webcart/activate', 'WebcartActivationController@activation')->name('webcart.activate');
            Route::post('webcart/activate', 'WebcartActivationController@activate');
            Route::post('webcart/activator', 'WebcartActivationController@activator');
            //Demo data route
            Route::get('webcart/import-demo-data', 'WebcartActivationController@demoData')->name('webcart.demo_data');
            Route::post('webcart/import-demo-data', 'WebcartActivationController@importDemoData');
            

    });

});


