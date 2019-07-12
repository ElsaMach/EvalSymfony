<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Form\BlogPostType;
use App\Repository\BlogPostRepository;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index()
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

     /**
     * @Route("/category/{category}", name="blog_category", methods={"GET"})
     */
    public function list(BlogPostRepository $blogPostRepository, Category $category): Response
    {
        //Liste des posts en fonction des categories 
        return $this->render('category/cat.html.twig', [
            'blogposts' => $blogPostRepository->findBy($category),
            'category' => $category,
        ]);
    }
}
