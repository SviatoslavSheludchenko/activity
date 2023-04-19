<?php

namespace App\Controller;

use App\Entity\UserActivity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\ReadModel\Activity\Filter;
use App\ReadModel\Activity\ActivityFetcher;

class ActivityController extends AbstractController
{
    private const PER_PAGE = 50;

    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("/activity", name="activity")
     */
    public function index(Request $request, ActivityFetcher $fetcher,EntityManagerInterface $entityManager): Response
    {
        $filter = new Filter\Filter();

        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);

        $pagination = $fetcher->all(
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'date'),
            $request->query->get('direction', 'desc')
        );

        // Add view activity
        $userActivity = new UserActivity();
        $userActivity->setAction('view-activity-page');
        $userActivity->setUser($this->getUser()->getEmail());
        $userActivity->setDate(new \DateTimeImmutable());
        $entityManager->persist($userActivity);
        $entityManager->flush();

        return $this->render('activity/index.html.twig', [
            'controller_name' => 'ActivityController',
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }
}
