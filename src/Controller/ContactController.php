<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact.index')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $data = new ContactDTO();
        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ){
            $email = (new TemplatedEmail())
            ->from($data->email)
            ->to($data->service)
            ->subject('Contact demand')
            ->htmlTemplate('emails/contact.html.twig')
            ->context(['data' => $data]);

            try {
                $mailer->send($email);
                $this->addFlash('success','Message send.');
                return $this->redirectToRoute('contact.index');
            } catch (TransportExceptionInterface $e) {
                $this->addFlash('danger','Message not send.');
            }

            
        }
        return $this->render('contact/index.html.twig', [
            'form'=> $form
        ]);
    }
}
