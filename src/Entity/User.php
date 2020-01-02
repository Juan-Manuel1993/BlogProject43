<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
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

    }

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

    public function GetId(): ?int
    {
        return $this->id;
    }

    public function SetId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

}
