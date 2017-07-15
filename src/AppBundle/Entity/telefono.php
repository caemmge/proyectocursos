<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * telefono
 *
 * @ORM\Table(name="telefono")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\telefonoRepository")
 */
class telefono
{
    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=15, nullable=false)
     */
    private $phone;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\persona
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\persona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="persona_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $persona;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->persona;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getPersona() {
        return $this->persona;
    }

    public function setPersona(Persona $p = null) {
        return $this->persona = $p;
    }
}
