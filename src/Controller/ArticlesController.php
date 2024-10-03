<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\AddArticleType;
use App\Form\CreateArticleType;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ArticlesController extends AbstractController
{
    private $manager;
    private $articleRepo;
    public function __construct(ManagerRegistry $manager, ArticleRepository $articleRepo)
    {
        $this->manager = $manager;
        $this->articleRepo = $articleRepo;
    }
    
    #[Route('/articles', name: 'app_articles')]
    public function articles(): Response
    {
        $articles = $this->articleRepo->findAll();

        return $this->render('articles/index.html.twig', [
            "articles" => $articles
        ]);
    }

    #[Route('/articles/create', name: 'app_article_create')]
    public function article_create(Request $request): Response
    {
        $article = new Article();

        $form = $this->createForm(AddArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if($image){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );
                $article->setImage($fichier);
            }
            $article->setCreatedAt(new DateTime('Europe/Paris'));
            $article->setUpdatedAt(new DateTime('Europe/Paris'));

            $this->manager->getManager()->persist($article);
            $this->manager->getManager()->flush();

            return $this->redirectToRoute('app_admin',[
                'id' => $this->getUser()->getUserIdentifier()
            ]);
        }

        return $this->render('articles/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/article/{id}/edit/', name: 'app_article_edit')]
    public function article_edit(Article $article, Request $request): Response
    {
        $form = $this->createForm(AddArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if($image){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );
                $article->setImage($fichier);
            }
            $article->setCreatedAt(new DateTime('Europe/Paris'));
            $article->setUpdatedAt(new DateTime('Europe/Paris'));

            $this->manager->getManager()->persist($article);
            $this->manager->getManager()->flush();

            return $this->redirectToRoute('app_admin',[
                'id' => $this->getUser()->getUserIdentifier()
            ]);
        }

        return $this->render('articles/edit.html.twig', [
            'form' => $form->createView(),
            'article' =>$article
        ]);
    }

    #[Route('/article/{id}/{slug}', name: 'app_article')]
    public function article(Article $article): Response
    {
        return $this->render('articles/article.html.twig', [
            'article' => $article
        ]);
    }

    #[Route('/article/{id}/delete', name: 'app_articles_delete')]
    public function article_delete(): Response
    {
        return $this->render('articles/index.html.twig', [
        ]);
    }
}
