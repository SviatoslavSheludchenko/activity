<?php

namespace App\Controller;

use App\Entity\UserActivity;
use App\ReadModel\User\Filter;
use App\ReadModel\User\UserFetcher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    private const PER_PAGE = 50;

    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("/user", name="user")
     */
    public function index(Request $request, UserFetcher $fetcher, EntityManagerInterface $entityManager): Response
    {
        $filter = new Filter\Filter();

        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);

        $pagination = $fetcher->all(
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'email'),
            $request->query->get('direction', 'desc')
        );

        // Add view-users activity
        $userActivity = new UserActivity();
        $userActivity->setAction('view-users-page');
        $userActivity->setUser($this->getUser()->getEmail());
        $userActivity->setDate(new \DateTimeImmutable());
        $entityManager->persist($userActivity);
        $entityManager->flush();


        return $this->render('app/users/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }
}
