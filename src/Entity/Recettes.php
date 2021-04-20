<?php

namespace App\Entity;

use App\Repository\RecettesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RecettesRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields={"titre"},
 *  message="titre déjà utilisé merci d'en choisir un autre"
 * )
 */

class Recettes
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
    private $titre;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $etapes;

    /**
     * @ORM\ManyToMany(targetEntity=Produits::class, mappedBy="recettesAssociees")
     */
    private $ingredients;

    /**
     * @ORM\OneToMany(targetEntity=Commentaires::class, mappedBy="recette")
     */
    private $commentaires;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class, mappedBy="liaison")
     */
    private $types;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recettes")
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image(mimeTypes={"image/png", "image/jpeg", "image/jpg", "image/gif"}, mimeTypesMessage="Vous devez upload un fichier jpg, png ou gif")
     * @Assert\File(maxSize="1024k", maxSizeMessage="Taille du fichier trop grande")
     */
    private $imgRecette;

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
            $this->slug = $slugify->slugify($this->titre);
        }
    }

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->types = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEtapes(): ?string
    {
        return $this->etapes;
    }

    public function setEtapes(string $etapes): self
    {
        $this->etapes = $etapes;

        return $this;
    }

    /**
     * @return Collection|Produits[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Produits $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->addRecettesAssociee($this);
        }

        return $this;
    }

    public function forEachIngredients($ingredients)
    {
        foreach($ingredients as $ingredient){
            $this->addIngredient($ingredient);
        }
    }

    public function removeIngredient(Produits $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            $ingredient->removeRecettesAssociee($this);
        }

        return $this;
    }

    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setRecette($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getRecette() === $this) {
                $commentaire->setRecette(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
            $type->addLiaison($this);
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        if ($this->types->removeElement($type)) {
            $type->removeLiaison($this);
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

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

    public function getImgRecette(): ?string
    {
        return $this->imgRecette;
    }

    public function setImgRecette(?string $imgRecette): self
    {
        $this->imgRecette = $imgRecette;

        return $this;
    }
}
