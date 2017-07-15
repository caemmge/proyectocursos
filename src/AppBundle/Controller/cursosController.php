<?php

namespace AppBundle\Controller;

use AppBundle\Entity\cursos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\persona;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityManager;

/**
 * Curso controller.
 *
 * @Route("cursos")
 */
class cursosController extends Controller
{
    /**
     * Lists all curso entities.
     *
     * @Route("/", name="cursos_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cursos = $em->getRepository('AppBundle:cursos')->findAll();

        return $this->render('cursos/index.html.twig', array(
            'cursos' => $cursos,
        ));
    }

    /**
     * Creates a new curso entity.
     *
     * @Route("/new", name="cursos_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $persona = $this->getDoctrine()
        ->getRepository('AppBundle:persona')
        ->findOneBy(array('email' => $user->getEmail())
                   );

        $em = $this->getDoctrine()->getManager();

        $conn = $em->getConnection();

        $sql = 'SELECT c.id, c.name, c.facilitador, c.start_date FROM cursos as c
                where not EXISTS (select * FROM persona_cursos 
                  where cursos_id = c.id and persona_id = :id)';

        $stmt = $conn->prepare($sql);
        $stmt->execute(array('id' => $persona->getId()));
        $cursos = $stmt->fetchAll();

        $builder =$this->createFormBuilder();

        if (count($cursos) == 0) {
            return $this->redirectToRoute('cursos_show');
        }

        foreach ($cursos as $curso) {
            $choices[$curso['name']] = strval($curso['id']);
        }

        $builder
            ->add('cursos', ChoiceType::class,array(
                  'choices' => array($choices),
                  'multiple' => true,
                  'expanded' => true,
                 ));


        $form = $builder->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $chk = $form['cursos']->getData();

            $conn = $em->getConnection();
            foreach ($chk as $value) {
                $conn->insert('persona_cursos', 
                    array('cursos_id' => $value,
                          'persona_id' => $persona->getId()
                    ));
            }

            return $this->redirectToRoute('cursos_show', array(
                'email' => $user->getEmail()));
        }

        return $this->render('cursos/new.html.twig', array(
            'curso' => $cursos,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a curso entity.
     *
     * @Route("/show", name="cursos_show")
     */
    public function showAction()
    {
        $user = $this->container
        ->get('security.token_storage')
        ->getToken()
        ->getUser();
        
        $persona = $this->getDoctrine()
        ->getRepository('AppBundle:persona')
        ->findOneBy(array('email' => $user->getEmail())
                   );

        $em = $this->getDoctrine()->getManager();

        $conn = $em->getConnection();

        $sql = 'SELECT c.id, c.name, c.facilitador, c.start_date FROM cursos c inner join persona_cursos pc on pc.cursos_id = c.id WHERE pc.persona_id = :id';

        $stmt = $conn->prepare($sql);
        $stmt->execute(array('id' => $persona->getId()));
        $cursos = $stmt->fetchAll();

        return $this->render('cursos/show.html.twig', array(
            'cursos' => $cursos,
        ));
    }

    /**
     * Displays a form to edit an existing curso entity.
     *
     * @Route("/{id}/edit", name="cursos_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, cursos $curso)
    {
        $deleteForm = $this->createDeleteForm($curso);
        $editForm = $this->createForm('AppBundle\Form\cursosType', $curso);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cursos_edit', array('id' => $curso->getId()));
        }

        return $this->render('cursos/edit.html.twig', array(
            'curso' => $curso,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a curso entity.
     *
     * @Route("/del/{id}", name="cursos_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $user = $this->container
        ->get('security.token_storage')
        ->getToken()
        ->getUser();
        
        $persona = $this->getDoctrine()
        ->getRepository('AppBundle:persona')
        ->findOneBy(array('email' => $user->getEmail()));

        $em = $this->getDoctrine()->getManager();

        $conn = $em->getConnection();

        $sql = 'DELETE FROM persona_cursos WHERE persona_id = :pid AND cursos_id = :cid';

        $stmt = $conn->prepare($sql);
        $stmt->execute(array('pid' => $persona->getId(),
                             'cid' => $id));

        return $this->redirectToRoute('cursos_show');
    }

    /**
     * Generates de data of the inscription
     *
     * @Route("/pdf", name="pdf_show")
     */
    public function pdfAction()
    {
        
    }
}
