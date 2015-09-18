<?php

namespace Mipa\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mipa\UserBundle\Entity\User;
use Mipa\UserBundle\Form\UserType;

/**
 * User controller.
 *
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     */
    public function indexAction(Request $request)
   {
		$em = $this->getDoctrine()->getManager();
		$dql   = "SELECT a FROM MipaUserBundle:User a";
		$query = $em->createQuery($dql);

		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$request->query->getInt('page', 1)/*page number*/,
			10 /*limit per page*/
		);
		
		this->executeExcel();
		this->sendAction();
		
		// parameters to template
		return $this->render('MipaUserBundle:User:index.html.twig', array('pagination' => $pagination));
	}
	

	

    /**
     * Creates a new User entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mipa_user_show', array('id' => $entity->getId())));
        }

        return $this->render('MipaUserBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('mipa_user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createCreateForm($entity);

        return $this->render('MipaUserBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MipaUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MipaUserBundle:User:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MipaUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MipaUserBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('mipa_user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MipaUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('mipa_user_edit', array('id' => $id)));
        }

        return $this->render('MipaUserBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MipaUserBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('mipa_user'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('mipa_user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
	
	public function executeExcel()
	{
		// We're not going to be displaying any html, so no need to pass the data through the template
		$this->setLayout(false);
	 
		// Initialize the Excel document
		$obj = new PHPExcel();
		
		// Set some meta data relative to the document
		$obj->getProperties()->setCreator("Irina");
		$obj->getProperties()->setTitle("List of Users");
		$obj->getProperties()->setSubject("Users");
		$obj->getProperties()->setDescription("File with the list of users");
		$obj->getProperties()->setKeywords("user");
		$obj->getProperties()->setCategory("from database");
		
		// Set the active excel sheet
		$obj->setActiveSheetIndex(0);
		$obj->getActiveSheet()->setTitle('users');
		
		// Get the data that we want to display in the excel sheet
		$data = Doctrine_Core::getTable('user')->findAll();
		
		// Set relavant indexes
		$nRows = $data->count();
		$nColumns = 'A';
		
		// The keys of the $data[0]->toArray() array are the field names of the table
		$fields = isset($data[0])? array_keys($data[0]->toArray()): array();
		
		// NOTE: $column = 'A'; $column + 1 == 1; $column++ == 'B'; True story.
		// Get the final column index and create the excel column to table field map
		$fieldsCount = count($fields);
		$excelMap = array();
		for($i = 0; $i < $fieldsCount; $i++){
		  $excelMap[$nColumns] = $fields[$i];
		  $nColumns++;
		}
		
		// Set the first row as the table's field names
		for($j = 'A'; $j < $nColumns; $j++){
		  $obj->getActiveSheet()->setCellValue($j.'1', $excelMap[$j]);
		}
		
		// Fill the rest of the excel sheet with data
		$nRows += 1;
		for($i = 2; $i <= $nRows; $i++){
		  for($j = 'A'; $j < $nColumns; $j++){
			$obj->getActiveSheet()->setCellValue($j.$i, $data[$i - 2][$excelMap[$j]]);
		  }
		}
		
		// Output the excel data to a file
		$filePath = realpath('./ExcelFiles') . DIRECTORY_SEPARATOR . 'excel.xlsx';
		$writer = PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
		$writer->save($filePath);
		
		// Redirect request to the outputed file
		//$this->getResponse()->setHttpHeader('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//$this->redirect('/ExcelFiles/excel.xlsx');
	  }
	  
	  public function sendAction()
	{
		$message = \Swift_Message::newInstance()
			->setSubject('user data')
			->setFrom('jumamuradova.i@gmail.com')
			->setTo('ijumamuradova@codixis.com')
			->setBody('Daily data')
			->attach(Swift_Attachment::fromPath('/ExcelFiles/excel.xlsx'));
			
			/*
			 * If you also want to include a plaintext version of the message
			->addPart(
				$this->renderView(
					'Emails/registration.txt.twig',
					array('name' => $name)
				),
				'text/plain'
			)
			*/
		
		$this->get('mailer')->send($message);
		
	}
}
