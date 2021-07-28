<?php

namespace App\Controller;

use App\Entity\Impression;
use App\Repository\ImpressionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImpressionController extends AbstractController
{
    /**
     * @Route("/impression", name="impression")
     */
    public function index(ImpressionRepository $repo): Response
    {
        $impressions = $repo->findAll();
        return $this->render('fiml/show.html.twig', [
            'controller_name' => 'ImpressionController',
            'impressions' => $impressions
        ]);
    }

    /**
     * @Route("/impression/delete/{id}", name = "deleteImpression", priority=2);
     * 
     */
    public function delete(Impression $impression, EntityManagerInterface $manager)
    {


        $manager->remove($impression);
        $manager->flush();

        return $this->redirectToRoute('film');
    }
}
