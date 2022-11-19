<?php

namespace App\Entity;

use App\Repository\FacturesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturesRepository::class)
 */
class Factures
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @var string
     * @ORM\OneToOne(targetEntity=Reparations::class, cascade={"persist", "remove"})
     */
    private $repair;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRepair(): ?Reparations
    {
        return $this->repair;
    }

    public function setRepair(?Reparations $repair): self
    {
        $this->repair = $repair;

        return $this;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->dateCreate;
    }

    public function setDateCreate(?\DateTimeInterface $dateCreate): self
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }
}
