<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    use UuidEntity;
    use DateTimeEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: 'integer')]
    private int $estimate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $developmentStartAt;

    public function __construct()
    {
        $this->developmentStartAt = new \DateTime();
        $this->createUuid();
        $this->setDateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEstimate(): int
    {
        return $this->estimate;
    }

    public function setEstimate(string $estimate): self
    {
        $this->estimate = $estimate;

        return $this;
    }

    public function getDevelopmentStartAt(): \DateTime
    {
        return $this->developmentStartAt;
    }

    public function setDevelopmentStartAt(\DateTime $developmentStartAt): self
    {
        $this->developmentStartAt = $developmentStartAt;

        return $this;
    }
}
