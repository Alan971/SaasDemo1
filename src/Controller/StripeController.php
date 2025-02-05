<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Stripe\StripeClient;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

final class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'controller_name' => 'StripeController',
        ]);
    }

    #[Route('/create-checkout-session', name: 'app_create_checkout_session')]
    public function createCheckoutSession(): Response
    {
        $apiKeyStripe = $_ENV['STIPEKEY'];
        dump($apiKeyStripe);
        $stripe = new StripeClient($apiKeyStripe);
        $checkout_session = $stripe->checkout->sessions->create([
            'customer_email' => $this->getUser()->getUserIdentifier(),
            'submit_type' => 'auto',
            'billing_address_collection' => 'required',
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'abonnement_LM',
                            'images' => ['https://stripe.com/img/documentation/checkout/marketplace.png'],
                        ],
                        'unit_amount' => 1500, // en cents
                        
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => 'http://localhost:8001/success',
            'cancel_url' => 'http://localhost:8001/cancel',
        ]);
        return $this->redirect($checkout_session->url);
    }

    #[Route('/success', name: 'app_stripe_success')]
    public function success(UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $user->setPaiement(true);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }

    #[Route('/cancel', name: 'app_cancel')]
    public function cancel(): Response
    {
        return $this->redirectToRoute('app_home');
    }


    #[Route('/unsetpaiement', name: 'app_unsetpaiement')]
    public function unsetPaiement(UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $user->setPaiement(false);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }
}
