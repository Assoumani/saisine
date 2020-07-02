<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use function mysql_xdevapi\getSession;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        dump($this->getUser(), $request->getSession()->get('_security.ticket.target_path'));
         if ($this->getUser()) {
             return $this->redirect($request->getSession()->get('_security.ticket.target_path'));
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/front", name="front_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function frontLogin(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        dump($this->getUser(), $request->getSession()->get('_security.ticket.target_path'));
         if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
             return $this->redirect($request->getSession()->get('_security.ticket.target_path'));
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/front_login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(Request $request)
    {
        $request->getSession()->clear();
        if ($request->server->get('HTTP_REFERER') === "http://127.0.0.1:8000/home") {
            return new RedirectResponse($this->generateUrl('home'));
        }
        return $this->redirectToRoute('admin');
    }
}
