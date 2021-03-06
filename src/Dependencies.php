<?php declare(strict_types = 1);

$injector = new \Auryn\Injector;

$injector->alias('Http\Request', 'Http\HttpRequest');
$injector->share('Http\HttpRequest');
$injector->define('Http\HttpRequest', [
    ':get' => $_GET,
    ':post' => $_POST,
    ':cookies' => $_COOKIE,
    ':files' => $_FILES,
    ':server' => $_SERVER,
]);

$injector->alias('Http\Response', 'Http\HttpResponse');
$injector->share('Http\HttpResponse');

// $injector->alias('Underground\Template\Renderer', 'Underground\Template\MustacheRenderer');
$injector->define('Mustache_Engine', [
    ':options' => [
        'loader' => new Mustache_Loader_FilesystemLoader(dirname(__DIR__) . '/templates', [
            'extension' => '.html',
        ]),
    ],
]);

$injector->define('Underground\Page\FilePageReader', [
    ':pageFolder' => __DIR__ . '/../pages',
]);

$injector->alias('Underground\Page\PageReader', 'Underground\Page\FilePageReader');
$injector->share('Underground\Page\FilePageReader');


$injector->alias('Underground\Template\Renderer', 'Underground\Template\TwigRenderer');
$injector->delegate('Twig_Environment', function () use ($injector)
{
    $loader = new Twig_Loader_Filesystem(dirname(__DIR__) . '/templates');
    $twig = new Twig_Environment($loader);
    return $twig;
});

$injector->alias('Underground\Template\FrontendRenderer', 'Underground\Template\FrontendTwigRenderer');


$injector->alias('Underground\Menu\MenuReader', 'Underground\Menu\ArrayMenuReader');
$injector->share('Underground\Menu\ArrayMenuReader');


return $injector;
