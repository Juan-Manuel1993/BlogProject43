<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\Tag;
use App\Form\ArticleType;
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
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

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
