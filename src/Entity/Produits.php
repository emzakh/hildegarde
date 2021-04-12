<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitsRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Produits
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomlatin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $effets;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Recettes::class, inversedBy="ingredients")
     */
    private $recettesAssociees;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;


    /**
     * Permet d'initialiser le slug automatiquement s'il n'est pas fourni
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function initializeSlug(){
        if(empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->nom);
        }
    }


    public function __construct()
    {
        $this->recettesAssociees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
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

    public function getNomlatin(): ?string
    {
        return $this->nomlatin;
    }

    public function setNomlatin(?string $nomlatin): self
    {
        $this->nomlatin = $nomlatin;

        return $this;
    }

    public function getEffets(): ?string
    {
        return $this->effets;
    }

    public function setEffets(string $effets): self
    {
        $this->effets = $effets;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Recettes[]
     */
    public function getRecettesAssociees(): Collection
    {
        return $this->recettesAssociees;
    }

    public function addRecettesAssociee(Recettes $recettesAssociee): self
    {
        if (!$this->recettesAssociees->contains($recettesAssociee)) {
            $this->recettesAssociees[] = $recettesAssociee;
        }

        return $this;
    }

    public function removeRecettesAssociee(Recettes $recettesAssociee): self
    {
        $this->recettesAssociees->removeElement($recettesAssociee);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
