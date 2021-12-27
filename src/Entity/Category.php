<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Articles::class, mappedBy="Category")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Jeux::class, mappedBy="Category")
     */
    private $jeux;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->jeux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Articles[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Articles $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
            $category->setCategory($this);
        }

        return $this;
    }

    public function removeCategory(Articles $category): self
    {
        if ($this->category->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getCategory() === $this) {
                $category->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Jeux[]
     */
    public function getJeux(): Collection
    {
        return $this->jeux;
    }

    public function addJeux(Jeux $jeux): self
    {
        if (!$this->jeux->contains($jeux)) {
            $this->jeux[] = $jeux;
            $jeux->setCategory($this);
        }

        return $this;
    }

    public function removeJeux(Jeux $jeux): self
    {
        if ($this->jeux->removeElement($jeux)) {
            // set the owning side to null (unless already changed)
            if ($jeux->getCategory() === $this) {
                $jeux->setCategory(null);
            }
        }

        return $this;
    }
}
