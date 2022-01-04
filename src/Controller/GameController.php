<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\JeuxRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(JeuxRepository $repoJeux): Response
    {

        $dernierjeu = $repoJeux->findAll();

        return $this->render('game/home.html.twig', [
            'title' => 'Bienvenue sur GamesProject',
            'dernierjeu' => $dernierjeu
        ]);
    }

    #[Route('/categorie', name: 'game_categorie')]
    public function gameCategorie(CategoryRepository $repoCategory): Response
    {

        $categorie = $repoCategory->findAll();

        // dd($categorie);

        return $this->render('game/categorie.html.twig', [
            'categorie' => $categorie
        ]);
    }

    #[Route('/jeux', name: 'game_jeux')]
    public function gameJeux(JeuxRepository $repoJeux): Response
    {
        $jeu = $repoJeux->findAll();

        return $this->render('game/jeux.html.twig',[
            'jeu' => $jeu
        ]);
    }

    #[Route('/jeux/{id}', name: 'game_jeux_cat')]
    public function gameJeuxCat(JeuxRepository $repoJeux, Category $categorie):Response
    {

        if($categorie)
        {
            $jeu = $categorie->getJeux();
        }

        return $this->render('game/jeux.html.twig', [
            'jeu' => $jeu
        ]);
    }
}