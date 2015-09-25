<?php

namespace Mipa\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mipa\UserBundle\Entity\CronTask;
use Mipa\UserBundle\Form\CronTaskType;

/**
 * CronTask controller.
 *
 */
class CronTaskController extends Controller
{

    /**
     * Lists all CronTask entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MipaUserBundle:CronTask')->findAll();

        return $this->render('MipaUserBundle:CronTask:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new CronTask entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CronTask();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cron_task_show', array('id' => $entity->getId())));
        }

        return $this->render('MipaUserBundle:CronTask:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a CronTask entity.
     *
     * @param CronTask $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CronTask $entity)
    {
        $form = $this->createForm(new CronTaskType(), $entity, array(
            'action' => $this->generateUrl('cron_task_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CronTask entity.
     *
     */
    public function newAction()
    {
        $entity = new CronTask();
        $form   = $this->createCreateForm($entity);

        return $this->render('MipaUserBundle:CronTask:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CronTask entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MipaUserBundle:CronTask')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CronTask entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MipaUserBundle:CronTask:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CronTask entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MipaUserBundle:CronTask')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CronTask entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MipaUserBundle:CronTask:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a CronTask entity.
    *
    * @param CronTask $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CronTask $entity)
    {
        $form = $this->createForm(new CronTaskType(), $entity, array(
            'action' => $this->generateUrl('cron_task_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CronTask entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MipaUserBundle:CronTask')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CronTask entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cron_task_edit', array('id' => $id)));
        }

        return $this->render('MipaUserBundle:CronTask:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a CronTask entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MipaUserBundle:CronTask')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CronTask entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cron_task'));
    }

    /**
     * Creates a form to delete a CronTask entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cron_task_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
