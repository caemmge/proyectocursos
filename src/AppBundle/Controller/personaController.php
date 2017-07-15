<?php

namespace AppBundle\Controller;

use AppBundle\Entity\persona;
use AppBundle\Entity\telefono;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Persona controller.
 *
 * @Route("persona")
 */
class personaController extends Controller
{
    /**
     * Lists all persona entities.
     *
     * @Route("/", name="persona_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        # $personas = $em->getRepository('AppBundle:persona')->findAll();

        $personas = $em->getRepository('AppBundle:persona')
            ->findOneBy(array('usuario' => $user)
                   );

        if ($personas != "") {
            return $this->render('persona/index.html.twig', array(
                'personas' => $personas,
            ));
        } else {
            return $this->redirectToRoute('persona_new');
        }


    }

    /**
     * Creates a new persona entity.
     *
     * @Route("/new", name="persona_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $personas = $em->getRepository('AppBundle:persona')
            ->findOneBy(array('usuario' => $user->getId())
                   );
        $persona = new Persona();
        $telefono = new telefono();

        $form = $this->createForm('AppBundle\Form\personaType', $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $telefono->setPhone($form['telefono']['phone']->getData());
            $telefono->setPersona($persona);
            $persona->setEmail($user->getEmail());
            $persona->setUsuario($user);
            $em->persist($persona);
            $em->persist($telefono);
            $em->flush();

            return $this->redirectToRoute('persona_show', array('id' => $persona->getId()));
        }

        if ($personas == "") {
            return $this->render('persona/new.html.twig', array(
                        'persona' => $persona,
                        'form' => $form->createView(),
            ));
        } else {
            return $this->redirectToRoute('persona_show', array('id' => $personas->getId()));
        }

    }

    /**
     * Finds and displays a persona entity.
     *
     * @Route("/{id}", name="persona_show")
     * @Method("GET")
     */
    public function showAction(persona $persona)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $personas = $em->getRepository('AppBundle:persona')
            ->findOneBy(array('usuario' => $user->getId())
                   );

        if ($personas != "") {
            $deleteForm = $this->createDeleteForm($persona);
        
            $telefono = $this->getDoctrine()
                ->getRepository('AppBundle:telefono')
                ->findOneBy(
                    array('persona' => $persona)
                   );

            return $this->render('persona/show.html.twig', array(
                'persona' => $persona,
                'telefono' => $telefono,
                'delete_form' => $deleteForm->createView(),
            ));
        } else {
            return $this->redirectToRoute('persona_new');
        }

        
    }

    /**
     * Displays a form to edit an existing persona entity.
     *
     * @Route("/{id}/edit", name="persona_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, persona $persona)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $personas = $em->getRepository('AppBundle:persona')
            ->findOneBy(array('usuario' => $user->getId()));

        $telefono = new telefono();

        if ($personas != "") {
            $telefonos = $em->getRepository('AppBundle:telefono')
            ->findOneBy(array('persona' => $personas->getId()));
            $telefono->setPhone($telefonos->getPhone());
            $persona->setTelefono($telefono);
            $deleteForm = $this->createDeleteForm($persona);
            $editForm = $this->createForm('AppBundle\Form\personaType', $persona);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                
                $phone = $form['telefono']['phone']->getData();
                $conn->update('telefono', 't')
                     ->set('t.phone', '?')
                     ->where('t.usuario', '?')
                     ->setParameter(0, $phone)
                     ->setParameter(1, $personas->getId());

                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('persona_show', array('id' => $persona->getId()));
            } else {
                return $this->render('persona/edit.html.twig', array(
                'persona' => $persona,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
                ));
            }


        } else {
            return $this->redirectToRoute('persona_new');
        }
        
    }

    /**
     * Deletes a persona entity.
     *
     * @Route("/{id}", name="persona_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, persona $persona)
    {
        $form = $this->createDeleteForm($persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($persona);
            $em->flush();
        }

        return $this->redirectToRoute('persona_index');
    }

    /**
     * Creates a form to delete a persona entity.
     *
     * @param persona $persona The persona entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(persona $persona)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('persona_delete', array('id' => $persona->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
