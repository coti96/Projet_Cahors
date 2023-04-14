<?php

namespace App\Entity;

use App\Repository\DocumentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name:'documents')] 
#[ORM\Index(name: "documents_idx", columns: ['description'], flags: ['fulltext'])]
#[ORM\Index(name: "serveur_idx", columns: ['nom'], flags: ['fulltext'])]
#[ORM\Entity(repositoryClass: DocumentsRepository::class)]
class Documents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;


    #[ORM\Column(length: 255,nullable: true)]
    private ?string $description = null;

    
    #[ORM\ManyToOne(
        inversedBy: 'documents',
        targetEntity: PareFeu::class,
        cascade:['persist','refresh'])]
    private ?PareFeu $parefeu = null;


    #[ORM\ManyToOne(
        inversedBy: 'documents',
        targetEntity: Serveur::class,
        cascade:['persist','refresh'])]
    private ?Serveur $serveur = null;

    #[ORM\ManyToOne(
        inversedBy: 'documents',
        targetEntity: Commutateur::class,
        cascade:['persist','refresh'])]
    private ?Commutateur $commutateur = null;

    #[ORM\ManyToOne(
        inversedBy: 'documents',
        targetEntity: Routeur::class,
        cascade:['persist','refresh'])]
    private ?Routeur $routeur = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getServeur(): ?Serveur
    {
        return $this->serveur;
    }

    public function setServeur(?Serveur $serveur): self
    {
        $this->serveur = $serveur;

        return $this;
    }
    
    public function getCommutateur(): ?Commutateur
    {
        return $this->commutateur;
    }

    public function setCommutateur(?Commutateur $commutateur): self
    {
        $this->commutateur = $commutateur;

        return $this;
    }
    public function getRouteur(): ?Routeur
    {
        return $this->routeur;
    }

    public function setRouteur(?Routeur $routeur): self
    {
        $this->routeur = $routeur;

        return $this;
    }
    public function getParefeu(): ?Parefeu
    {
        return $this->parefeu;
    }

    public function setParefeu(?Parefeu $parefeu): self
    {
        $this->parefeu = $parefeu;

        return $this;
    }

   

}
