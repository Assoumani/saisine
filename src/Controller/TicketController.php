<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Ticket;
use App\Form\MessageType;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use App\Service\FileUploader;
use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/ticket")
 */
class TicketController extends AbstractController
{
    /**
     * @Route("/", name="ticket_index", methods={"GET"})
     * @IsGranted("ROLE_AGENT")
     * @param TicketRepository $ticketRepository
     * @return Response
     */
    public function index(TicketRepository $ticketRepository): Response
    {
        return $this->render('ticket/index.html.twig', [
            'tickets' => $ticketRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ticket_new", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @throws Exception
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $ticket->setCreatedAt(new DateTime());
            $ticket->setTicketNumber(strtoupper(uniqid(substr($ticket->getName(), 0, 2))));
            $ticket->setPassword($passwordEncoder->encodePassword($ticket, $ticket->getTicketNumber()));
            $entityManager->persist($ticket);
            $entityManager->flush();
            if (!$this->getUser()) {
                $this->addFlash('success', 'Requête envoyée avec succes. Consulter vos mails!');
                return $this->redirectToRoute('home');
            }
            return $this->redirectToRoute('ticket_show', ['ticketNumber' => $ticket->getTicketNumber()]);
        }
        return $this->render('ticket/new.html.twig', [
            'ticket' => $ticket,
            'error' => null,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{ticketNumber}", name="ticket_show", methods={"GET", "POST"})
     * @param Request $request
     * @param Ticket $ticket
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function show(Request $request, Ticket $ticket, FileUploader $fileUploader): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fileNames = [];
            if ($form->get('files')->getData()) {
                foreach ($form->get('files')->getData() as $file) {
                    $fileNames[] = $fileUploader->upload($file);
                }
                if ($ticket->getFiles()) {
                    foreach ($ticket->getFiles() as $fileName) {
                        $fileNames[] = $fileName;
                    }
                }
                $ticket->setFiles($fileNames);
                $entityManager->persist($ticket);
            }
            $message->setTicket($ticket);
            $message->setAuthorRole($this->getUser()->getUsername());
            $entityManager->persist($message);
            $entityManager->flush();
            return $this->redirectToRoute('ticket_show', ['ticketNumber' => $ticket->getTicketNumber()]);
        }
        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{ticketNumber}/edit", name="ticket_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Ticket $ticket
     * @return Response
     */
    public function edit(Request $request, Ticket $ticket): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ticket_index');
        }

        return $this->render('ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ticket_delete", methods={"DELETE"})
     * @param Request $request
     * @param Ticket $ticket
     * @return Response
     */
    public function delete(Request $request, Ticket $ticket): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ticket->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ticket_index');
    }
}
