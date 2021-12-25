<?php

namespace App\Entity;

use App\Repository\FeeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeeRepository::class)]
class Fee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'float')]
    private $costfee;

    #[ORM\OneToOne(mappedBy: 'fees', targetEntity: Course::class, cascade: ['persist', 'remove'])]
    private $course;

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

    public function getCostfee(): ?float
    {
        return $this->costfee;
    }

    public function setCostfee(float $costfee): self
    {
        $this->costfee = $costfee;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        // unset the owning side of the relation if necessary
        if ($course === null && $this->course !== null) {
            $this->course->setFees(null);
        }

        // set the owning side of the relation if necessary
        if ($course !== null && $course->getFees() !== $this) {
            $course->setFees($this);
        }

        $this->course = $course;

        return $this;
    }
}
