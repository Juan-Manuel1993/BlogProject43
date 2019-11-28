<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $Nom;

    /**
     * @ORM\Column(type="text")
     */
    private $Contenu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $featured_image;


     /**
      * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="contenu")
      * @ORM\JoinColumn(nullable=false)
      */
    protected $user_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaires", mappedBy="article_id")
     */
    private $contenu;

    public function __construct()
    {
        $this->contenu = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->Contenu;
    }

    public function setContenu(string $Contenu): self
    {
        $this->Contenu = $Contenu;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getFeaturedImage(): ?string
    {
        return $this->featured_image;
    }

    public function setFeaturedImage(string $featured_image): self
    {
        $this->featured_image = $featured_image;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(User $user_id ): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function addContenu(Commentaires $contenu): self
    {
        if (!$this->contenu->contains($contenu)) {
            $this->contenu[] = $contenu;
            $contenu->setArticleId($this);
        }

        return $this;
    }

    public function removeContenu(Commentaires $contenu): self
    {
        if ($this->contenu->contains($contenu)) {
            $this->contenu->removeElement($contenu);
            // set the owning side to null (unless already changed)
            if ($contenu->getArticleId() === $this) {
                $contenu->setArticleId(null);
            }
        }

        return $this;
    }
}
