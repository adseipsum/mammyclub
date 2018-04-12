<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

[2018-04-10 06:39:48] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest/export Severity: Warning  --> Invalid argument supplied for foreach() C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 26
[2018-04-10 06:42:38] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest/export Severity: Warning  --> Invalid argument supplied for foreach() C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 26
[2018-04-10 06:42:38] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest/export FATAL ERROR: Uncaught exception 'MoySklad\Exceptions\EntityHasNoIdException' with message 'Entity MoySklad\Entities\Misc\Webhook has no id' in C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php:131
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php(146): MoySklad\Entities\AbstractEntity->findEntityId()
#1 C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php(20): MoySklad\Entities\AbstractEntity->fresh()
#2 C:\AppServ\www\mammyclub\app\controllers\admin\xadmin_supplierrequest.php(39): MoySkladManager->getInstance()
#3 [internal function]: xAdmin_SupplierRequest->export_process()
#4 C:\AppServ\www\mammyclub\lib\codeigniter\CodeIgniter.php(206): call_user_func_array(Array, Array)
#5 C:\AppServ\www\mammyclub\index.php(186): require_once('C:\\AppServ\\www\\...')
#6 {main}
  thrown; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 131
[2018-04-10 06:43:02] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest/export Severity: Notice  --> Undefined property: stdClass::$meta C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\EntityFields.php 40
[2018-04-10 06:43:02] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest/export FATAL ERROR: Call to a member function getId() on null; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 131
[2018-04-10 06:46:13] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest/export Severity: Warning  --> Invalid argument supplied for foreach() C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 26
[2018-04-10 06:46:13] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest/export FATAL ERROR: Uncaught exception 'MoySklad\Exceptions\IncompleteCreationFieldsException' with message 'Entity MoySklad\Entities\Misc\Webhook requires these fields to be created: ["url","action","entityType"], has no these fields at the moment: ["url","action","entityType"]' in C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php:334
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\MutationBuilders\CreationBuilder.php(28): MoySklad\Entities\AbstractEntity->validateFieldsRequiredForCreation()
#1 C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php(210): MoySklad\Components\MutationBuilders\CreationBuilder->execute()
#2 C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php(20): MoySklad\Entities\AbstractEntity->create()
#3 C:\AppServ\www\mammyclub\app\controllers\admin\xadmin_supplierrequest.php(39): MoySkladManager->getInstance()
#4 [internal function]: xAdmin_SupplierRequest->export_process()
#5 C:\AppServ\www\mammyclub\lib\codeign; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 334
[2018-04-10 07:00:41] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest/export FATAL ERROR: Call to undefined function MoySklad\Entities\Products\Product::listQueryResponseAttributeMapper(); FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Query\AbstractQuery.php at line: 229
[2018-04-10 07:06:13] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest/export FATAL ERROR: Class 'DashboardReport' not found; FILE: C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php at line: 18
[2018-04-10 08:28:36] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest/export FATAL ERROR: Class 'Entities\Documents\Orders\PurchaseOrder' not found; FILE: C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php at line: 14
[2018-04-10 08:28:54] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest/export Severity: 4096  --> Argument 1 passed to MoySklad\Entities\AbstractEntity::__construct() must be an instance of MoySklad\MoySklad, none given, called in C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php on line 14 and defined C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php 79
[2018-04-10 08:28:54] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest/export Severity: Notice  --> Undefined variable: skladInstance C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php 86
[2018-04-10 08:28:54] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest/export FATAL ERROR: Call to a member function hashCode() on null; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 86
[2018-04-10 08:34:13] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Array to string conversion C:\AppServ\www\mammyclub\app\controllers\admin\xadmin_supplierrequest.php 594
[2018-04-10 10:25:59] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: User Error  --> Call to undefined method MoySkladManager::getInstance C:\AppServ\www\mammyclub\app\logic\base\BaseManager.php 184
[2018-04-10 10:25:59] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Call to a member function createPurchaseOrder() on null; FILE: C:\AppServ\www\mammyclub\app\controllers\admin\xadmin_supplierrequest.php at line: 591
[2018-04-10 10:26:09] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: User Error  --> Call to undefined method MoySkladManager::getInstance C:\AppServ\www\mammyclub\app\logic\base\BaseManager.php 184
[2018-04-10 10:26:09] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Call to a member function createPurchaseOrder() on null; FILE: C:\AppServ\www\mammyclub\app\controllers\admin\xadmin_supplierrequest.php at line: 591
[2018-04-10 10:26:11] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: User Error  --> Call to undefined method MoySkladManager::getInstance C:\AppServ\www\mammyclub\app\logic\base\BaseManager.php 184
[2018-04-10 10:26:11] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Call to a member function createPurchaseOrder() on null; FILE: C:\AppServ\www\mammyclub\app\controllers\admin\xadmin_supplierrequest.php at line: 591
[2018-04-10 10:30:27] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined index: id C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 22
[2018-04-10 10:30:27] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined index: description C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 23
[2018-04-10 10:30:27] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined index: id C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 24
[2018-04-10 10:30:27] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined index:  C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 25
[2018-04-10 11:08:29] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined index: id C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 22
[2018-04-10 11:08:29] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined index: description C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 23
[2018-04-10 11:08:29] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined index: id C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 24
[2018-04-10 11:08:29] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined index: execution_date  C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 25
[2018-04-10 11:09:08] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined index: id C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 22
[2018-04-10 11:09:08] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined index: description C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 23
[2018-04-10 11:09:08] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined index: id C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 24
[2018-04-10 11:09:08] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined index: execution_date  C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 25
[2018-04-10 11:11:18] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined index: description C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 23
[2018-04-10 11:11:18] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined index: execution_date  C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 25
[2018-04-10 11:13:29] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined property: stdClass::$meta C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\EntityFields.php 40
[2018-04-10 11:13:29] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Call to a member function getId() on null; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 131
[2018-04-10 11:14:12] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined property: stdClass::$meta C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\EntityFields.php 40
[2018-04-10 11:14:12] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Call to a member function getId() on null; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 131
[2018-04-10 11:14:30] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined property: stdClass::$meta C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\EntityFields.php 40
[2018-04-10 11:14:30] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Call to a member function getId() on null; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 131
[2018-04-10 11:14:33] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined property: stdClass::$meta C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\EntityFields.php 40
[2018-04-10 11:14:33] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Call to a member function getId() on null; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 131
[2018-04-10 11:17:47] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined property: stdClass::$meta C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\EntityFields.php 40
[2018-04-10 11:17:47] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Call to a member function getId() on null; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 131
[2018-04-10 11:19:46] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `GET https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder/1` resulted in a `404 Not Found` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\s; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:21:15] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'MoySklad\Exceptions\IncompleteCreationFieldsException' with message 'Entity MoySklad\Entities\Documents\Orders\PurchaseOrder requires these fields to be created: ["organization","agent"], has no these fields at the moment: ["organization","agent"]' in C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php:334
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\MutationBuilders\CreationBuilder.php(28): MoySklad\Entities\AbstractEntity->validateFieldsRequiredForCreation()
#1 C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php(210): MoySklad\Components\MutationBuilders\CreationBuilder->execute()
#2 C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php(89): MoySklad\Entities\AbstractEntity->create()
#3 C:\AppServ\www\mammyclub\app\controllers\admin\xadmin_supplierrequest.php(591): MoySkladManager->createPurchaseOrder(Array)
#4 [internal function]: xAdmin_SupplierRequest->print_supplier_request('1')
#5 C:\; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 334
[2018-04-10 11:21:56] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:22:05] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:22:20] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:27:34] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:28:50] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Warning  --> Invalid argument supplied for foreach() C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 26
[2018-04-10 11:28:50] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Warning  --> Invalid argument supplied for foreach() C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 26
[2018-04-10 11:28:50] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:32:53] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Warning  --> Invalid argument supplied for foreach() C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 26
[2018-04-10 11:32:53] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Warning  --> Invalid argument supplied for foreach() C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 26
[2018-04-10 11:32:53] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:32:59] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Warning  --> Invalid argument supplied for foreach() C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 26
[2018-04-10 11:32:59] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Warning  --> Invalid argument supplied for foreach() C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 26
[2018-04-10 11:32:59] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:35:43] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Warning  --> Invalid argument supplied for foreach() C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 26
[2018-04-10 11:35:43] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Warning  --> Invalid argument supplied for foreach() C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 26
[2018-04-10 11:35:43] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:35:48] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Warning  --> Invalid argument supplied for foreach() C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 26
[2018-04-10 11:35:48] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Warning  --> Invalid argument supplied for foreach() C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 26
[2018-04-10 11:35:48] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:41:04] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:41:33] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:41:37] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:41:44] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Class 'RequestLog' not found; FILE: C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php at line: 93
[2018-04-10 11:42:45] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:43:06] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:49:41] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:49:45] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:49:56] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:50:15] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined variable: purchaseOrder C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 102
[2018-04-10 11:51:13] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:51:41] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:51:44] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:51:55] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:52:25] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:52:36] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:52:43] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:53:03] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:53:09] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `400 Bad Request` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 11:53:33] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined variable: purchaseOrder C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 102
[2018-04-10 11:54:13] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined variable: e C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 95
[2018-04-10 11:54:14] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Call to a member function getResponse() on null; FILE: C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php at line: 95
[2018-04-10 11:54:24] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined variable: purchaseOrder C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 103
[2018-04-10 11:55:45] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined variable: purchaseOrder C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 101
[2018-04-10 11:56:02] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined variable: purchaseOrder C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 101
[2018-04-10 11:56:18] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined variable: purchaseOrder C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 101
[2018-04-10 11:56:37] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined variable: purchaseOrder C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 101
[2018-04-10 11:58:27] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Call to undefined method MoySklad\Entities\Counterparty::getHref(); FILE: C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php at line: 27
[2018-04-10 11:59:03] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined variable: purchaseOrder C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 101
[2018-04-10 11:59:22] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined variable: purchaseOrder C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 101
[2018-04-10 12:09:17] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: 4096  --> Argument 1 passed to MoySklad\Entities\AbstractEntity::getMetaData() must be an instance of MoySklad\MoySklad, none given, called in C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php on line 23 and defined C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php 303
[2018-04-10 12:09:17] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined variable: sklad C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php 304
[2018-04-10 12:09:17] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest FATAL ERROR: Call to a member function getClient() on null; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 304
[2018-04-10 12:12:10] ERROR - IP:127.0.0.1 FATAL ERROR: Call to undefined method MoySklad\Entities\Counterparty::transformItemsToMetaClass(); FILE: C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php at line: 23
[2018-04-10 12:13:10] ERROR - IP:127.0.0.1 Severity: Warning  --> Invalid argument supplied for foreach() C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 26
[2018-04-10 12:13:10] ERROR - IP:127.0.0.1 Severity: Warning  --> Invalid argument supplied for foreach() C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 26
[2018-04-10 12:13:11] ERROR - IP:127.0.0.1 FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ServerException' with message 'Server error: `POST https://online.moysklad.ru/api/remap/1.1/entity/purchaseorder` resulted in a `500 Internal Server Error` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 12:16:04] ERROR - IP:127.0.0.1 FATAL ERROR: Call to undefined method MoySklad\Entities\Documents\Orders\PurchaseOrder::addCounterparty(); FILE: C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php at line: 31
[2018-04-10 12:25:26] ERROR - IP:127.0.0.1 Severity: Notice  --> Undefined property: MoySkladManager::$sklad C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 22
[2018-04-10 12:25:26] ERROR - IP:127.0.0.1 Severity: 4096  --> Argument 1 passed to MoySklad\Entities\AbstractEntity::getMetaData() must be an instance of MoySklad\MoySklad, null given, called in C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php on line 22 and defined C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php 303
[2018-04-10 12:25:26] ERROR - IP:127.0.0.1 FATAL ERROR: Call to a member function getClient() on null; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 304
[2018-04-10 12:26:27] ERROR - IP:127.0.0.1 FATAL ERROR: Call to undefined method MoySklad\Entities\Documents\Orders\PurchaseOrder::addCounterparty(); FILE: C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php at line: 31
[2018-04-10 12:27:01] ERROR - IP:127.0.0.1 FATAL ERROR: Call to undefined method MoySklad\Entities\Documents\Orders\PurchaseOrder::addCounterparty(); FILE: C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php at line: 32
[2018-04-10 12:27:32] ERROR - IP:127.0.0.1 FATAL ERROR: Call to undefined method MoySklad\Entities\Documents\Orders\PurchaseOrder::addCounterparty(); FILE: C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php at line: 32
[2018-04-10 12:28:19] ERROR - IP:127.0.0.1 FATAL ERROR: Call to undefined method MoySklad\Entities\Documents\Orders\PurchaseOrder::addCounterparty(); FILE: C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php at line: 32
[2018-04-10 12:29:26] ERROR - IP:127.0.0.1 FATAL ERROR: Call to undefined method MoySklad\Entities\Documents\Orders\PurchaseOrder::addCounterparty(); FILE: C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php at line: 36
[2018-04-10 12:38:07] ERROR - IP:127.0.0.1 Severity: Warning  --> Creating default object from empty value C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 23
[2018-04-10 12:38:07] ERROR - IP:127.0.0.1 Severity: Warning  --> Creating default object from empty value C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 24
[2018-04-10 13:11:58] ERROR - IP:127.0.0.1 Severity: Warning  --> Creating default object from empty value C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 23
[2018-04-10 13:11:58] ERROR - IP:127.0.0.1 Severity: Warning  --> Creating default object from empty value C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 24
[2018-04-10 13:11:58] ERROR - IP:127.0.0.1 Severity: Notice  --> Undefined property: stdClass::$type C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 44
[2018-04-10 13:12:13] ERROR - IP:127.0.0.1 Severity: Warning  --> Creating default object from empty value C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 23
[2018-04-10 13:12:13] ERROR - IP:127.0.0.1 Severity: Warning  --> Creating default object from empty value C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 24
[2018-04-10 13:12:14] ERROR - IP:127.0.0.1 FATAL ERROR: Call to undefined method MoySklad\Entities\Counterparty::getType(); FILE: C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php at line: 26
[2018-04-10 13:12:28] ERROR - IP:127.0.0.1 Severity: Warning  --> Creating default object from empty value C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 23
[2018-04-10 13:12:28] ERROR - IP:127.0.0.1 Severity: Warning  --> Creating default object from empty value C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 24
[2018-04-10 13:12:29] ERROR - IP:127.0.0.1 FATAL ERROR: Call to undefined method stdClass::getType(); FILE: C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php at line: 26
[2018-04-10 13:13:22] ERROR - IP:127.0.0.1 Severity: Warning  --> Creating default object from empty value C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 23
[2018-04-10 13:13:22] ERROR - IP:127.0.0.1 Severity: Warning  --> Creating default object from empty value C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 24
[2018-04-10 13:13:23] ERROR - IP:127.0.0.1 FATAL ERROR: Call to undefined method stdClass::getType(); FILE: C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php at line: 26
[2018-04-10 13:13:32] ERROR - IP:127.0.0.1 FATAL ERROR: Call to undefined method stdClass::getType(); FILE: C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php at line: 24
[2018-04-10 13:13:44] ERROR - IP:127.0.0.1 Severity: Notice  --> Undefined property: stdClass::$type C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\Fields\AbstractFieldAccessor.php 44
[2018-04-10 13:22:18] ERROR - IP:127.0.0.1 FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `GET https://online.moysklad.ru/api/remap/1.1/entity/counterparty/ad420481-39cc-11e8-9ff4-34e800190912` resulted in a `404 Not Found` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclu; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 13:24:45] ERROR - IP:127.0.0.1 FATAL ERROR: Uncaught exception 'MoySklad\Exceptions\IncompleteCreationFieldsException' with message 'Entity MoySklad\Entities\Documents\Orders\PurchaseOrder requires these fields to be created: ["organization","agent"], has no these fields at the moment: ["agent"]' in C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php:334
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\MutationBuilders\CreationBuilder.php(28): MoySklad\Entities\AbstractEntity->validateFieldsRequiredForCreation()
#1 C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php(210): MoySklad\Components\MutationBuilders\CreationBuilder->execute()
#2 C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php(36): MoySklad\Entities\AbstractEntity->create()
#3 C:\AppServ\www\mammyclub\app\controllers\admin\xadmin_supplierrequest.php(591): MoySkladManager->createPurchaseOrder(Array)
#4 [internal function]: xAdmin_SupplierRequest->print_supplier_request('1')
#5 C:\AppServ\www\mam; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 334
[2018-04-10 13:24:54] ERROR - IP:127.0.0.1 FATAL ERROR: Uncaught exception 'MoySklad\Exceptions\IncompleteCreationFieldsException' with message 'Entity MoySklad\Entities\Documents\Orders\PurchaseOrder requires these fields to be created: ["organization","agent"], has no these fields at the moment: ["agent"]' in C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php:334
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\MutationBuilders\CreationBuilder.php(28): MoySklad\Entities\AbstractEntity->validateFieldsRequiredForCreation()
#1 C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php(210): MoySklad\Components\MutationBuilders\CreationBuilder->execute()
#2 C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php(36): MoySklad\Entities\AbstractEntity->create()
#3 C:\AppServ\www\mammyclub\app\controllers\admin\xadmin_supplierrequest.php(591): MoySkladManager->createPurchaseOrder(Array)
#4 [internal function]: xAdmin_SupplierRequest->print_supplier_request('1')
#5 C:\AppServ\www\mam; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 334
[2018-04-10 13:25:04] ERROR - IP:127.0.0.1 FATAL ERROR: Uncaught exception 'MoySklad\Exceptions\IncompleteCreationFieldsException' with message 'Entity MoySklad\Entities\Documents\Orders\PurchaseOrder requires these fields to be created: ["organization","agent"], has no these fields at the moment: ["agent"]' in C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php:334
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\MutationBuilders\CreationBuilder.php(28): MoySklad\Entities\AbstractEntity->validateFieldsRequiredForCreation()
#1 C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php(210): MoySklad\Components\MutationBuilders\CreationBuilder->execute()
#2 C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php(36): MoySklad\Entities\AbstractEntity->create()
#3 C:\AppServ\www\mammyclub\app\controllers\admin\xadmin_supplierrequest.php(591): MoySkladManager->createPurchaseOrder(Array)
#4 [internal function]: xAdmin_SupplierRequest->print_supplier_request('1')
#5 C:\AppServ\www\mam; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 334
[2018-04-10 13:25:20] ERROR - IP:127.0.0.1 FATAL ERROR: Uncaught exception 'MoySklad\Exceptions\IncompleteCreationFieldsException' with message 'Entity MoySklad\Entities\Documents\Orders\PurchaseOrder requires these fields to be created: ["organization","agent"], has no these fields at the moment: ["organization"]' in C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php:334
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\MutationBuilders\CreationBuilder.php(28): MoySklad\Entities\AbstractEntity->validateFieldsRequiredForCreation()
#1 C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php(210): MoySklad\Components\MutationBuilders\CreationBuilder->execute()
#2 C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php(36): MoySklad\Entities\AbstractEntity->create()
#3 C:\AppServ\www\mammyclub\app\controllers\admin\xadmin_supplierrequest.php(591): MoySkladManager->createPurchaseOrder(Array)
#4 [internal function]: xAdmin_SupplierRequest->print_supplier_request('1')
#5 C:\AppServ\; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 334
[2018-04-10 13:25:25] ERROR - IP:127.0.0.1 FATAL ERROR: Uncaught exception 'MoySklad\Exceptions\IncompleteCreationFieldsException' with message 'Entity MoySklad\Entities\Documents\Orders\PurchaseOrder requires these fields to be created: ["organization","agent"], has no these fields at the moment: ["organization"]' in C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php:334
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\MutationBuilders\CreationBuilder.php(28): MoySklad\Entities\AbstractEntity->validateFieldsRequiredForCreation()
#1 C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php(210): MoySklad\Components\MutationBuilders\CreationBuilder->execute()
#2 C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php(36): MoySklad\Entities\AbstractEntity->create()
#3 C:\AppServ\www\mammyclub\app\controllers\admin\xadmin_supplierrequest.php(591): MoySkladManager->createPurchaseOrder(Array)
#4 [internal function]: xAdmin_SupplierRequest->print_supplier_request('1')
#5 C:\AppServ\; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 334
[2018-04-10 14:05:55] ERROR - IP:127.0.0.1 Severity: 4096  --> Argument 1 passed to MoySklad\Components\MutationBuilders\AbstractMutationBuilder::addOrganization() must be an instance of MoySklad\Entities\Organization, instance of MoySklad\Entities\Counterparty given, called in C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php on line 36 and defined C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\MutationBuilders\AbstractMutationBuilder.php 193
[2018-04-10 14:05:55] ERROR - IP:127.0.0.1 FATAL ERROR: Uncaught exception 'MoySklad\Exceptions\IncompleteCreationFieldsException' with message 'Entity MoySklad\Entities\Documents\Orders\PurchaseOrder requires these fields to be created: ["organization","agent"], has no these fields at the moment: ["organization"]' in C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php:334
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Components\MutationBuilders\CreationBuilder.php(28): MoySklad\Entities\AbstractEntity->validateFieldsRequiredForCreation()
#1 C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php(37): MoySklad\Components\MutationBuilders\CreationBuilder->execute()
#2 C:\AppServ\www\mammyclub\app\controllers\admin\xadmin_supplierrequest.php(591): MoySkladManager->createPurchaseOrder(Array)
#3 [internal function]: xAdmin_SupplierRequest->print_supplier_request('1')
#4 C:\AppServ\www\mammyclub\lib\codeigniter\CodeIgniter.php(206): call_user_func_array(Array, Array)
#5 C:\AppServ\www\mammyclub\index.php(186): requ; FILE: C:\AppServ\www\mammyclub\lib\vendor\tooyz\moysklad\src\Entities\AbstractEntity.php at line: 334
[2018-04-10 14:06:15] ERROR - IP:127.0.0.1 FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `GET https://online.moysklad.ru/api/remap/1.1/entity/organization/ad420aab-39cc-11e8-9ff4-34e800190916` resulted in a `404 Not Found` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclu; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 14:07:34] ERROR - IP:127.0.0.1 FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `GET https://online.moysklad.ru/api/remap/1.1/entity/organization/ad420aab-39cc-11e8-9ff4-34e800190916` resulted in a `404 Not Found` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclu; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 14:09:39] ERROR - IP:127.0.0.1 FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `GET https://online.moysklad.ru/api/remap/1.1/entity/organization/cG99GjfEi3tWRrvtcid8M1` resulted in a `404 Not Found` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\g; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 14:13:07] ERROR - IP:127.0.0.1 FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `GET https://online.moysklad.ru/api/remap/1.1/entity/organization/2940008275` resulted in a `404 Not Found` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\pr; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 14:13:40] ERROR - IP:127.0.0.1 FATAL ERROR: Uncaught exception 'GuzzleHttp\Exception\ClientException' with message 'Client error: `GET https://online.moysklad.ru/api/remap/1.1/entity/organization/2940008275` resulted in a `404 Not Found` response' in C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113
Stack trace:
#0 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Middleware.php(66): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response))
#1 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response))
#2 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array)
#3 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}()
#4 C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\pr; FILE: C:\AppServ\www\mammyclub\lib\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php at line: 113
[2018-04-10 15:29:47] ERROR - IP:127.0.0.1 Severity: Notice  --> Undefined variable: purchaseOrder C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 41
[2018-04-10 15:30:35] ERROR - IP:127.0.0.1 REFERER: http://localhost.com/madmin/supplierrequest Severity: Notice  --> Undefined variable: purchaseOrder C:\AppServ\www\mammyclub\app\logic\MoySkladManager.php 41
