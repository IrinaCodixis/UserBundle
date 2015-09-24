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
     * @Route("/test", name="your_examplebundle_crontasks_test")
     */
    public function testAction()
    {
        $entity = new CronTask();

        $entity
            ->setName('Create csv file and tranfer it')
            ->setInterval(86400) // Run once every hour
            ->setCommands(array(
                'user:ftp'
            ));

        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        return new Response('OK!');
    }
}
?>