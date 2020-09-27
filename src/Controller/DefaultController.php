<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        if ($this->isGranted('ROLE_AGENT')){
            return $this->redirectToRoute('admin');
        }
        return $this->redirectToRoute('home');
    }
}
