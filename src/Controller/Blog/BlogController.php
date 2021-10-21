<?php

namespace App\Controller\Blog;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Tags;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagsRepository;
use App\service\PaginationService;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    Private ArticleRepository $articleRepository;
    Private TagsRepository $tagsRepository;
    Private CategoryRepository $categoryRepository;
    private array $tags;
    private array $categories;
    private PaginationService  $paginationService;
    /**
     * @var FlashyNotifier
     */
    private FlashyNotifier $flashy;

    /**
     * BlogController constructor.
     * @param ArticleRepository $articleRepoe
     * @param TagsRepository $tagRepo
     * @param CategoryRepository $categorRepoe
     * @param FlashyNotifier $flashy
     * @param PaginationService $paginationService
     */
    public function __construct(ArticleRepository $articleRepoe, TagsRepository $tagRepo,
                                CategoryRepository $categorRepoe, FlashyNotifier $flashy,
                                PaginationService  $paginationService)
    {
        $this->articleRepository  =  $articleRepoe;
        $this->tagsRepository  =  $tagRepo;
        $this->categoryRepository  =  $categorRepoe;
        $this->flashy = $flashy;
        $this->tags = $this->tagsRepository->findAll();
        $this->categories = $this->categoryRepository->findAll();
        $this->paginationService = $paginationService;

    }

    /**
     * @Route("/blog", name="blog")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function blog(PaginatorInterface $paginator, Request $request): Response
    {
        $articles = $this->articleRepository->findAll();
        if($articles)
        {
            $articles = $this->paginationService->verifyArticles( $articles,  $paginator,  $request);
        }

        return $this->render('frontend/blog/blog.html.twig', [
            'articles' => $articles,
            'tags' => $this->tags,
            'categories' => $this->categories,
        ]);
    }

    /**
     * @Route("/blog/category/{slug}", name="blog_category", methods={"GET"})
     * @param Category|null $category
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function articleCategory(?Category $category, PaginatorInterface $paginator, Request $request): Response
    {
        if(!$category) return $this->redirectToRoute('blog');
        $articles = $category->getPostsCategory();
        if($articles)
        {
            $articles = $this->paginationService->verifyArticles( $articles,  $paginator,  $request);

            return $this->render('frontend/blog/blog.html.twig', [
                'articles' => $articles,
                'tags' => $this->tags,
                'categories' => $this->categories,
                'categoryTile' => "Blog > Category ".$category->getTitle()
            ]);

        }
        return $this->redirectToRoute('blog');
    }

    /**
     * @Route("/blog/tag/{slug}", name="blog_tag")
     * @param Tags|null $tag
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function articleTags(?Tags $tag, PaginatorInterface $paginator, Request $request): Response
    {
        if($tag == null) return $this->redirectToRoute('blog', [301]);
        $articles = $tag->getArticles();
        if($articles)
        {
            $articles = $this->paginationService->verifyArticles( $articles,  $paginator,  $request);

        }
        return $this->render('frontend/blog/blog.html.twig', [
            'articles' => $articles,
            'tags' => $this->tags,
            'categories' => $this->categories,
            'tagTitle' => "Blog - Tag - ".$tag->getName()
        ]);
    }

    /**
     * @Route("/blog/article/{slug}", name="article_detail")
     * @param Article|null $article
     * @param Request $request
     * @return Response
     */
    public function ArticleDetail(?Article $article ,Request $request): Response
    {
        if(!$article) return $this->redirectToRoute('blog');
        $tags = $this->tagsRepository->findAll();
        $categories = $this->categoryRepository->findAll();
        $articles = $this->articleRepository->findBy([],['id'=>'DESC'],4);
        $comments = $article->getComments();

        //Creation du formlaire
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment, );
        $commentForm->handleRequest($request);

        //Formulaire sommise et valide
        if($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $this->flashy->success('Votre Commentaire à été bien enregistré, il est en attente de validation ');
            $comment->setArticle($article);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('article_detail', ['slug'=>$article->getSlug()]);
        }
        if($commentForm->isSubmitted() && !$commentForm->isValid()){
            $this->flashy->error('Le formulaire remplie contient des erreurs');
        }

        return $this->render('frontend/blog/article_detail.html.twig', [
            'article' => $article,
            'tags' => $tags,
            'categories' => $categories,
            'articles' => $articles,
            'comments' => $comments,
            'commentForm'=>$commentForm->createView()
        ]);
    }


}
