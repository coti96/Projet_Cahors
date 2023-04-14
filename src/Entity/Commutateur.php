<?php

namespace App\Entity;

use App\Repository\CommutateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Documents;


#[ORM\Table(name:'commutateur')] 
#[ORM\Index(name: "Commutateur_idx1", columns: ['nom'], flags: ['fulltext'])]
#[ORM\Index(name: "commutateur_idx2", columns: ['marque'], flags: ['fulltext'])]
#[ORM\Index(name: "commutateur_idx3", columns: ['ip'], flags: ['fulltext'])]
#[ORM\Index(name: "commutateur_idx4", columns: ['fonction'], flags: ['fulltext'])]
#[ORM\Entity(repositoryClass: CommutateurRepository::class)]

class Commutateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    private ?string $fonction = null;


    #[ORM\Column(length: 255,nullable: true)]
    public ?string $parent = null;

    #[ORM\OneToMany(
        targetEntity: Documents::class,
        cascade:['all'],
        mappedBy:"commutateur",)]
    private ?Collection $documents = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emplacement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Rack = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Date = null;

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
        return $this->nom;
    }

    public function setNom(string $Nom): self
    {
        $this->nom = $Nom;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): self
    {
        $this->fonction = $fonction;

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
            $document->setcommutateur($this);
        }

        return $this;
    }

    public function removedocument(Documents $document): self
    {
        if ($this->documents->contains($document)) {
            $this->documents->removeElement($document);
            // set the owning side to null (unless already changed)
            if ($document->getcommutateur() === $this) {
                $document->setcommutateur(null);
            }
        }

        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(?string $emplacement): self
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getRack(): ?string
    {
        return $this->Rack;
    }

    public function setRack(?string $Rack): self
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
