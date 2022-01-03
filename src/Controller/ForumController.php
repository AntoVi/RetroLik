<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Comment;
use App\Entity\Category;
use App\Form\ArticlesType;
use App\Form\CommentaireType;
use App\Repository\CommentRepository;
use App\Repository\ArticlesRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForumController extends AbstractController
{
    #[Route('/forum', name: 'forum')]
    public function gameCategorie(ArticlesRepository $repoArticle, EntityManagerInterface $manager): Response
    {
        $table = $manager->getClassMetadata(Articles::class)->getFieldNames();

        $article = $repoArticle->findAll();

        // dd($categorie);

        return $this->render('forum/forum.html.twig', [
            'table' => $table,
            'article' => $article
        ]);
    }

    #[Route('/forum/article/add', name:'forum_add_article')]
    #[Route('/forum/article/{id}/edit', name:'forum_edit_article')]
    public function forumArticleAdd(Articles $article = null, Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if(!$article)
        {
            $article = new Articles; 
        }
        

        $formArt = $this->createForm(ArticlesType::class, $article);

        $formArt->handleRequest($request);

        if($formArt->isSubmitted() && $formArt->isValid())
        {
            $user = $this->getUser();

            if($article->getId())
            $texte = "Modifié";
            else 
            $texte = "Ajouté";

            if(!$article->getId())
            $article->setDate(new \DateTime());

            $article->setUser($this->getUser());

            $article->setAuteur($user->getPrenom() . ' ' . $user->getNom());

            $manager->persist($article);

            $manager->flush();

            $id = $article->getId();

            $this->addFlash('success', "l'article $id a été $texte avec succèss");
            
            return $this->redirectToRoute('forum_show', [
                'id' => $article->getId()
            ]);
        }



        return $this->render('forum/forum.article.form.html.twig', [
            'user' => $user,
            'formArt' => $formArt->createView(),
            'article' => $article,
            'editMode' => $article->getId()
        ]);
    }


    #[Route('/forum/article/{id}', name:'forum_show')]
    public function gameShow( Request $request, EntityManagerInterface $manager, Articles $article ): Response 
    {

        $user = $this->getUser();
        // dd($user);
        $comments = new Comment;

        $formCommentaires = $this->createForm(CommentaireType::class, $comments ,[
            'commentFront' => true 
        ]); 

        // dd($comments);

        $formCommentaires->handleRequest($request);

        if($formCommentaires->isSubmitted() && $formCommentaires->isValid())
        {
                
                $user = $this->getUser();
            
                $comments->setDate(new \DateTime());

                $comments->setArticles($article);
                // dd($comments);

                $comments->setAuteur( $user->getPrenom() . ' ' . $user->getNom());
                $comments->setAvatar($user->getAvatar());
                
                $manager->persist($comments); 
                $manager->flush();

                $this->addFlash('success', "Le commentaire a été posté avec succès !");

                return $this->redirectToRoute('forum_show', [
                    'id' => $article->getId()
                ]);
        }

        return $this->render('forum/forum.show.html.twig', [
            'formCommentaires' => $formCommentaires->createView(),
            'user' => $user,
            'article' => $article
        ]);
    }

    
    
}