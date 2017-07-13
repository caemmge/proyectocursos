<?php

namespace AppBundle\Controller;
use AppBundle\Entity\cursos;
use AppBundle\Entity\persona;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class CourseController extends Controller
{
	
    /**
     * @Route("/test", name="testing")
     */
    public function TestAction()
    {
        $cursos = $this->getDoctrine()
            ->getRepository('AppBundle:cursos')
            ->findOneById(1);

        #$personas = $cursos->getPersonacursos();

        # return $this->render('default/index.html.twig', array('cursos' => $cursos));
        return $this->render('test.html.twig', array('cursos' => $cursos));

    }


}