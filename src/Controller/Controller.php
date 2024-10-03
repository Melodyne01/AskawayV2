<?php

namespace App\Controller;

use Twig\Environment;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Controller extends AbstractController
{
    private $manager;
    private $articleRepo;
    public function __construct(ManagerRegistry $manager, ArticleRepository $articleRepo)
    {
        $this->manager = $manager;
        $this->articleRepo = $articleRepo;
    }

    #[Route('/sitemap.xml', name: 'sitemap')]
    public function sitemap(ArticleRepository $articleRepo, Environment $twig): Response
    {
        $articles = $articleRepo->findAll();
        $urls = [];

        foreach ($articles as $article){
            array_push($urls, "https://askaway.fr/article/" . $article->getId() . "/" . $twig->getFilter('slugify')->getCallable()($article->getTitle()));
        }
        // Ajoutez ici d'autres URLs de votre projet Symfony en utilisant une boucle for ou en récupérant les données dynamiquement
        $response = $this->render('sitemap.xml.twig', [
            'urls' => $urls,
        ]);

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
    
    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $limit = 3;
        
        $articles = $this->articleRepo->findArticlesWithLimit($limit);
        return $this->render('/index.html.twig', [
            "articles" => $articles
        ]);
    }

    #[Route('/about', name: 'about')]
    public function about(Request $request): Response
    {
        
        return $this->render('/about.html.twig', [
        ]);
    }
}
