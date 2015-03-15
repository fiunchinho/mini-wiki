<?php

namespace MiniWiki;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WikiController
{
    public function __construct(Wiki $wiki_service, \Twig_Environment $templating, $url_generator)
    {
        $this->wiki             = $wiki_service;
        $this->templating       = $templating;
        $this->url_generator    = $url_generator;
    }

    public function get($slug)
    {
        return $this->templating->render(
            'article.html',
            ['text' => $this->wiki->getArticle($slug)]
        );
    }

    public function post(Request $request, $slug)
    {
        $this->checkConditionalPost($request, $slug);

        if ($this->wiki->writeArticle($slug, $request->request->get('text'))) {
            return $this->redirect($this->url_generator->generate("article_get", ['slug' => $slug]));
        }else{
            throw new \RunTimeException("Error writing the article");
        }
    }

    public function admin($slug)
    {
        return $this->templating->render(
            'admin.html',
            ['slug' => $slug, 'text' => $this->wiki->getArticle($slug)]
        );
    }

    private function redirect($url)
    {
        return new RedirectResponse($url, Response::HTTP_SEE_OTHER);
    }

    private function checkConditionalPost(Request $request, $slug)
    {
        $incoming_etag  = $request->headers->get('if-match');
        if ((!is_null($incoming_etag)) && ($incoming_etag !== md5($this->get($slug)))) {
            throw new HttpException(Response::HTTP_PRECONDITION_FAILED, "You didn't have the latest version of the article");
        }
    }
}