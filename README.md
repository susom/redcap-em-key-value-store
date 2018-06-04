#Stanford Key Value Store

This external modules is intended to store secrets in the External Modules database table



In order to load a KVS object, insert this line into your code after installing this module and enabling it.
You do not need to enable it for a specific project, only from the control center.
```php
// This is how you access an EM class from other code in REDCap
$KVS = class_exists("Stanford\KVS\KVS") ? new \Stanford\KVS\KVS : \ExternalModules\ExternalModules::getModuleInstance('stanford_key_value_store');
```

Then, to store and retrieve values, simply call:
```php
// This is how you can use the KVS class to get and store parameters:

$project_id = 5;    // Usually this will be a global variable you should already have access to

$KVS->setValue($project_id, "FOO", "BAR");
// In the external_modules_settings table, you will now have a value for FOO with an encrypted version of BAR


// Later in your code, you can retrieve BAR as:
$value = $KVS->getValue($project_id, "FOO");
```

If the $project_id is not provided, it will return FALSE.