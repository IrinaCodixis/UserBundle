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
class FtpCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('user:ftp')
            ->setDescription('Saving the csv report in Temp');
          //  ->addArgument('name', InputArgument::OPTIONAL, 'Qui voulez vous saluer??')
           // ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $records = $this->getDoctrine()->getManager()
            ->getRepository('MipaUserBundle:User')->findAll();

        if($records->count()) {
				$file = '/var/www/irina-dev.codixis.net/www/files/export_'.date("Y_m_d").'.csv';
				$fp= fopen($file, 'w');
                //$handle = fopen('php://output', 'r+');
                foreach ($records as $record) {
                    //array list fields you need to export
                    $data = $record->toArray(false);
                    fputcsv($fp, $data);
                }
                fclose($fp);
				
				//$file = "/tmp/rapport".$plateforme.".csv";
				//$fp= fopen($file, "w");
				//fwrite($fp,$csv);
				//fclose($fp);
            }
        //);
        //$response->headers->set('Content-Type', 'text/csv');
        //$response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');
		//return $response;
    }    
        
      
       //envoie ftp
        $params = $container->getParameter('user')['ftp'];
        if(isset($params)){
        	//ftp : $conn_id = ftp_connect($params['server']);
        	//sftp
        	$conn_id = ssh2_connect($params['server'], $params['port']);
        	if($conn_id===false){
        		$output->writeln("Connection failed");
        		exit;
        	}
        	
        	//ftp authentification $login_result = ftp_login($conn_id, $params['login'],$params['password']);
        	$login_result = ssh2_auth_password($conn_id, $params['login'],$params['password']);
        	 
        	$remote_file = $params['path']."export.csv";
        	$output->writeln($file);
        	$output->writeln($remote_file);
        	
        	//ftp envoie fichier if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
        	//
        	if (ssh2_scp_send($conn_id,  $file, $remote_file, 0644)) {
        		$output->writeln("file transferred");
        	} else {
        		$output->writeln("Failed transfer");
        	}
        //	close the connection
        	//ftp_close($conn_id);
        }
      
        
    }
}