<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
 */
$route['default_controller'] = 'Main';
$route['inicio']           = 'Main/inicio';
$route['salir']            = 'Main/logout';

# Envio de correos electronicos
#$route['testmail/(:any)']        = 'SendEmail/testEmail/$1'; #Habilitar para pruebas externas de configuracion de email
$route['contacto']         = 'SendEmail/contact';

#metricas del sistema
$route['dashboard']        = 'metrics/Dashboard';
$route['export']           = 'metrics/Dashboard/exportData';
$route['formAgregarDato']  = 'metrics/AddValues';
$route['agregarDato']      = 'metrics/AddValues/addData';
$route['validar']          = 'metrics/Validation';
$route['validar/update']   = 'metrics/Validation/validate_reject';

#Comunicación con ADI
$route['verif_usuario']    = 'ADI/user_verify';

# Interfaces de configuracion
$route['configurar']       = 'configuration/MainConfig/configMenu';

$route['config/metricas']        = 'configuration/MetricsConfig/metricsConfig';
$route['config/metricas/add']    = 'configuration/MetricsConfig/addMetric';
$route['config/metricas/delmod'] = 'configuration/MetricsConfig/delModMetric';

$route['config/dashboard']                = 'configuration/DashboardConfig/dashboardConfig';
$route['config/dashboard/modify/graphic'] = 'configuration/DashboardConfig/modifyGraphic';
$route['config/dashboard/modify/serie']   = 'configuration/DashboardConfig/modifySerie';
$route['config/dashboard/delete']         = 'configuration/DashboardConfig/delete';
$route['config/dashboard/values']         = 'configuration/DashboardConfig/graphicValues';

$route['config/organizacion']         = 'configuration/OrganizationConfig/modifyAreaUnidad';
$route['config/organizacion/addArea'] = 'configuration/OrganizationConfig/addArea';
$route['config/organizacion/addUni']  = 'configuration/OrganizationConfig/addUni';
$route['config/organizacion/delete']  = 'configuration/OrganizationConfig/delAreaUni';

#Interfaces de FODA y Plan Estrategico
$route['fodaStrategy'] = 'FodaStrategy/fodaIndex';
$route['fodaStrategy/validate'] = 'FodaStrategy/validate';

$route['fodaStrategy/modify/foda'] = 'FodaStrategy/modifyFoda';
$route['fodaStrategy/modify/strategy'] = 'FodaStrategy/modifyStrategy';

$route['fodaStrategy/add/item'] = 'FodaStrategy/modifyItem';
$route['fodaStrategy/add/goal'] = 'FodaStrategy/modifyGoal';
$route['fodaStrategy/add/action'] = 'FodaStrategy/modifyAction';

$route['fodaStrategy/delete'] = 'FodaStrategy/delete';

#Presupuesto de la organizacion
$route['presupuesto'] = 'Budget/index';
$route['presupuesto/modify'] = 'Budget/modify';
$route['presupuesto/validate'] = 'Budget/validate';

#Otros
$route['404_override']         = '';
$route['translate_uri_dashes'] = FALSE;
