<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RecipeRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HomeController extends AbstractController
{
    #[Route("/", name: "home")]
    function index(Request $request,RecipeRepository $recipeRepository, CategoryRepository $categoryRepository): Response 
    {
        return $this->render('home/index.html.twig', [
            'categories'=> $categoryRepository->findAll(),
            'recipes' => $recipeRepository->findAll()
        ]);
    }

    #[Route('/home/filter', name: 'filter')]
    public function filter(Request $request): Response
    {
        $slug = $request->request->get('category');
        return $this->redirectToRoute('filtered', ['slug' => $slug]);
    }

    #[Route('/home/{slug}', name: 'filtered', requirements: ['slug' => '[a-z0-9-]+'])]
    public function sort(string $slug, Request $request, RecipeRepository $recipeRepository, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy(['slug'=> $slug]);
        if($slug == 'all') {
            $recipes = $recipeRepository->findAll();
        }else{
            $recipes = $category->getRecipes();
        }
        return $this->render('home/index.html.twig', [
            'categories'=> $categoryRepository->findAll(),
            'recipes'=> $recipes,
            'slug'=> $slug
        ]);
    }
}
