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
       $container=$this->getApplication()->getKernel()->getContainer();
      
        //connexion BD
        $em = $container->get('doctrine')->getManager();
		
		$results = $em->getRepository('MipaUserBundle:User')->findAll();
       
        //nom de la plateforme
        
        if($records->count()) {
        
		$file = '/var/www/irina-dev.codixis.net/www/files/export_'.date("Y_m_d").'.csv';
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
        fclose($fp);
        
        $output->writeln("Fichier enregistre sur temp");
        
	}
       //envoie ftp
//        $params = $container->getParameter('user')['ftp'];
//        if(isset($params)){
//        	//ftp : $conn_id = ftp_connect($params['server']);
//        	//sftp
//        	$conn_id = ssh2_connect($params['server'], $params['port']);
//        	if($conn_id===false){
//        		$output->writeln("Connection failed");
//        		exit;
//        	}
        	
        	//ftp authentification $login_result = ftp_login($conn_id, $params['login'],$params['password']);
//        	$login_result = ssh2_auth_password($conn_id, $params['login'],$params['password']);
        	 
//        	$remote_file = $params['path'].'export_'.date("Y_m_d").'.csv';
//        	$output->writeln($file);
 //       	$output->writeln($remote_file);
        	
        	//ftp envoie fichier if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
        	//
//        	if (ssh2_scp_send($conn_id,  $file, $remote_file, 0644)) {
//        		$output->writeln("file transferred");
//        	} else {
//        		$output->writeln("Failed transfer");
//        	}
        //	close the connection
        	//ftp_close($conn_id);
//        }
      
        
    }
}