<?php

namespace MiniWiki;

class WikiServiceProvider implements \Silex\ServiceProviderInterface
{
    public function register(\Silex\Application $app)
    {
        $app['wiki'] = $app->share(function ($app) {
            return new Wiki($app['article_path']);
        });
        $app['controller.wiki'] = $app->share(function ($app) {
            return new WikiController($app['wiki'], $app['twig'], $app['url_generator']);
        });
    }

    public function boot(\Silex\Application $app)
    {
    }
}