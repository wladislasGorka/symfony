<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use App\Entity\Users;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register.index')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $user = new Users();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ){
            $em->persist($user);
            $em->flush();
            $this->addFlash('success','User creation success.');
            return $this->redirectToRoute('register.index');
        }
        return $this->render('register/index.html.twig', [
            'form'=> $form
        ]);
    }
}
