<?php

// load simple YAML parser
require_once($_SERVER['DOCUMENT_ROOT'] . '/../lib/spyc.php');

// load the database.yml file
$db = Spyc::YAMLLoad(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../config/database.yml'));

// populate global database constants
define('DATABASE_NAME', $db['development']['database']);
define('DATABASE_HOST', $db['development']['host']);
define('DATABASE_USER', $db['development']['username']);
define('DATABASE_PASS', $db['development']['password']);
define('DATABASE_PREFIX', 'dev_');
