<?php

namespace App\Controller;

use App\Entity\UserActivity;
use App\Repository\UserActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    /**
     * @Route("/report", name="report")
     */
    public function index(EntityManagerInterface $entityManager, UserActivityRepository $userActivityRepository): Response
    {
        $actions = $userActivityRepository->findAll();

        $activities = [];
        $reports = [];

        foreach ($actions as $item)
        {
            $activities[$item->getUser()][$item->getAction()][] = $item;
        }
        // Better to group and count as query not like this :(
        foreach ($actions as $item) {
            $report['user'] = $item->getUser();
            $report['action'] = $item->getAction();
            $report['count'] = count($activities[$item->getUser()][$item->getAction()]);
            $reports[] = $report;
        }
        $reports = array_map("unserialize", array_unique(array_map("serialize", $reports)));

        // Add view-reports activity
        $userActivity = new UserActivity();
        $userActivity->setAction('view-reports-page');
        $userActivity->setUser($this->getUser()->getEmail());
        $userActivity->setDate(new \DateTimeImmutable());
        $entityManager->persist($userActivity);
        $entityManager->flush();

        return $this->render('report/index.html.twig', [
            'controller_name' => 'ReportController',
            'reports' => $reports,
        ]);
    }
}
