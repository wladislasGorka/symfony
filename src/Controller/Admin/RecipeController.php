<?php

namespace App\Controller\Admin;

use App\Repository\RecipeRepository;
use App\Entity\Recipe;
use App\Form\RecipeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/admin/recipes', name: 'admin.recipe.')]
class RecipeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(RecipeRepository $repository): Response
    {
        //dd($request->attributes->get('slug'), $request->attributes->get('id'));
        //return new Response('Recipes');
        $recipes = $repository->findAll();
        //$recipes = $em->getRepository(Recipe::class)->findWithDurationLowerThan(20);

        // Update recipe
        // $recipes[0]->setTitle('Pâtes sauce tomate');
        // $em->flush();

        // Create recipe
        // $recipe = new Recipe();
        // $recipe->setTitle('Barbe à papa')
        //     ->setSlug('barbe-papa')
        //     ->setContent('Mettez du sucre.')
        //     ->setCreatedAt(new \DateTimeImmutable())
        //     ->setUpdateAt(new \DateTimeImmutable())
        //     ->setDuration(5);
        // $em->persist($recipe);
        // $em->flush();

        // Remove recipe
        // $em->remove($recipes[0]);
        // $em->flush();
        // $recipes = $repository->findWithDurationLowerThan(20);

        return $this->render('admin/recipe/index.html.twig', [
            'recipes'=> $recipes
        ]);
    }

    // #[Route('/recipes/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    // public function show(Request $request, string $slug, int $id, RecipeRepository $repository): Response
    // {
    //     //dd($request->attributes->get('slug'), $request->attributes->get('id'));
    //     //dd($slug, $id);
    //     //return new Response('Recipe: ' . $slug);
    //     // return new JsonResponse([
    //     //     'slug' => $slug
    //     // ]);
    //     // return $this->json([
    //     //     'slug' => $slug
    //     // ]);
    //     $recipe = $repository->find($id);
    //     if($recipe->getSlug() != $slug) {
    //         return $this->redirectToRoute('recipe.show', ['slug'=> $recipe->getSlug(), 'id'=> $recipe->getId()]);
    //     }
    //     return $this->render('recipe/show.html.twig', [
    //         'recipe'=> $recipe
    //     ]);
    // }

    #[Route('/create', name:'create')]
    public function createRecipe(Request $request, EntityManagerInterface $em){
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ){
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success','Creation success.');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render('admin/recipe/create.html.twig', [
            'form'=> $form
        ]);
    }

    #[Route('/{id}', name:'edit', methods: ['GET','POST'], requirements: ['id'=>Requirement::DIGITS])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em){
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ){
            $em->flush();
            $this->addFlash('success','Modif success.');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render('admin/recipe/edit.html.twig', [
            'recipe'=> $recipe,
            'form'=> $form
        ]);
    }

    #[Route('/{id}', name:'delete', methods: ['DELETE'], requirements: ['id'=>Requirement::DIGITS])]
    public function deleteRecipe(Recipe $recipe, EntityManagerInterface $em){
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success','Delete success.');
        return $this->redirectToRoute('admin.recipe.index');
    }
}