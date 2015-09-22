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

class FtpCommand extends ContainerAwareCommand
{
	
	public function exportCSVAction()
    {
        $container=$this->getApplication()->getKernel()->getContainer();
      
        //connexion BD
        $em = $container->get('doctrine')->getManager('default');
        $results = $em->getRepository('MipaUserBundle:User')->findAll();

        $response = new StreamedResponse();
        $response->setCallback(
            function () use ($results) {
				$file = '/var/www/irina-dev.codixis.net/www/files/export_'.date("Y_m_d").'.csv';
				$fp= fopen($file, 'w');
                //$handle = fopen('php://output', 'r+');
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
				
			}
        );
        return $response;
    }
	
    protected function configure()
    {
        $this
            ->setName('user:ftp')
            ->setDescription('Save the csv report in Temp');
          //  ->addArgument('name', InputArgument::OPTIONAL, 'Qui voulez vous saluer??')
           // ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
        ;
    }
    
	protected function execute(InputInterface $input, OutputInterface $output)
    {       
       $csv= $this->exportCSVAction();
         
		if(isset($csv)){
			$output->writeln("Files saved");
		}
		else{
			$output->writeln("Failed to save file");
		} 
        
	}
       
      
        
    
}