<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();

        $lastUsername = $utils->getLastUsername();


        return $this->render('security/login.html.twig', [
            'error'         => $error,
            'last_username' => $lastUsername
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }

    /**
     * @Route("/{username}", name="user_profile")
     * Method({"GET","POST"})
     */
    public function userProfile($username)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findBy([
            'username' => $username
        ]);
        $user2 = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('articles/user-profile.html.twig', array
            (
               'userp' => $user,
               'user2' => $user2

            )
        );
    }
    
}
