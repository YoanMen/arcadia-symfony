<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use App\Event\ContactRequestEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request,  EventDispatcherInterface $dispatcher)
    {
        $data = new ContactDTO();

        $contactForm = $this->createForm(ContactType::class, $data);

        $csrf = $request->get("_csrf_token", "");


        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            try {

                if ($this->isCsrfTokenValid('contact', $csrf)) {

                    $dispatcher->dispatch(new ContactRequestEvent($data));
                    $this->addFlash("success", "Votre message a été envoyé. Nous vous recontacterons bientôt.");

                    return $this->redirectToRoute('app_contact');
                } else {
                    throw new \Exception("Clé CSRF non valide");
                }
            } catch (\Exception $e) {

                return $this->render('contact/index.html.twig', [
                    'controller_name' => 'ContactController',
                    'contactForm' => $this->createForm(ContactType::class, $data),
                    'error' => 'Impossible d\'envoyer votre message, une erreur est survenue'
                ]);
            }
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contactForm' => $contactForm
        ]);
    }
}
