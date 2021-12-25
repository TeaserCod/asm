<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Lecture::class, inversedBy: 'courses')]
    private $lectures;

    #[ORM\OneToOne(inversedBy: 'course', targetEntity: Fee::class, cascade: ['persist', 'remove'])]
    private $fees;

    #[ORM\OneToMany(mappedBy: 'courses', targetEntity: Sclass::class)]
    private $sclasses;

    public function __construct()
    {
        $this->sclasses = new ArrayCollection();
    }

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

    public function getLectures(): ?Lecture
    {
        return $this->lectures;
    }

    public function setLectures(?Lecture $lectures): self
    {
        $this->lectures = $lectures;

        return $this;
    }

    public function getFees(): ?Fee
    {
        return $this->fees;
    }

    public function setFees(?Fee $fees): self
    {
        $this->fees = $fees;

        return $this;
    }

    /**
     * @return Collection|Sclass[]
     */
    public function getSclasses(): Collection
    {
        return $this->sclasses;
    }

    public function addSclass(Sclass $sclass): self
    {
        if (!$this->sclasses->contains($sclass)) {
            $this->sclasses[] = $sclass;
            $sclass->setCourses($this);
        }

        return $this;
    }

    public function removeSclass(Sclass $sclass): self
    {
        if ($this->sclasses->removeElement($sclass)) {
            // set the owning side to null (unless already changed)
            if ($sclass->getCourses() === $this) {
                $sclass->setCourses(null);
            }
        }

        return $this;
    }
}
