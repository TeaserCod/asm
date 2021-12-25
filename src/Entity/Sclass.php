<?php

namespace App\Entity;

use App\Repository\SclassRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SclassRepository::class)]
class Sclass
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToOne(inversedBy: 'sclass', targetEntity: Student::class, cascade: ['persist', 'remove'])]
    private $students;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'sclasses')]
    private $courses;

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

    public function getStudents(): ?Student
    {
        return $this->students;
    }

    public function setStudents(?Student $students): self
    {
        $this->students = $students;

        return $this;
    }

    public function getCourses(): ?Course
    {
        return $this->courses;
    }

    public function setCourses(?Course $courses): self
    {
        $this->courses = $courses;

        return $this;
    }
}
