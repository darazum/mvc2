<?php
ini_set('include_path',
    ini_get('include_path') . PATH_SEPARATOR .
    '../App'
);

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

require '../Base/init.php';
$app = new \Base\Application($appConfig);
$app->run();

