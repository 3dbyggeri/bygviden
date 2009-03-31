<?php

// set the current environment to development if the system environment variable BYGVIDEN_ENV is not set
if($_ENV['BYGVIDEN_ENV'] == "")
  $_ENV['BYGVIDEN_ENV'] = 'development';

// load simple YAML parser
require_once($_SERVER['DOCUMENT_ROOT'] . '/../lib/spyc.php');

// load the database.yml file
$db = Spyc::YAMLLoad(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../config/database.yml'));

// scope the database settings to the correct environment
$db = $db[$_ENV['BYGVIDEN_ENV']];

// populate global database constants
define('DATABASE_NAME', $db['database']);
define('DATABASE_HOST', $db['host']);
define('DATABASE_USER', $db['username']);
define('DATABASE_PASS', $db['password']);
define('DATABASE_PREFIX', 'dev_');
