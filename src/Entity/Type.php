<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 */
class Type
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
     * @ORM\ManyToMany(targetEntity=Recettes::class, inversedBy="types")
     */
    private $liaison;

    public function __construct()
    {
        $this->liaison = new ArrayCollection();
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
     * @return Collection|Recettes[]
     */
    public function getLiaison(): Collection
    {
        return $this->liaison;
    }

    public function addLiaison(Recettes $liaison): self
    {
        if (!$this->liaison->contains($liaison)) {
            $this->liaison[] = $liaison;
        }

        return $this;
    }

    public function removeLiaison(Recettes $liaison): self
    {
        $this->liaison->removeElement($liaison);

        return $this;
    }
}
