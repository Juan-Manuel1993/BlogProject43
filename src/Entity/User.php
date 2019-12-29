<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

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
