<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;


    #[ORM\Column(length: 255, nullable: true)]
    public ?string $assignedTo = null;

   
    #[ORM\OneToMany(
        targetEntity: Documents::class,
        cascade:['all'],
        mappedBy:"tasks",)]
    private ?Collection $Document = null;

    #[ORM\Column]
    private ?bool $completed = false;


    public function __construct()
    {
        $this->assignedTo = new ArrayCollection();
        $this->Document = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    /**
     * @return Collection<int, User>
     */
    public function getAssignedTo(): string
    {
        return $this->assignedTo;
    }

    public function addAssignedTo(string $assignedTo): self
    {
        $this->addAssignedTo($assignedTo);
        
        return $this;
    }

    public function removeAssignedTo(string $assignedTo): self
    {
        $this->removeAssignedTo($assignedTo);

        return $this;
    }

    /**
     * @return Collection<int, Documents>
     */
    public function getDocument(): Collection
    {
        return $this->Document;
    }

    public function addDocument(Documents $document): self
    {
        if (!$this->Document->contains($document)) {
            $this->Document->add($document);
        }

        return $this;
    }

    public function removeDocument(Documents $document): self
    {
        $this->Document->removeElement($document);

        return $this;
    }

    public function isCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): self
    {
        $this->completed = $completed;

        return $this;
    }
}
