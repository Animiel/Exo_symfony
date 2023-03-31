<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbJours = null;

    #[ORM\ManyToOne(inversedBy: 'programmes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SessionFormation $sessionForma = null;

    #[ORM\ManyToOne(inversedBy: 'programmes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ModuleFormation $moduleForma = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbJours(): ?int
    {
        return $this->nbJours;
    }

    public function setNbJours(int $nbJours): self
    {
        $this->nbJours = $nbJours;

        return $this;
    }

    public function getSessionForma(): ?SessionFormation
    {
        return $this->sessionForma;
    }

    public function setSessionForma(?SessionFormation $sessionForma): self
    {
        $this->sessionForma = $sessionForma;

        return $this;
    }

    public function getModuleForma(): ?ModuleFormation
    {
        return $this->moduleForma;
    }

    public function setModuleForma(?ModuleFormation $moduleForma): self
    {
        $this->moduleForma = $moduleForma;

        return $this;
    }
}
