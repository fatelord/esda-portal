<?php
$db = require(__DIR__ . '/esd_db.php');
// test database! Important not to run tests on production or development databases
$db['dsn'] = 'mysql:host=localhost;dbname=yii2_basic_tests';

return $db;