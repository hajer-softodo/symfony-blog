<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Article;
use App\Entity\Comment;
use  App\Repository\ArticleRepository;
use  App\Form\ArticleType;
use  App\Form\CommentType;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    public function index(ArticleRepository $repo): Response
    {
       
        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }
     /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig',[
            'title' => "bienvenue ici les amis !",
            'age' => 31
        ]);
    }

    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function form(Article $article=null,Request $request,EntityManagerInterface $manager)
    {
    //    if($request->request->count()){
    //     $article=new Article();
    //     $article->setTitle($request->request->get('title'))
    //             ->setContent($request->request->get('content'))
    //             ->setImage($request->request->get('image'))
    //             ->setCreatedAt(new \DateTime());

    //             $manager->persist($article);
    //             $manager->flush();
    //             return $this->redirectToRoute('blog/create.html.twig', [
    //                 'id' => $article->getId()
    //             ]);
    //    }
        if(!$article){
            $article =new Article();
        }
        
        // $form = $this->createFormBuilder($article)
        //              ->add('title', TextType::class)
        //              ->add('content', TextareaType::class)
        //              ->add('image', TextType::class)
                    //  ->add('save', SubmitType::class, [
                    //     'label' => "Enregistrer"
                    //  ])
                    //  ->getForm();

            $form = $this->createForm(ArticleType::class, $article);  
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                if(!$article->getId()){
                    $article->setCreatedAt(new \DateTime());
                }
                
                $manager->persist($article);
                $manager->flush();
                return $this->redirectToRoute('blog_show',[
                    'id' => $article->getId()
                ]);
            }
       
        return $this->render('blog/create.html.twig',[
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() != null
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article, Request $request, EntityManagerInterface $manager)
    {
        // $repo = $this->getDoctrine()->getRepository(Article::class);
    $comment = new Comment();
    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
        $comment->setCreatedAt(new \DateTime())
                ->setArticle($article);
        $manager->persist($comment);
        $manager->flush();
        return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
       }
        return $this->render('blog/show.html.twig' ,[
            'article' => $article,
            'commentForm' => $form->createView()
        ]);
    }

     

}
