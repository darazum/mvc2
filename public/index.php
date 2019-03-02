<?php
ini_set('include_path',
    ini_get('include_path') . PATH_SEPARATOR .
    '../App'
);

require '../Base/init.php';
$app = new \Base\Application($appConfig);
$app->run();