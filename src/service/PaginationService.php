<?php


namespace App\service;


use App\Entity\Article;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class PaginationService
{

        public function verifyArticles( $articles, PaginatorInterface $paginator, Request $request)
        {
            $articles = $paginator->paginate(
                $articles, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                4 /*limit per page*/
            );
            $articles->setTemplate('@KnpPaginator/Pagination/twitter_bootstrap_v4_pagination.html.twig');
            return $articles;
        }
}