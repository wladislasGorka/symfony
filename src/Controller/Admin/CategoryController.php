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
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('admin/category/category.html.twig', [
            'categories'=> $categories
        ]);
    }

    #[Route('/filter', name: 'filter')]
    public function filter(Request $request): Response
    {
        $slug = $request->request->get('category');
        return $this->redirectToRoute('admin.category.filtered', ['slug' => $slug]);
    }

    #[Route('/{slug}', name: 'filtered', requirements: ['slug' => '[a-z0-9-]+'])]
    public function sort(string $slug, Request $request, RecipeRepository $recipeRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        $recipes = $recipeRepository->findByCategory($slug);
        return $this->render('admin/category/category.html.twig', [
            'categories'=> $categories,
            'recipes'=> $recipes,
            'slug'=> $slug
        ]);
    }
}
