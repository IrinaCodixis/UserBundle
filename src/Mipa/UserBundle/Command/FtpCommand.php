<?php
// src/Acme/DemoBundle/Command/GreetCommand.php
namespace Mipa\UserBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
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
       
         $container=$this->getApplication()->getKernel()->getContainer();
      
         $response = new StreamedResponse(function() use($container) {

            $em = $container->get('doctrine')->getManager();

            // The getExportQuery method returns a query that is used to retrieve
            // all the objects (lines of your csv file) you need. The iterate method
            // is used to limit the memory consumption
            $results = $em->getRepository('MipaUserBundle:User')->getExportQuery()->iterate();
            $handle = fopen('php://output', 'r+');

            while (false !== ($row = $results->next())) {
                // add a line in the csv file. You need to implement a toArray() method
                // to transform your object into an array
                fputcsv($handle, $row[0]->toArray());
                // used to limit the memory consumption
                $em->detach($row[0]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition','attachment; filename="export.csv"');
		$output->writeln("Files saved");
        $response;

        
        
		       
        
      
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