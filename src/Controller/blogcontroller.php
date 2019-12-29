<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\Tag;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class blogcontroller extends AbstractController
{

    /**
    * @Route("")
    */
    public function index()
    {

        return $this->render('index.html.twig', ['controller_name' => 'blogcontroller']);
    }

    /**
    * @Route("/homepage")
    */
    public function homepage(){
        return $this->render('homepage.html.twig', ['controller_name' => 'blogcontroller']);

    }

    /**
    * @Route("/add")
    */
    public function article_add(Request $request){
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt(new \DateTime());

            $article->setCreatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager(); // On récupère l'entity manager
            $em->persist($article); // On confie notre entité à l'entity manager (on persist l'entité)
            $em->flush(); // On execute la requete

            return new Response('L\'article a bien été enregistrer.');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setLastUpdateDate(new \DateTime());

            if ($article->getPicture() !== null) {
                $file = $form->get('picture')->getData();
                $fileName =  uniqid(). '.' .$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('post_images_directory'), // Le dossier dans le quel le fichier va etre charger
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $article->setPicture($fileName);
            }

            if ($article->getIsPublished()) {
                $article->setPublicationDate(new \DateTime());
            }

            $em = $this->getDoctrine()->getManager(); // On récupère l'entity manager
            $em->persist($article); // On confie notre entité à l'entity manager (on persist l'entité)
            $em->flush(); // On execute la requete

            return new Response('L\'article a bien été enregistrer.');
        }

    	return $this->render('add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/post/{url}",methods={"GET"})
    */
    public function article_show($url){
        return $this->render('show.html.twig', ['slug' => $url]);
    }

    /**
    * @Route("/edit/{id}",methods={"GET"})
    */
    public function article_edit($id){
        return $this->render('edit.html.twig', ['slug' => $id]);
    }

    /**
    * @Route("/remove/{id}",methods={"GET"})
    */
    public function article_remove($id){
        return new Response('<h1>Delete article: ' .$id. '</h1>');
    }

}
