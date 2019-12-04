<?php

namespace App\Controller;


use  App\Entity\Article;
use  App\Entity\User;
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
    public function article_add(){
        return $this->render('add.html.twig', ['controller_name' => 'blogcontroller']);
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


      /**
     * @Route("/new", name="create_article")
     */
    public function createProduct(): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $article = new Article();
        $article->setNOM('Zoro-Article');
        $article->setContenu('Bienvenu sur l\'article de zoro');


        $article->setCreatedAt(new \DateTime("02-11-1997 16:19:22"));

        $article->setUpdatedAt(new \DateTime("12-12-1999 13:08:30"));

        $article->setFeaturedImage('null');

        $article->setUserId(1);
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($article);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new article with id '.$article->getId());
    }


     /**
     * @Route("/newuser", name="create_user")
     */
    public function createUser(): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();

        $user->SetId(1);

         // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);
       

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new user with id '.$user->GetId());
    }


    /**
     * @Route("/show/{Articleid}",methods={"GET"},name="show_article")
     */
    public function showAction($Articleid): Response
{
    $article = $this->getDoctrine()
        ->getRepository(Article::class)
        ->find($Articleid);

    if (!$article) {
        throw $this->createNotFoundException(
            'No Article found for id '.$Articleid
        );
    }

    // ... do something, like pass the $product object into a template
     return new Response('Article Found with id '.$article->getContenu());
}

}

