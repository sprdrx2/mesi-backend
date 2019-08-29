<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentaireRepository")
 */
class Commentaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commentaires")
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Venue", inversedBy="commentaires")
     */
    private $venue;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
    public function getVenue(): ?Venue
    {
        return $this->venue;
    }
    public function setVenue(?Venue $venue): self
    {
        $this->venue = $venue;
        return $this;
    }
    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }
    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;
        return $this;
    }
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }
    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }
}