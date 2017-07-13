<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $cursos = $this->getDoctrine()
            ->getRepository('AppBundle:cursos')
            ->findAll();

        return $this->render('default/index.html.twig', array('cursos' => $cursos));
    }

    /**
     * @Route("/admin", name="admin_homepage")
     */
    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }
}
