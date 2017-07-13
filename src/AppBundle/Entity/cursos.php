<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * cursos
 *
 * @ORM\Table(name="cursos")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\cursosRepository")
 */
class cursos
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="facilitador", type="string", length=50, nullable=false)
     */
    private $facilitador;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=false)
     */
    private $startDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\persona", inversedBy="cursos")
     * @ORM\JoinTable(name="persona_cursos",
     *   joinColumns={
     *     @ORM\JoinColumn(name="cursos_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="persona_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $persona;

    /**
     * Constructor
     */

    public function __construct()
    {
        $this->persona = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getFacilitador()
    {
        return $this->facilitador;
    }

    public function setFacilitador($facilitador)
    {
        $this->facilitador = $facilitador;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    public function getPersona()
    {
        return $this->persona;
    }

    public function addPersona(persona $person)
    {
        if ($this->persona->contains($person)) {
            return;
        }
        $this->persona[] = $person;
        $person->addCursos($this);
    }

    public function removePersona(persona $person)
    {
        if (!$this->persona->contains($person)) {
            return;
        }
        $this->persona->removeElement($person);
        $person->removeCursos($this);
    }

    public function __toString()
    {
        return $this->name;
    }
}
