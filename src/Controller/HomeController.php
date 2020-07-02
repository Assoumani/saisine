<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     * @param TicketRepository $repository
     * @return Response
     */
    public function index(TicketRepository $repository)
    {
        return $this->forward('App\Controller\TicketController::new');
    }

    /**
     * @Route("/show", name="show_ticket")
     * @param TicketRepository $repository
     * @return Response
     */
    public function show(TicketRepository $repository)
    {
        return  $this->redirectToRoute('ticket_show', [
            'ticketNumber' => $repository->findOneBy(['name' => $this->getUser()->getUsername()])
        ]);
    }
}
