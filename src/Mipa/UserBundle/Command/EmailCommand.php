<?php
// src/Acme/DemoBundle/Command/GreetCommand.php
namespace Mipa\UserBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Mipa\UserBundle\Controller\UserController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mipa\UserBundle\Entity\User;
use Mipa\UserBundle\Form\UserType;

class EmailCommand extends ContainerAwareCommand
{
	
    protected function configure()
    {
        $this
            ->setName('user:email')
            ->setDescription('Save the csv report in Temp');
          //  ->addArgument('name', InputArgument::OPTIONAL, 'Qui voulez vous saluer??')
           // ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
        ;
    }
    
	protected function execute(InputInterface $input, OutputInterface $output)
    {  
		 $container=$this->getApplication()->getKernel()->getContainer();
      
        //connexion BD
        $em = $container->get('doctrine')->getManager('default');
        $results = $em->getRepository('MipaUserBundle:User')->findAll();
		$file = $this->getApplication()->getKernel()->getRootDir() . '/../files/export_'.date("Y_m_d").'.csv';
		$fp= fopen($file, 'w');
               
        foreach ($results as $row) {
        //array list fields you need to export
        $data = array(
			$row->getId(),
			$row->getName(),
			$row->getGender(),
			$row->getAddress(),
			$row->getEmail(),
         );
		fputcsv($fp, $data);
        }
        fclose($fp);
		$output->writeln("File was saved");		
	

       //envoie ftp
	   
		$message = \Swift_Message::newInstance()
			->setSubject('user data')
			->setFrom('ijumamuradova@codixis.com')
			->setTo('guillaumelegales@gmail.com')
			->setBody('Daily data')
			->attach(\Swift_Attachment::fromPath($file));
			
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
		
		$this->getApplication()->getKernel()->getContainer()->get('mailer')->send($message);
		
	
      
        
	}
}