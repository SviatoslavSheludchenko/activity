<?php

namespace App\Controller;

use App\Entity\UserActivity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CowController extends AbstractController
{
    /**
     * @Route("/cow", name="cow")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Add view-cow activity
        $userActivity = new UserActivity();
        $userActivity->setAction('view-cow-page');
        $userActivity->setUser($this->getUser()->getEmail());
        $userActivity->setDate(new \DateTimeImmutable());
        $entityManager->persist($userActivity);
        $entityManager->flush();

        return $this->render('cow/index.html.twig', [
            'controller_name' => 'CowController',
        ]);
    }

    /**
     * @Route("/buy", name="buy")
     */
    public function buy(EntityManagerInterface $entityManager): Response
    {
        // Add buy-cow activity
        $userActivity = new UserActivity();
        $userActivity->setAction('buy-cow');
        $userActivity->setUser($this->getUser()->getEmail());
        $userActivity->setDate(new \DateTimeImmutable());
        $entityManager->persist($userActivity);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'You buy a cow'
        );

        return $this->redirectToRoute('activity');
    }
}
