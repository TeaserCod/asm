<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $address;

    #[ORM\ManyToOne(targetEntity: Parents::class, inversedBy: 'students')]
    private $parents;

    #[ORM\OneToOne(mappedBy: 'students', targetEntity: Sclass::class, cascade: ['persist', 'remove'])]
    private $sclass;

    #[ORM\Column(type: 'string', length: 255)]
    private $gender;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getParents(): ?Parents
    {
        return $this->parents;
    }

    public function setParents(?Parents $parents): self
    {
        $this->parents = $parents;

        return $this;
    }

    public function getSclass(): ?Sclass
    {
        return $this->sclass;
    }

    public function setSclass(?Sclass $sclass): self
    {
        // unset the owning side of the relation if necessary
        if ($sclass === null && $this->sclass !== null) {
            $this->sclass->setStudents(null);
        }

        // set the owning side of the relation if necessary
        if ($sclass !== null && $sclass->getStudents() !== $this) {
            $sclass->setStudents($this);
        }

        $this->sclass = $sclass;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }
}
