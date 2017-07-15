<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * persona
 *
 * @ORM\Table(name="persona")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\personaRepository")
 */
class persona
{
    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=50, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=50, nullable=false)
     */
    private $lastname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth", type="date", nullable=false)
     */
    private $birth;

    /**
     * @var integer
     *
     * @ORM\Column(name="age", type="integer", nullable=false)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var \AppBundle\Entity\usuario
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * })
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Persona
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id", nullable=false, unique=true, onDelete="CASCADE")
     * })
     */
    private $usuario;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\cursos", mappedBy="persona", cascade={"persist", "remove"})
     */
    private $cursos;

    /**
     * @var AppBundle\Entity\telefono
     */
    protected $telefono;

    /**
     * Constructor
     */

    public function __construct()
    {
        $this->cursos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setAge($age)
    {
        $this->age = $age;
    }

    public function getBirth()
    {
        return $this->birth;
    }

    public function setBirth($birth)
    {
        $this->birth = $birth;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getCursos()
    {
        return $this->cursos;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario(Usuario $u) {
        return $this->usuario = $u;
    }

    public function addCursos(cursos $curs)
    {
        if ($this->cursos->contains($curs)) {
            return;
        }
        $this->cursos[] = $curs;
        $curs->addPersona($this);
    }

    public function removeCursos(cursos $curs)
    {
        if (!$this->cursos->contains($curs)) {
            return;
        }
        $this->cursos->removeElement($curs);
        $curs->removePersona($this);
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function setTelefono(telefono $phone = null)
    {
        $this->telefono = $phone;
    }

    public function __toString()
    {
        return $this->firstname . ' ' . $this->lastname;
    }
}
