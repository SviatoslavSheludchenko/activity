<?php

namespace App\Controller;

use App\Entity\UserActivity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController
{
    /**
     * @Route("/file", name="file")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Add view-file activity
        $userActivity = new UserActivity();
        $userActivity->setAction('view-file-page');
        $userActivity->setUser($this->getUser()->getEmail());
        $userActivity->setDate(new \DateTimeImmutable());
        $entityManager->persist($userActivity);
        $entityManager->flush();

        return $this->render('file/index.html.twig', [
            'controller_name' => 'FileController',
        ]);
    }
    /**
     * @Route("/download", name="download")
     */
    public function download(EntityManagerInterface $entityManager): BinaryFileResponse
    {
        // Add download-file activity
        $userActivity = new UserActivity();
        $userActivity->setAction('download-file');
        $userActivity->setUser($this->getUser()->getEmail());
        $userActivity->setDate(new \DateTimeImmutable());
        $entityManager->persist($userActivity);
        $entityManager->flush();

        return $this->file('/file/test.exe');
    }
}
