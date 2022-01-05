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

        $dernierjeu = $repoJeux->findAll(); // On pioche les derniers jeux ajoutés pour les afficher sur la page home

        return $this->render('game/home.html.twig', [
            'title' => 'Bienvenue sur GamesProject',
            'dernierjeu' => $dernierjeu
        ]);
    }

    #[Route('/categorie', name: 'game_categorie')]
    public function gameCategorie(CategoryRepository $repoCategory): Response
    {

        $categorie = $repoCategory->findAll(); // Select * FROM category + FETCH ALL

        // dd($categorie);

        return $this->render('game/categorie.html.twig', [
            'categorie' => $categorie
        ]);
    }

    #[Route('/jeux', name: 'game_jeux')]
    public function gameJeux(JeuxRepository $repoJeux, ): Response
    {
        $jeu = $repoJeux->findAll();

        return $this->render('game/jeux.html.twig',[
            'jeu' => $jeu
        ]);
    }

    #[Route('/jeux/{id}', name: 'game_jeux_cat')]
    public function gameJeuxCat(Category $categorie):Response
    {
        // quand l'utilisateur cliquera sur une catégorie  cela déclenchera la condition IF
        if($categorie)
        {
            // Grace aux relations bi-directgionnelle, lorsque nous selectionnons une catégorie en BDD, cela donne
            // accès à tous les jeux liés à cette catégorie
            // GetJeux() -> array multi contenant tous les jeux liés à la catéghorie transmise dans l'URL
            $jeu = $categorie->getJeux();
        }

        return $this->render('game/jeux.html.twig', [
            'jeu' => $jeu
        ]);
    }
}