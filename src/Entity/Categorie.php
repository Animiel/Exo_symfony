<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: ModuleFormation::class, orphanRemoval: true)]
    private Collection $modulesForma;

    public function __construct()
    {
        $this->modulesForma = new ArrayCollection();
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
     * @return Collection<int, ModuleFormation>
     */
    public function getModulesForma(): Collection
    {
        return $this->modulesForma;
    }

    public function addModulesForma(ModuleFormation $modulesForma): self
    {
        if (!$this->modulesForma->contains($modulesForma)) {
            $this->modulesForma->add($modulesForma);
            $modulesForma->setCategorie($this);
        }

        return $this;
    }

    public function removeModulesForma(ModuleFormation $modulesForma): self
    {
        if ($this->modulesForma->removeElement($modulesForma)) {
            // set the owning side to null (unless already changed)
            if ($modulesForma->getCategorie() === $this) {
                $modulesForma->setCategorie(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getNom();
    }
}
