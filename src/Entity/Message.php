<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\ManyToOne(targetEntity=Ticket::class, inversedBy="messages")
     */
    private $ticket;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $authorRole;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;   
    }

    public function setTicket(?Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->body;
    }

    public function getAuthorRole(): ?string
    {
        return $this->authorRole;
    }

    public function setAuthorRole(string $authorRole): self
    {
        $this->authorRole = $authorRole;

        return $this;
    }
}
