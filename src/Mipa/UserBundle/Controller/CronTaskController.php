<?php
namespace Mipa\UserBundle\Controller;

use Mipa\UserBundle\Entity\CronTask;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/crontasks")
 */
class CronTaskController extends Controller
{
    /**
     * @Route("/test", name="Mipa_UserBundle_crontasks_test")
     */
    public function testAction()
    {
        $task = new CronTask();

        $task
            ->setName('Create csv file and tranfer it')
            ->setInterval(60) // Run once every minute
            ->setCommands(array(
                'user:ftp'
            ));

        $em = $this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();

        return new Response('OK!');
    }
}
?>