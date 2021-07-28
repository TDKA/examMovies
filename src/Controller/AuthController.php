<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends AbstractController
{
    /**
     * @Route("/register", name="register" , priority=2)
     */
    public function register(Request $req, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {


            $hashedPassword = $hasher->hashPassword($user, $user->getPassword());

            $user->setPassword($hashedPassword);


            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('film');
        }



        return $this->render('auth/register.html.twig', [

            'form' => $form->createView()
        ]);
    }

    /**
     *@Route("/login", name="login")
     *
     *
     */
    public function login()
    {

        return $this->render('auth/login.html.twig');
    }


    /**
     *@Route ("/logout", name="logout")
     *
     */
    public function logout()
    {
    }
}
