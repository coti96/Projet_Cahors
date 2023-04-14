<?php

namespace App\Entity;

use App\Repository\PareFeuRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Entity\Documents;
use Doctrine\DBAL\Types\Types;


#[ORM\Table(name:'parefeu')] 
#[ORM\Index(name: "parefeu_idx1", columns: ['nom'], flags: ['fulltext'])]
#[ORM\Index(name: "parefeu_idx2", columns: ['editeur'], flags: ['fulltext'])]
#[ORM\Index(name: "parefeu_idx3", columns: ['ip'], flags: ['fulltext'])]
#[ORM\Entity(repositoryClass: PareFeuRepository::class)]
class PareFeu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $editeur = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $ip = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Emplacement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Rack = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Date = null;
  
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $parent = null;

    #[ORM\OneToMany(
        targetEntity: Documents::class,
        cascade:['all'],
        mappedBy:"parefeu",)]
    private ?Collection $documents = null;

  

      

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
            $document->setParefeu($this);
        }

        return $this;
    }

    public function removedocument(Documents $document): self
    {
        if ($this->documents->contains($document)) {
            $this->documents->removeElement($document);
            // set the owning side to null (unless already changed)
            if ($document->getRouteur() === $this) {
                $document->setRouteur(null);
            }
        }

        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEditeur(): ?string
    {
        return $this->editeur;
    }

    public function setEditeur(?string $Editeur): self
    {
        $this->editeur = $Editeur;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->Emplacement;
    }

    public function setEmplacement(?string $Emplacement): self
    {
        $this->Emplacement = $Emplacement;

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


    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function setParent(?string $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
}
