<?php

define('LIB_DIR', 'lib/');

// Register generation begintime
require(LIB_DIR . 'tic.php');

// Set decent error reporting
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Include template engine
require_once(LIB_DIR . 'Twig/Autoloader.php');
Twig_Autoloader::register();
$twig = new Twig_Environment(new Twig_Loader_Filesystem('templates/'), array(
    'cache' => '/tmp/vcieCache/',
));

// Include form-validation
require_once(LIB_DIR . 'formvalidation.php');

// Start sessiebeheer
session_start();

// Defineer ene leuke secret key
define('SECRET_KEY', 'hfFmh3L8XOVq0gfAcDf3K8mFABdTjlrpGYrTB5zKQPJ14kOxrvZK353falHE8L00E');

// Stel tijdzone in (Twig heeft graag dat je dit expliciet vertelt)
date_default_timezone_set('Europe/Amsterdam');

// Maak een CSRFToken
if (! array_key_exists('csrftoken', $_SESSION)) {
	$_SESSION['csrftoken'] = sha1(mt_rand() . SECRET_KEY);
}
$twig->addGlobal('csrf_token', $_SESSION['csrftoken']);
$twig->addGlobal('csrf_token_tag', '<input type="hidden" name="csrfmiddlewaretoken" value="' . $_SESSION['csrftoken'] . '" />');

// Welke email-adressen zouden emails moeten ontvangen?
$reservering_emailadres = "Kode Kolonel <daan@voorraadcie.nl>";
