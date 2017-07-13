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
            ->findOneBy(array('email' => $user)
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
        $persona = new Persona();
        $telefono = new telefono();
        $form = $this->createForm('AppBundle\Form\personaType', $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $telefono->setPhone($form['telefono']['phone']->getData());
            $telefono->setPersona($persona);
            $persona->setEmail($user->getEmail());
            $em->persist($persona);
            $em->persist($telefono);
            $em->flush();

            return $this->redirectToRoute('persona_show', array('id' => $persona->getId()));
        }

        return $this->render('persona/new.html.twig', array(
            'persona' => $persona,
            'form' => $form->createView(),
        ));
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
        $exist = $this->getDoctrine()
        ->getRepository('AppBundle:persona')
        ->find($user->getId());

        if ($exist != "") {
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
        $exist = $this->getDoctrine()
        ->getRepository('AppBundle:persona')
        ->find($user->getId());

        if ($exist != "") {
            $deleteForm = $this->createDeleteForm($persona);
            $editForm = $this->createForm('AppBundle\Form\personaType', $persona);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
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
