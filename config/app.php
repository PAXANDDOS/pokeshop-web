<?php

define('APP_NAME', getenv('APP_NAME') ? getenv('APP_NAME') : 'PokeShop');
define('APP_ENV', getenv('APP_ENV') ? getenv('APP_ENV') : 'local');
define('APP_URL', getenv('APP_URL') ? getenv('APP_URL') : 'http://localhost:3000');

define('APP_ROOT', dirname(dirname(__FILE__)));
define('APP_CONTROLLERS', APP_ROOT . '/app/Http/Controllers/');
define('APP_MIDDLEWARE', APP_ROOT . '/app/Http/Middleware/');
define('APP_MODELS', APP_ROOT . '/app/Models/');
define('APP_VIEWS', APP_ROOT . '/resources/views/');
define('APP_FRAMEWORK', APP_ROOT . '/framework/');