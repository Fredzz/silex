<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../resources/classes/requires.php';
require_once __DIR__ . '/../resources/local.php';

//use Silex\Provider\TwigServiceProvider;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();
$app['debug'] = true;

// Register Form
$app->register(new FormServiceProvider());


// Register Translation
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallback' => 'en',
));

// Register URL Generator
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// Register Twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../resources/views'
));

// Register Doctrine
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'host' => DB_HOST,
        'user' => DB_USER,
        'password' => DB_PASS,
        'dbname' => DB_NAME
    ),
));

//Web root route
$app->get('/', function() use ($app) {
            return $app['twig']->render('index.twig');
        })->bind('home');

// Handle 404 by redirecting to homepage
$app->error(function (\Exception $e, $code) use ($app) {
            if (404 == $code) {
                return $app['twig']->render('index.twig');
            }
        });

// Require other routes
require '../resources/routes/authors.php';
require '../resources/routes/posts.php';


$app->run();