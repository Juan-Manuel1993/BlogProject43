<?php

namespace App\Controller;


use App\Entity\Article;
use App\Entity\Commentaires;
use App\Entity\User;
use App\Entity\Tag;
use App\Form\ArticleType;
use App\Form\CommentairesType;
use App\Form\TagType;
use App\Utils\Slugger;
use FOS\UserBundle\Model;
use Symfony\Form\Extension\Core\Type\IntegerType;
use Symfony\Form\Extension\Core\Type\DateType;
use Symfony\Form\Extension\Core\Type\SubmitType;
use Symfony\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\Query\ResultSetMapping;

class blogcontroller extends AbstractController
{

    /**
    * @Route("", name="home")
    */
    public function index(Request $request)
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(
            [],
            ['created_at' => 'desc']
        );

        return $this->render('index.html.twig', ['articles' => $articles]);
    }

    /**
    * @Route("/add", name="article_add")
    */
    public function article_add(Request $request, Slugger $slugger){
        $article = new Article();

        $user = $this->getUser();
        $article->setUser($user);

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt(new \DateTime());
            $article->setCreatedAt(new \DateTime());
            $slug = $slugger->slugify($article->getTitle());
            $article->setSlug($slug);
            if ($article->getFeaturedImage() !== null) {
                $file = $form->get('featured_image')->getData();
                $fileName =  uniqid(). '.' .$file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('post_images_directory'), // Le dossier dans le quel le fichier va etre charger
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $article->setFeaturedImage($fileName);
            }

            $em = $this->getDoctrine()->getManager(); // On récupère l'entity manager
            $em->persist($article); // On confie notre entité à l'entity manager (on persist l'entité)
            $em->flush(); // On execute la requete

            return $this->redirectToRoute('home');
        }

        return $this->render('add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/post/{articleSlug}",name="article_show", methods={"GET"})
    */
    public function article_show($articleSlug, Request $request){

        $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy(['slug' => $articleSlug]);

        $commentaires = $this->getDoctrine()->getRepository(Commentaires::class)->findBy([
            'article_id' => $article,
        ],['created_at' => 'desc']);

        if (!$article) {
            throw $this->createNotFoundException(
                'No Article found for id '.$articleSlug
            );
        }

        $commentaire = new Commentaires();

        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $commentaire->setUser($user);

            $commentaire->setArticleId($article);
            $commentaire->setCreatedAt(new \DateTime('now'));
            $commentaire->setUpdatedAt(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();

            return $this->redirectToRoute('article_show', array('articleSlug' => $articleSlug));
        }

        return $this->render('show.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
            'commentaires' => $commentaires,
        ]);
    }

    /**
    * @Route("/edit/{article}", name="article_edit")
    */
    public function article_edit(Article $article, Slugger $slugger, Request $request){

        $oldPicture = $article->getFeaturedImage();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt(new \DateTime());

            $slug = $slugger->slugify($article->getTitle());
            $article->setSlug($slug);

            if ($article->getFeaturedImage() !== null && $article->getFeaturedImage() !== $oldPicture) {
                $file = $form->get('featured_image')->getData();
                $fileName =  uniqid(). '.' .$file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('post_images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $article->setFeaturedImage($fileName);
            } else {
                $article->setFeaturedImage($oldPicture);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('home');
        }

    	return $this->render('edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/remove/{article}", name="article_remove", methods={"GET"})
    */
    public function article_remove(Article $article){
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();
        return $this->redirectToRoute('home');
    }

    /**
    * @Route("/remove/{comment}/{articleSlug}", name="comment_remove", methods={"GET"})
    */
    public function comment_remove(Commentaires $comment, $articleSlug){
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();
        return $this->redirectToRoute('article_show', array('articleSlug' => $articleSlug));
    }

    /**
    * @Route("/list_tag", name="list_tag")
    */
    public function tag(Request $request){
        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            return $this->redirectToRoute('list_tag');
        }

        $tags = $this->getDoctrine()->getRepository(Tag::class)->findBy(
            [],
            ['tag_name' => 'asc']
        );

        return $this->render('list_tag.html.twig', [
            'form' => $form->createView(),
            'tags' => $tags,
        ]);
    }

    /**
    * @Route("/remove_tag/{tag}", name="tag_remove", methods={"GET"})
    */
    public function tag_remove(Tag $tag){
        $em = $this->getDoctrine()->getManager();
        $em->remove($tag);
        $em->flush();
        return $this->redirectToRoute('list_tag');
    }

    /**
    * @Route("/tag/{tag}", name="tag", methods={"GET"})
    */
    public function tag_list($tag){

        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy([]);
        //
        // $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(
        //     ['tag' => $article],
        //     ['created_at' => 'desc']
        // );

        return $this->render('tag.html.twig', ['articles' => $articles, 'value' => $tag]);
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
     * @Route("/show/d/{Articleid}",methods={"GET"},name="show_article")
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
