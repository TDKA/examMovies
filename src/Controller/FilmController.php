<?php

namespace App\Controller;

use App\Entity\Film;
use App\Form\FilmType;
use App\Entity\Impression;
use App\Form\ImpressionType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class FilmController extends AbstractController
{
    /**
     * @Route("/film", name="film")
     */
    public function index(FilmRepository $repo): Response
    {
        $films = $repo->findAll();

        return $this->render('film/index.html.twig', [
            'controller_name' => 'PhoneController',
            'films' => $films

        ]);
    }

    /**
     * @Route("/film/{id}", name="showFilm")
     * 
     * 
     */
    public function showOne(Film $film, EntityManagerInterface $manager, Request $req, Impression $impression = null)
    {
        $modeEdition = true;

        if (!$impression) {
            $impression = new Impression();
            $modeEdition = false;
        }

        $form = $this->createForm(ImpressionType::class, $impression);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$modeEdition) {

                $impression->setCreatedAt(new \DateTime());
            }

            $impression->setFilm($film);
            $manager->persist($impression);
            $manager->flush();

            return $this->redirectToRoute('showFilm', [
                "id" => $film->getId()
            ]);
        }

        return $this->render("film/show.html.twig", [
            'film' => $film,
            'form' => $form->createView(),
            'modeEdition' => $modeEdition


        ]);
    }

    /**
     * 
     * @Route("/film/create", name="createFilm", priority=2)
     * @Route("/film/edit/{id}", name="editFilm", priority=2)
     * 
     * 
     */
    public function create(Request $req, EntityManagerInterface $manager, Film $film = null, UserInterface $user): Response
    {

        $modeEdition = true;

        if (!$film) {

            $film = new Film();
            $modeEdition = false;
        }
        if ($user != $film->getUserId() && $modeEdition) {

            return $this->redirectToRoute('film');
        }

        $form = $this->createForm(FilmType::class, $film);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$modeEdition) {

                $film->setCreatedAt(new \DateTime());
                $film->setUserId($user);
            }


            $manager->persist($film);
            $manager->flush();

            return $this->redirectToRoute('showFilm', [
                "id" => $film->getId()

            ]);
        }


        return $this->render('film/create.html.twig', [
            'form' => $form->CreateView(),
            'modeEdition' => $modeEdition

        ]);
    }

    /**
     * @Route("/film/delete/{id}", name = "deleteFilm", priority=2);
     * 
     */
    public function delete(Film $film, EntityManagerInterface $manager)
    {


        $manager->remove($film);
        $manager->flush();

        return $this->redirectToRoute('film');
    }
}
