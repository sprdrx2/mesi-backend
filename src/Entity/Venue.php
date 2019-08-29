<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VenueRepository")
 */
class Venue
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $yelp_id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $espace_poussette;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $table_langer;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $table_langer_men;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $menu_enfant;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $espace_jeu;

    /**
     * @ORM\Column(type="boolean")
     */
    private $wc_enfant;

    /**
     * @ORM\Column(type="boolean")
     */
    private $chaise_haute;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYelpId(): ?string
    {
        return $this->yelp_id;
    }

    public function setYelpId(string $yelp_id): self
    {
        $this->yelp_id = $yelp_id;

        return $this;
    }

    public function getEspacePoussette(): ?bool
    {
        return $this->espace_poussette;
    }

    public function setEspacePoussette(?bool $espace_poussette): self
    {
        $this->espace_poussette = $espace_poussette;

        return $this;
    }

    public function getTableLanger(): ?bool
    {
        return $this->table_langer;
    }

    public function setTableLanger(?bool $table_langer): self
    {
        $this->table_langer = $table_langer;

        return $this;
    }

    public function getTableLangerMen(): ?bool
    {
        return $this->table_langer_men;
    }

    public function setTableLangerMen(?bool $table_langer_men): self
    {
        $this->table_langer_men = $table_langer_men;

        return $this;
    }

    public function getMenuEnfant(): ?bool
    {
        return $this->menu_enfant;
    }

    public function setMenuEnfant(?bool $menu_enfant): self
    {
        $this->menu_enfant = $menu_enfant;

        return $this;
    }

    public function getEspaceJeu(): ?bool
    {
        return $this->espace_jeu;
    }

    public function setEspaceJeu(?bool $espace_jeu): self
    {
        $this->espace_jeu = $espace_jeu;

        return $this;
    }

    public function getWcEnfant(): ?bool
    {
        return $this->wc_enfant;
    }

    public function setWcEnfant(bool $wc_enfant): self
    {
        $this->wc_enfant = $wc_enfant;

        return $this;
    }

    public function getChaiseHaute(): ?bool
    {
        return $this->chaise_haute;
    }

    public function setChaiseHaute(bool $chaise_haute): self
    {
        $this->chaise_haute = $chaise_haute;

        return $this;
    }
}
