<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;

#[Route('/admin/category', name: 'admin.category.')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/category/category.html.twig');
    }

    #[Route('/{slug}', name: 'sort', requirements: ['slug' => '[a-z0-9-]+'])]
    public function sort(string $slug, Request $request, RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findByCategory($slug);
        return $this->render('admin/category/category.html.twig', [
            'recipes'=> $recipes
        ]);
    }
}
