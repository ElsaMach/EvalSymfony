<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\BlogPost;
use App\Form\BlogPostType;
use App\Repository\BlogPostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog", methods={"GET"})
     */
    public function index(BlogPostRepository $blogPostRepository): Response
    {
        //Fonction qui retourne l'index et la page principale du blog
        return $this->render('blog/index.html.twig', [
            'blogposts' => 'BlogController',
        ]);
    }

     /**
     * @Route("/blog/list", name="blog_list", methods={"GET"})
     */
    public function listPostsAction(BlogPostRepository $blogPostRepository): Response
    {
        //Fonction qui retourne la liste des posts 
        return $this->render('content.html.twig', [
            'blogposts' => $blogPostRepository->findAll(),
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blogpost_show", methods={"GET"})
     * @Route("/blog/slug", name="blogpost_show", methods={"GET"})
     *  @Route("/blog/date/slug", name="blogpost_show", methods={"GET"})
     */
    public function showPostAction(BlogPost $blogPost): Response
    {
        //Fonction qui retourne un post avec la posibilité d'utiliser plusieurs routes
        return $this->render('blog/show.html.twig', [
            'blogpost' => $blogPost,
        ]);
    }


      /**
     * @Route("/admin/new-post", name="blog_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        //Création d'un nouveau post réservé à l'admin 
        $blogpost = new BlogPost();
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blogPost);
            $entityManager->flush();

            return $this->redirectToRoute('blog_list');
        }

        return $this->render('blog/new.html.twig', [
            'blogpost' => $blogPost,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/update-post/{id}", name="blogpost_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BlogPost $blogPost): Response
    {
        //Modification d'un post réservé à l'admin 
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_list');
        }

        return $this->render('blog/edit.html.twig', [
            'blogpost' => $blogPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete-post/{id}", name="blogpost_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BlogPost $blogPost): Response
    {
        //Suppression d'un post réservé à l'admin
        if ($this->isCsrfTokenValid('delete'.$blogPost->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($blogPost);
            $entityManager->flush();
        }

        return $this->redirectToRoute('blog_list');
    }

}
