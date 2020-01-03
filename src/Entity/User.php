<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function _construct()
    {
            parent::_construct();
            $this->articles = new ArrayCollection();
            $this->commentaires = new ArrayCollection();

    }

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="user", orphanRemoval=true)
    */
    private $articles;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Commentaires", mappedBy="user", orphanRemoval=true)
    */
    private $commentaires;

      /**
     * @ORM\Column(type="string",nullable=true)
     */
    public $image;

    protected function getimage_user(): ?string
    {
        return $this->image;
    }

    protected function setimage_user($image_user)
    {
        $this->image = $image;

        return $this;
    }

      public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('image', new Assert\Image([
            'minWidth' => 200,
            'maxWidth' => 400,
            'minHeight' => 200,
            'maxHeight' => 400,
        ]));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }
        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentaires(): Collection
    {
        return $this->$commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->$commentaires->contains($commentaire)) {
            $this->$commentaires[] = $commentaire;
            $commentaire->setUser($this);
        }
        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->$commentaires->contains($commentaire)) {
            $this->$commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }
        return $this;
    }

}
