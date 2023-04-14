<?php

namespace App\Entity;

use App\Repository\ServeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Documents;
use App\Entity\Routeur;
use Doctrine\DBAL\Types\Types;


#[ORM\Table(name:'serveur')] 
#[ORM\Index(name: "serveur_idx", columns: ['nom'], flags: ['fulltext'])]
#[ORM\Index(name: "serveur_idx2", columns: ['ip'], flags: ['fulltext'])]
#[ORM\Index(name: "serveur_idx1", columns: ['marque'], flags: ['fulltext'])]
#[ORM\Entity(repositoryClass: ServeurRepository::class)]
class Serveur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Marque = null;

    #[ORM\Column(length: 255)]
    private ?string $Emplacement = null;

    #[ORM\Column(length: 255)]
    private ?string $Rack = null;

   
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Date = null;
  

    #[ORM\Column(length: 255, nullable: true)]
    public ?string $parent = null;

    #[ORM\OneToMany(
        targetEntity: Documents::class,
        cascade:['all'],
        mappedBy:"serveur",)]
    private ?Collection $documents = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ip = null;


 

  

    public function __construct()
    {
        $this->documents = new ArrayCollection();
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->Marque;
    }

    public function setMarque(?string $Marque): self
    {
        $this->Marque = $Marque;

        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->Emplacement;
    }

    public function setEmplacement(string $Emplacement): self
    {
        $this->Emplacement = $Emplacement;

        return $this;
    }

    public function getRack(): ?string
    {
        return $this->Rack;
    }

    public function setRack(string $Rack): self
    {
        $this->Rack = $Rack;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(?\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }


    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function setParent(?string $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
   

   /**
     * @return Collection|Documents[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Documents $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setServeur($this);
        }

        return $this;
    }

    public function removedocument(Documents $document): self
    {
        if ($this->documents->contains($document)) {
            $this->documents->removeElement($document);
            // set the owning side to null (unless already changed)
            if ($document->getServeur() === $this) {
                $document->setServeur(null);
            }
        }

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }






  

}
