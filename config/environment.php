<?php

// set the current environment to development if the system environment variable BYGVIDEN_ENV is not set
if($_SERVER['BYGVIDEN_ENV'] == "")
  $_SERVER['BYGVIDEN_ENV'] = 'development';

// load simple YAML parser
require_once($_SERVER['DOCUMENT_ROOT'] . '/../lib/spyc.php');

// load the database.yml file
$db = Spyc::YAMLLoad(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../config/database.yml'));

// scope the database settings to the correct environment
$db = $db[$_SERVER['BYGVIDEN_ENV']];

// populate global database constants
define('DATABASE_NAME', $db['database']);
define('DATABASE_HOST', $db['host']);
define('DATABASE_USER', $db['username']);
define('DATABASE_PASS', $db['password']);
define('DATABASE_PREFIX', 'dev_');

// load the buildin.yml file
$buildin = Spyc::YAMLLoad(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../config/buildin.yml'));

// scope the buildin settings to the correct environment
$buildin = $buildin[$_SERVER['BYGVIDEN_ENV']];

// populate global database constants
define('BUILDIN_HOST', $buildin['host']);
define('BUILDIN_API_KEY', $buildin['api_key']);
