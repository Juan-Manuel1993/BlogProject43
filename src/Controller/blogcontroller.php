<?php

namespace App\Controller;


use App\Entity\Article;
use App\Entity\User;
use App\Entity\Tag;
use App\Form\ArticleType;
use FOS\UserBundle\Model;
use Symfony\Form\Extension\Core\Type\IntegerType;
use Symfony\Form\Extension\Core\Type\DateType;
use Symfony\Form\Extension\Core\Type\SubmitType;
use Symfony\Form\Extension\Core\Type\TextType;
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


      /**
     * @Route("/new", name="create_article")
     */
    public function createArticle(): Response
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
/*

    public function new(Request $request)
    {
        // creates a task object and initializes some data for this example
        $user = new Task();
        $task->setTask('Write a blog post');
        $task->setDueDate(new \DateTime('tomorrow'));

        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();

        // ...
    }

    */

     /**
     * @Route("/getforms",name="getforms")
     */
    public function getforms(): Response  
    {

        $repository = $this->getDoctrine()->getRepository(User::class);

        $user = $repository->find(1);

        //$userManager = $this->UserManager;
       /* $userManager = $this->get('fos_user.userManager');
        $users = $userManager->findUsers();
       */
       return new Response('email found : '.$user->getEmail());   

    }


}


