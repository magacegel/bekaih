# UT-Report Application Routes Documentation

This document provides comprehensive documentation for all routes in the UT-Report Laravel application.

## Table of Contents
- [Authentication Routes](#authentication-routes)
- [Payment Routes](#payment-routes)
- [Public Test Routes](#public-test-routes)
- [Protected Routes](#protected-routes)
- [API Routes](#api-routes)

---

## Authentication Routes

### GET `/`
- **Description**: Root route that redirects to login page
- **Action Script**: Anonymous function in [`routes/web.php`](../routes/web.php)
- **Parameters**: None
- **Output Format**: HTTP redirect to `/login`

### GET `/login`
- **Description**: Display login form or redirect authenticated users to appropriate dashboard
- **Action Script**: `App\Http\Controllers\AuthController@index` ([`routes/web.php`](../routes/web.php), [`AuthController.php`](../app/Http/Controllers/AuthController.php))
- **Parameters**: None
- **Output Format**: HTML page (login view) or redirect based on user role

### POST `/proses_login`
- **Description**: Process user login with username/email/phone and password
- **Action Script**: `App\Http\Controllers\AuthController@proses_login` ([`routes/web.php`](../routes/web.php), [`AuthController.php`](../app/Http/Controllers/AuthController.php))
- **Parameters**: 
  - `username` (string): Username, email, or phone number
  - `password` (string): User password
  - `token` (string): reCAPTCHA token (production only)
  - `cabang` (string): Branch selection
- **Output Format**: HTTP redirect to role-based dashboard or back to login with errors

### GET|POST `/logout`
- **Description**: Log out user and clear session
- **Action Script**: `App\Http\Controllers\AuthController@logout` ([`routes/web.php`](../routes/web.php), [`AuthController.php`](../app/Http/Controllers/AuthController.php))
- **Parameters**: None
- **Output Format**: HTTP redirect to login page

---

## Payment Routes

### GET `/test/payment/{report}`
- **Description**: Test payment initiation for a specific report
- **Action Script**: `App\Http\Controllers\InvoiceController@initiatePayment` ([`routes/web.php`](../routes/web.php), [`InvoiceController.php`](../app/Http/Controllers/InvoiceController.php))
- **Parameters**: 
  - `report` (integer): Report ID
- **Output Format**: Payment gateway response or redirect

### GET `/payment/success`
- **Description**: Handle successful payment callback
- **Action Script**: `App\Http\Controllers\InvoiceController@success` ([`routes/web.php`](../routes/web.php), [`InvoiceController.php`](../app/Http/Controllers/InvoiceController.php))
- **Parameters**: Payment gateway parameters (varies by provider)
- **Output Format**: HTML page showing payment success

### GET `/payment/cancel`
- **Description**: Handle cancelled payment callback
- **Action Script**: `App\Http\Controllers\InvoiceController@cancel` ([`routes/web.php`](../routes/web.php), [`InvoiceController.php`](../app/Http/Controllers/InvoiceController.php))
- **Parameters**: Payment gateway parameters (varies by provider)
- **Output Format**: HTML page showing payment cancellation

### POST `/payment/callback`
- **Description**: Handle payment gateway callback (webhook)
- **Action Script**: `App\Http\Controllers\InvoiceController@callback` ([`routes/web.php`](../routes/web.php), [`InvoiceController.php`](../app/Http/Controllers/InvoiceController.php))
- **Parameters**: Payment gateway callback data
- **Output Format**: JSON response for payment gateway

---

## Public Test Routes

### GET `/report_surveyor_review/{id}`
- **Description**: Display report for surveyor review (public access)
- **Action Script**: `App\Http\Controllers\ReportController@report_surveyor_review` ([`routes/web.php`](../routes/web.php), [`ReportController.php`](../app/Http/Controllers/ReportController.php))
- **Parameters**: 
  - `id` (integer): Report ID
- **Output Format**: HTML page with report details

### POST `/surveyor_approve/{form}`
- **Description**: Approve form by surveyor
- **Action Script**: `App\Http\Controllers\ReportController@surveyor_approve` ([`routes/web.php`](../routes/web.php), [`ReportController.php`](../app/Http/Controllers/ReportController.php))
- **Parameters**: 
  - `form` (integer): Form ID
- **Output Format**: JSON response or redirect

### POST `/surveyor_revise/{form}`
- **Description**: Request revision for form by surveyor
- **Action Script**: `App\Http\Controllers\ReportController@surveyor_revise` ([`routes/web.php`](../routes/web.php), [`ReportController.php`](../app/Http/Controllers/ReportController.php))
- **Parameters**: 
  - `form` (integer): Form ID
  - `notes` (string): Revision notes
- **Output Format**: JSON response or redirect

### POST `/surveyor_submit/{id}`
- **Description**: Submit surveyor review
- **Action Script**: `App\Http\Controllers\ReportController@surveyor_submit` ([`routes/web.php`](../routes/web.php), [`ReportController.php`](../app/Http/Controllers/ReportController.php))
- **Parameters**: 
  - `id` (integer): Report ID
- **Output Format**: JSON response or redirect

### GET `/comcerts/{id}`
- **Description**: Display company certificates
- **Action Script**: `App\Http\Controllers\CompanyController@comcerts` ([`routes/web.php`](../routes/web.php), [`CompanyController.php`](../app/Http/Controllers/CompanyController.php))
- **Parameters**: 
  - `id` (integer): Company ID
- **Output Format**: HTML page with certificates

### GET `/report_print_tcpdf3/{id}`
- **Description**: Generate PDF report using TCPDF (version 3)
- **Action Script**: `App\Http\Controllers\Report\TcpdfController@data` ([`routes/web.php`](../routes/web.php), [`Report/TcpdfController.php`](../app/Http/Controllers/Report/TcpdfController.php))
- **Parameters**: 
  - `id` (integer): Report ID
- **Output Format**: PDF file

### GET `/report_print_tcpdf4/{id}`
- **Description**: Generate PDF report using TCPDF (version 4)
- **Action Script**: `App\Http\Controllers\Report\TcpdfController@run` ([`routes/web.php`](../routes/web.php), [`Report/TcpdfController.php`](../app/Http/Controllers/Report/TcpdfController.php))
- **Parameters**: 
  - `id` (integer): Report ID
- **Output Format**: PDF file

### GET `/cek/{id}`
- **Description**: Check and list PDF reports (optimized)
- **Action Script**: `App\Http\Controllers\ReportOptimizedController@listPDF` ([`routes/web.php`](../routes/web.php), [`ReportOptimizedController.php`](../app/Http/Controllers/ReportOptimizedController.php))
- **Parameters**: 
  - `id` (integer): Report ID
- **Output Format**: JSON response with PDF list

---

## Protected Routes (Require Authentication)

### User Profile Routes

#### GET `/profile`
- **Description**: Display user profile edit form
- **Action Script**: `App\Http\Controllers\UserController@profile` ([`routes/web.php`](../routes/web.php), [`UserController.php`](../app/Http/Controllers/UserController.php))
- **Parameters**: None
- **Output Format**: HTML page with profile form

#### POST `/profile`
- **Description**: Update user profile information
- **Action Script**: `App\Http\Controllers\UserController@profile` ([`routes/web.php`](../routes/web.php), [`UserController.php`](../app/Http/Controllers/UserController.php))
- **Parameters**: 
  - `name` (string): User name
  - `email` (string): Email address
  - `ktp` (string): ID card number
  - `phone` (string): Phone number
  - `signature` (file): Signature image
  - `profile_image` (file): Profile image
- **Output Format**: JSON response or redirect with success/error message

#### GET `/user_list`
- **Description**: Display list of users
- **Action Script**: `App\Http\Controllers\UserController@user_list` ([`routes/web.php`](../routes/web.php), [`UserController.php`](../app/Http/Controllers/UserController.php))
- **Parameters**: None
- **Output Format**: HTML page with user list

---

### Dashboard Routes (Role-based Access)

#### GET `/dashboard`
- **Description**: Main dashboard for authenticated users
- **Action Script**: `App\Http\Controllers\AdminController@index` ([`routes/web.php`](../routes/web.php), [`AdminController.php`](../app/Http/Controllers/AdminController.php))
- **Parameters**: None
- **Output Format**: HTML page with dashboard

#### GET `/inspektor`
- **Description**: Inspector dashboard
- **Action Script**: `App\Http\Controllers\AdminController@index` ([`routes/web.php`](../routes/web.php), [`AdminController.php`](../app/Http/Controllers/AdminController.php))
- **Parameters**: None
- **Output Format**: HTML page with inspector dashboard

#### GET `/supervisor`
- **Description**: Supervisor dashboard
- **Action Script**: `App\Http\Controllers\AdminController@index` ([`routes/web.php`](../routes/web.php), [`AdminController.php`](../app/Http/Controllers/AdminController.php))
- **Parameters**: None
- **Output Format**: HTML page with supervisor dashboard

---

### Super Admin & Administrator Routes

#### GET `/profile/{id}`
- **Description**: View/edit specific user profile (admin only)
- **Action Script**: `App\Http\Controllers\UserController@profile` ([`routes/web.php`](../routes/web.php), [`UserController.php`](../app/Http/Controllers/UserController.php))
- **Parameters**: 
  - `id` (integer): User ID
- **Output Format**: HTML page with user profile

#### GET `/sa-dashboard`
- **Description**: Super admin dashboard
- **Action Script**: `App\Http\Controllers\AdminController@index` ([`routes/web.php`](../routes/web.php), [`AdminController.php`](../app/Http/Controllers/AdminController.php))
- **Parameters**: None
- **Output Format**: HTML page with super admin dashboard

#### GET `/sa-customer`
- **Description**: Super admin customer management
- **Action Script**: `App\Http\Controllers\AdminController@index_customers` ([`routes/web.php`](../routes/web.php), [`AdminController.php`](../app/Http/Controllers/AdminController.php))
- **Parameters**: None
- **Output Format**: HTML page with customer list

#### GET `/sa-user`
- **Description**: Super admin user management
- **Action Script**: `App\Http\Controllers\AdminController@index_users` ([`routes/web.php`](../routes/web.php), [`AdminController.php`](../app/Http/Controllers/AdminController.php))
- **Parameters**: None
- **Output Format**: HTML page with user management

#### GET `/sa-permohonanbarangjasa`
- **Description**: Super admin goods and services requests
- **Action Script**: `App\Http\Controllers\AdminController@index_permohonan_barang_jasa` ([`routes/web.php`](../routes/web.php), [`AdminController.php`](../app/Http/Controllers/AdminController.php))
- **Parameters**: None
- **Output Format**: HTML page with requests list

#### GET `/sa-permohonanbarang`
- **Description**: Super admin goods requests
- **Action Script**: `App\Http\Controllers\AdminController@index_permohonan_barang` ([`routes/web.php`](../routes/web.php), [`AdminController.php`](../app/Http/Controllers/AdminController.php))
- **Parameters**: None
- **Output Format**: HTML page with goods requests

#### GET `/sa-permohonanjasa`
- **Description**: Super admin services requests
- **Action Script**: `App\Http\Controllers\AdminController@index_permohonan_jasa` ([`routes/web.php:72`](../routes/web.php), [`AdminController.php`](../app/Http/Controllers/AdminController.php))
- **Parameters**: None
- **Output Format**: HTML page with services requests

#### GET `/sa-tagihan`
- **Description**: Super admin billing management
- **Action Script**: `App\Http\Controllers\AdminController@index_tagihan` ([`routes/web.php:73`](../routes/web.php), [`AdminController.php`](../app/Http/Controllers/AdminController.php))
- **Parameters**: None
- **Output Format**: HTML page with billing list

---

### User Management Routes

#### GET `/user_management`
- **Description**: User management interface
- **Action Script**: `App\Http\Controllers\UserController@user_management` ([`routes/web.php:75`](../routes/web.php), [`UserController.php`](../app/Http/Controllers/UserController.php))
- **Parameters**: None
- **Output Format**: HTML page with user management interface

#### POST `/user_data`
- **Description**: Save or update user data
- **Action Script**: `App\Http\Controllers\UserController@user_data` ([`routes/web.php:76`](../routes/web.php), [`UserController.php`](../app/Http/Controllers/UserController.php))
- **Parameters**: User form data (varies by action)
- **Output Format**: JSON response with success/error status

#### GET `/user_datatables`
- **Description**: Get user data for DataTables
- **Action Script**: `App\Http\Controllers\UserController@user_datatables` ([`routes/web.php:77`](../routes/web.php), [`UserController.php`](../app/Http/Controllers/UserController.php))
- **Parameters**: DataTables parameters
- **Output Format**: JSON response for DataTables

---

### Settings Routes

#### GET `/settings/form_type`
- **Description**: Form type settings management
- **Action Script**: `App\Http\Controllers\SettingController@form_type` ([`routes/web.php:79`](../routes/web.php), [`SettingController.php`](../app/Http/Controllers/SettingController.php))
- **Parameters**: None
- **Output Format**: HTML page with form type settings

#### POST `/settings/form_type_data`
- **Description**: Save form type data
- **Action Script**: `App\Http\Controllers\SettingController@form_type_data` ([`routes/web.php:80`](../routes/web.php), [`SettingController.php`](../app/Http/Controllers/SettingController.php))
- **Parameters**: Form type data
- **Output Format**: JSON response

#### GET `/settings/form_type_datatables`
- **Description**: Get form type data for DataTables
- **Action Script**: `App\Http\Controllers\SettingController@form_type_datatables` ([`routes/web.php:81`](../routes/web.php), [`SettingController.php`](../app/Http/Controllers/SettingController.php))
- **Parameters**: DataTables parameters
- **Output Format**: JSON response for DataTables

#### GET `/settings/ship_type`
- **Description**: Ship type settings management
- **Action Script**: `App\Http\Controllers\SettingController@ship_type`
- **Parameters**: None
- **Output Format**: HTML page with ship type settings

#### GET `/settings/ship_type_datatables`
- **Description**: Get ship type data for DataTables
- **Action Script**: `App\Http\Controllers\SettingController@ship_type_datatables`
- **Parameters**: DataTables parameters
- **Output Format**: JSON response for DataTables

#### POST `/settings/ship_type_data`
- **Description**: Save ship type data
- **Action Script**: `App\Http\Controllers\SettingController@ship_type_data`
- **Parameters**: Ship type data
- **Output Format**: JSON response

#### GET `/settings/category`
- **Description**: Category settings management
- **Action Script**: `App\Http\Controllers\SettingController@category`
- **Parameters**: None
- **Output Format**: HTML page with category settings

#### GET `/settings/category_datatables`
- **Description**: Get category data for DataTables
- **Action Script**: `App\Http\Controllers\SettingController@category_datatables`
- **Parameters**: DataTables parameters
- **Output Format**: JSON response for DataTables

#### POST `/settings/category_data`
- **Description**: Save category data
- **Action Script**: `App\Http\Controllers\SettingController@category_data`
- **Parameters**: Category data
- **Output Format**: JSON response

#### GET `/settings/report`
- **Description**: Report settings management
- **Action Script**: `App\Http\Controllers\SettingController@report`
- **Parameters**: None
- **Output Format**: HTML page with report settings

#### POST `/settings/report_data`
- **Description**: Save report settings data
- **Action Script**: `App\Http\Controllers\SettingController@report_data`
- **Parameters**: Report settings data
- **Output Format**: JSON response

---

### Administrator Routes

#### GET `/a-customer`
- **Description**: Administrator customer management
- **Action Script**: `App\Http\Controllers\AdminController@index_customer`
- **Parameters**: None
- **Output Format**: HTML page with customer list

#### GET `/a-permohonanbarangjasa`
- **Description**: Administrator goods and services requests
- **Action Script**: `App\Http\Controllers\AdminController@index_permohonan_barang_jasa`
- **Parameters**: None
- **Output Format**: HTML page with requests list

#### GET `/a-tambahbarangjasa`
- **Description**: Add new goods and services request form
- **Action Script**: `App\Http\Controllers\AdminController@add_permohonan_barang_jasa`
- **Parameters**: None
- **Output Format**: HTML page with add request form

#### GET `/a-permohonanbarang`
- **Description**: Administrator goods requests
- **Action Script**: `App\Http\Controllers\AdminController@index_permohonan_barang`
- **Parameters**: None
- **Output Format**: HTML page with goods requests

#### GET `/a-permohonanjasa`
- **Description**: Administrator services requests
- **Action Script**: `App\Http\Controllers\AdminController@index_permohonan_jasa`
- **Parameters**: None
- **Output Format**: HTML page with services requests

#### GET `/a-tagihan`
- **Description**: Administrator billing management
- **Action Script**: `App\Http\Controllers\AdminController@index_tagihan`
- **Parameters**: None
- **Output Format**: HTML page with billing list

#### POST `/a-tambahpemohonbarangjasa`
- **Description**: Save new goods and services request
- **Action Script**: `App\Http\Controllers\AdminController@save_permohonan_barang_jasa`
- **Parameters**: Request form data
- **Output Format**: JSON response or redirect

---

### Management Project Routes

#### GET `/mp-tagihan`
- **Description**: Management project billing view
- **Action Script**: `App\Http\Controllers\UserController@index_tagihan`
- **Parameters**: None
- **Output Format**: HTML page with billing information

---

### User Routes

#### GET `/u-tagihan`
- **Description**: User billing view
- **Action Script**: `App\Http\Controllers\UserController@index_tagihan`
- **Parameters**: None
- **Output Format**: HTML page with user billing

---

### Equipment Routes (Multi-role Access)

#### GET `/equipment`
- **Description**: Equipment management index
- **Action Script**: `App\Http\Controllers\EquipmentController@index`
- **Parameters**: None
- **Output Format**: HTML page with equipment list

#### GET `/equipment/show/{id}`
- **Description**: Show equipment details
- **Action Script**: `App\Http\Controllers\EquipmentController@show`
- **Parameters**: 
  - `id` (integer): Equipment ID
- **Output Format**: HTML page with equipment details

#### GET `/equipment/certificates/{equipmentId}`
- **Description**: Get equipment certificates
- **Action Script**: `App\Http\Controllers\EquipmentController@getCertificates`
- **Parameters**: 
  - `equipmentId` (integer): Equipment ID
- **Output Format**: JSON response with certificates data

#### POST `/equipment/save/{id}` (High Privilege)
- **Description**: Save equipment data
- **Action Script**: `App\Http\Controllers\EquipmentController@save`
- **Parameters**: 
  - `id` (integer): Equipment ID
  - Equipment form data
- **Output Format**: JSON response

#### DELETE `/equipment/delete/{id}` (High Privilege)
- **Description**: Delete equipment
- **Action Script**: `App\Http\Controllers\EquipmentController@delete`
- **Parameters**: 
  - `id` (integer): Equipment ID
- **Output Format**: JSON response

#### POST `/equipment` (High Privilege)
- **Description**: Create new equipment
- **Action Script**: `App\Http\Controllers\EquipmentController@store`
- **Parameters**: Equipment form data
- **Output Format**: JSON response

#### POST `/equipment/certificate/add` (High Privilege)
- **Description**: Add equipment certificate
- **Action Script**: `App\Http\Controllers\EquipmentController@addCertificate`
- **Parameters**: Certificate data
- **Output Format**: JSON response

#### DELETE `/equipment/certificate/delete/{id}` (High Privilege)
- **Description**: Delete equipment certificate
- **Action Script**: `App\Http\Controllers\EquipmentController@deleteCertificate`
- **Parameters**: 
  - `id` (integer): Certificate ID
- **Output Format**: JSON response

#### PUT `/equipment/certificate/update/{id}` (High Privilege)
- **Description**: Update equipment certificate
- **Action Script**: `App\Http\Controllers\EquipmentController@updateCertificate`
- **Parameters**: 
  - `id` (integer): Certificate ID
  - Certificate update data
- **Output Format**: JSON response

#### POST `/equipment/certificate/toggle-active/{id}` (High Privilege)
- **Description**: Toggle certificate active status
- **Action Script**: `App\Http\Controllers\EquipmentController@toggleCertificateActive`
- **Parameters**: 
  - `id` (integer): Certificate ID
- **Output Format**: JSON response

---

### Company Routes (Multi-role Access)

#### GET `/company`
- **Description**: Company management index
- **Action Script**: `App\Http\Controllers\CompanyController@index`
- **Parameters**: None
- **Output Format**: HTML page with company list

#### GET `/company/data`
- **Description**: Get company data for DataTables
- **Action Script**: `App\Http\Controllers\CompanyController@data`
- **Parameters**: DataTables parameters
- **Output Format**: JSON response for DataTables

#### GET `/company/user/data/{company}`
- **Description**: Get company user data
- **Action Script**: `App\Http\Controllers\CompanyController@user_data`
- **Parameters**: 
  - `company` (integer): Company ID
- **Output Format**: JSON response with user data

#### GET `/company/show/{id}`
- **Description**: Show company details
- **Action Script**: `App\Http\Controllers\SettingController@company_detail`
- **Parameters**: 
  - `id` (integer): Company ID
- **Output Format**: HTML page with company details

#### POST `/company` (High Privilege)
- **Description**: Create new company
- **Action Script**: `App\Http\Controllers\CompanyController@store`
- **Parameters**: Company form data
- **Output Format**: JSON response

#### POST `/company/save/{id}` (High Privilege)
- **Description**: Save company settings
- **Action Script**: `App\Http\Controllers\SettingController@company_setting_save`
- **Parameters**: 
  - `id` (integer): Company ID
  - Company settings data
- **Output Format**: JSON response

#### POST `/company/certificate/save/{id}` (High Privilege)
- **Description**: Save company certificate
- **Action Script**: `App\Http\Controllers\SettingController@company_certificate_save`
- **Parameters**: 
  - `id` (integer): Company ID
  - Certificate data
- **Output Format**: JSON response

#### PUT `/company/{id}` (High Privilege)
- **Description**: Update company
- **Action Script**: `App\Http\Controllers\CompanyController@update`
- **Parameters**: 
  - `id` (integer): Company ID
  - Company update data
- **Output Format**: JSON response

#### DELETE `/company/{id}` (High Privilege)
- **Description**: Delete company
- **Action Script**: `App\Http\Controllers\CompanyController@destroy`
- **Parameters**: 
  - `id` (integer): Company ID
- **Output Format**: JSON response

#### POST `/company/inspector/store` (High Privilege)
- **Description**: Add company inspector
- **Action Script**: `App\Http\Controllers\CompanyController@storeInspector`
- **Parameters**: Inspector data
- **Output Format**: JSON response

#### POST `/company/inspector/update/{id}` (High Privilege)
- **Description**: Update company inspector
- **Action Script**: `App\Http\Controllers\CompanyController@updateInspector`
- **Parameters**: 
  - `id` (integer): Inspector ID
  - Inspector update data
- **Output Format**: JSON response

#### DELETE `/company/inspector/delete/{id}` (High Privilege)
- **Description**: Delete company inspector
- **Action Script**: `App\Http\Controllers\CompanyController@deleteInspector`
- **Parameters**: 
  - `id` (integer): Inspector ID
- **Output Format**: JSON response

#### POST `/company/competency/store` (High Privilege)
- **Description**: Add company competency
- **Action Script**: `App\Http\Controllers\CompanyController@storeCompetency`
- **Parameters**: Competency data
- **Output Format**: JSON response

#### DELETE `/company/competency/delete/{id}` (High Privilege)
- **Description**: Delete company competency
- **Action Script**: `App\Http\Controllers\CompanyController@deleteCompetency`
- **Parameters**: 
  - `id` (integer): Competency ID
- **Output Format**: JSON response

#### GET `/company/user/update/{company}/{user}` (Supervisor Only)
- **Description**: Update company user
- **Action Script**: `App\Http\Controllers\CompanyController@user_update`
- **Parameters**: 
  - `company` (integer): Company ID
  - `user` (integer): User ID
- **Output Format**: HTML page or JSON response

---

### Report Routes (Multi-role Access)

#### GET `/report`
- **Description**: Report management index
- **Action Script**: `App\Http\Controllers\ReportController@index`
- **Parameters**: None
- **Output Format**: HTML page with report list

#### GET `/report_detail/{id}`
- **Description**: Show report details
- **Action Script**: `App\Http\Controllers\ReportController@report_detail`
- **Parameters**: 
  - `id` (integer): Report ID
- **Output Format**: HTML page with report details

#### GET `/report_print_pdf/{id}`
- **Description**: Generate PDF report
- **Action Script**: `App\Http\Controllers\ReportController@report_print_pdf`
- **Parameters**: 
  - `id` (integer): Report ID
- **Output Format**: PDF file

#### GET `/report_print_tcpdf/{id}`
- **Description**: Generate PDF report using TCPDF
- **Action Script**: `App\Http\Controllers\ReportController@report_print_tcpdf`
- **Parameters**: 
  - `id` (integer): Report ID
- **Output Format**: PDF file

#### GET `/report_print_tcpdf2/{id}`
- **Description**: Generate optimized PDF report using TCPDF
- **Action Script**: `App\Http\Controllers\ReportOptimizedController@render`
- **Parameters**: 
  - `id` (integer): Report ID
- **Output Format**: PDF file

#### GET `/report_preview/{id}`
- **Description**: Preview report
- **Action Script**: `App\Http\Controllers\ReportController@report_preview`
- **Parameters**: 
  - `id` (integer): Report ID
- **Output Format**: HTML page with report preview

#### GET `/report_datatables`
- **Description**: Get report data for DataTables
- **Action Script**: `App\Http\Controllers\ReportController@report_datatables`
- **Parameters**: DataTables parameters
- **Output Format**: JSON response for DataTables

#### POST `/report_data` (Inspector/Supervisor Only)
- **Description**: Save report data
- **Action Script**: `App\Http\Controllers\ReportController@report_data`
- **Parameters**: Report form data
- **Output Format**: JSON response

#### POST `/report_image` (Inspector/Supervisor Only)
- **Description**: Upload report image
- **Action Script**: `App\Http\Controllers\ReportController@report_image`
- **Parameters**: 
  - Image file
  - Report metadata
- **Output Format**: JSON response

#### POST `/report_submit` (Inspector/Supervisor Only)
- **Description**: Submit report
- **Action Script**: `App\Http\Controllers\ReportController@report_submit`
- **Parameters**: 
  - `report_id` (integer): Report ID
- **Output Format**: JSON response or redirect

#### GET `/report/edit-general-particular/{id}` (Inspector/Supervisor Only)
- **Description**: Edit report general particulars
- **Action Script**: `App\Http\Controllers\ReportController@editGeneralParticular`
- **Parameters**: 
  - `id` (integer): Report ID
- **Output Format**: HTML page with edit form

#### PUT `/report/update-general-particular/{id}` (Inspector/Supervisor Only)
- **Description**: Update report general particulars
- **Action Script**: `App\Http\Controllers\ReportController@updateGeneralParticular`
- **Parameters**: 
  - `id` (integer): Report ID
  - General particular data
- **Output Format**: JSON response

---

### Form Routes

#### GET `/form/edit/{id}`
- **Description**: Edit form
- **Action Script**: `App\Http\Controllers\FormController@edit`
- **Parameters**: 
  - `id` (integer): Form ID
- **Output Format**: HTML page with form editor

#### GET `/form_datatables`
- **Description**: Get form data for DataTables
- **Action Script**: `App\Http\Controllers\ShipController@form_datatables`
- **Parameters**: DataTables parameters
- **Output Format**: JSON response for DataTables

#### POST `/form_data` (Inspector/Supervisor Only)
- **Description**: Save form data
- **Action Script**: `App\Http\Controllers\FormController@form_data`
- **Parameters**: Form data
- **Output Format**: JSON response

---

### Ship Routes

#### GET `/ship`
- **Description**: Ship management index
- **Action Script**: `App\Http\Controllers\ShipController@index`
- **Parameters**: None
- **Output Format**: HTML page with ship list

#### GET `/ship_datatables`
- **Description**: Get ship data for DataTables
- **Action Script**: `App\Http\Controllers\ShipController@ship_datatables`
- **Parameters**: DataTables parameters
- **Output Format**: JSON response for DataTables

#### GET `/ship_list`
- **Description**: Get ship list
- **Action Script**: `App\Http\Controllers\ShipController@ship_list`
- **Parameters**: None
- **Output Format**: JSON response with ship list

#### POST `/ship_data` (Inspector/Supervisor Only)
- **Description**: Save ship data
- **Action Script**: `App\Http\Controllers\ShipController@ship_data`
- **Parameters**: Ship form data
- **Output Format**: JSON response

---

### User Certificate Routes

#### GET `/user-certificates/{user_id}`
- **Description**: Get user certificates
- **Action Script**: `App\Http\Controllers\UserController@getUserCertificates`
- **Parameters**: 
  - `user_id` (integer): User ID
- **Output Format**: JSON response with certificates

#### POST `/upload-certificate` (Inspector/Supervisor Only)
- **Description**: Upload user certificate
- **Action Script**: `App\Http\Controllers\UserController@uploadCertificate`
- **Parameters**: 
  - Certificate file
  - Certificate metadata
- **Output Format**: JSON response

---

### Supervisor Routes

#### GET `/supervisor_review/{id}`
- **Description**: Supervisor review interface
- **Action Script**: `App\Http\Controllers\ReportController@report_supervisor_review`
- **Parameters**: 
  - `id` (integer): Report ID
- **Output Format**: HTML page with supervisor review

#### POST `/supervisor_approve/{form}`
- **Description**: Approve form by supervisor
- **Action Script**: `App\Http\Controllers\ReportController@supervisor_approve`
- **Parameters**: 
  - `form` (integer): Form ID
- **Output Format**: JSON response

#### POST `/supervisor_revise/{form}`
- **Description**: Request revision by supervisor
- **Action Script**: `App\Http\Controllers\ReportController@supervisor_revise`
- **Parameters**: 
  - `form` (integer): Form ID
  - Revision notes
- **Output Format**: JSON response

#### POST `/supervisor_submit/{id}`
- **Description**: Submit supervisor review
- **Action Script**: `App\Http\Controllers\ReportController@supervisor_submit`
- **Parameters**: 
  - `id` (integer): Report ID
- **Output Format**: JSON response

---

### Sync Routes (Super Admin/Administrator Only)

#### GET `/sync_ship`
- **Description**: Synchronize ship data
- **Action Script**: `App\Http\Controllers\ShipController@sync_ship`
- **Parameters**: None
- **Output Format**: JSON response with sync status

#### GET `/sync_user`
- **Description**: Synchronize user data
- **Action Script**: `App\Http\Controllers\UserController@sync_user`
- **Parameters**: None
- **Output Format**: JSON response with sync status

---

## API Routes

### GET `/api/api_report_all`
- **Description**: Get all reports via API
- **Action Script**: `App\Http\Controllers\ReportController@api_report_all` ([`routes/api.php:18`](../routes/api.php), [`ReportController.php`](../app/Http/Controllers/ReportController.php))
- **Parameters**: None
- **Output Format**: JSON response with all reports

### GET `/api/api_report/{id}`
- **Description**: Get specific report via API
- **Action Script**: `App\Http\Controllers\ReportController@api_report` ([`routes/api.php:19`](../routes/api.php), [`ReportController.php`](../app/Http/Controllers/ReportController.php))
- **Parameters**: 
  - `id` (integer): Report ID
- **Output Format**: JSON response with report data

### GET `/api/user` (Authenticated)
- **Description**: Get authenticated user information
- **Action Script**: Anonymous function in [`routes/api.php:21-23`](../routes/api.php)
- **Parameters**: None (requires Bearer token)
- **Output Format**: JSON response with user data

---

## Middleware Information

- **auth**: Requires user authentication
- **check_profile**: Requires complete user profile
- **role:**: Role-based access control
  - `superadmin`: Super administrator access
  - `administrator`: Administrator access
  - `supervisor`: Supervisor access
  - `inspektor`: Inspector access
  - `manajemenproyek`: Project management access
  - `user`: Regular user access
- **web**: Web middleware group (CSRF protection, session, etc.)
- **auth:sanctum**: API authentication using Laravel Sanctum

---

## Notes

1. Most routes require authentication and specific role permissions
2. DataTables routes return JSON formatted for jQuery DataTables plugin
3. PDF generation routes return downloadable PDF files
4. Image upload routes handle file validation and storage
5. All form submission routes include CSRF protection
6. API routes use different authentication mechanism (Sanctum)
7. Some routes have multiple HTTP methods (GET/POST) for different actions
