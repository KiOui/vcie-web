<?php

define('LIB_DIR', 'lib/');

// Register generation begintime
require(LIB_DIR . 'tic.php');

// Set decent error reporting
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Define usefulle meuk
$H2O_OPTIONS =  array(
	'searchpath' => 'templates/',
	'cache' => 'file',
	'cache_prefix' => 'vcieCache_',
	'cache_dir' => 'cache/'
);

// Include h2o template engine
require_once(LIB_DIR . 'h2o.php');

// Include form-validation
require_once(LIB_DIR . 'formvalidation.php');

// Defineer ene leuke secret key
define('SECRET_KEY', 'hfFmh3L8XOVq0gfAcDf3K8mFABdTjlrpGYrTB5zKQPJ14kOxrvZK353falHE8L00E');

// Welke email-adressen zouden emails moeten ontvangen?
$reservering_emailadres = "Kode Kolonel <daan@voorraadcie.nl>";
