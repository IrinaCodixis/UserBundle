<?php

namespace Mipa\UserBundle\Entity;

/**
 * User
 */
class User
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $email;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * @var \Mipa\UserBundle\Entity\Subscription
     */
    private $subscription;


    /**
     * Set subscription
     *
     * @param \Mipa\UserBundle\Entity\Subscription $subscription
     *
     * @return User
     */
    public function setSubscription(\Mipa\UserBundle\Entity\Subscription $subscription = null)
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * Get subscription
     *
     * @return \Mipa\UserBundle\Entity\Subscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

	public function exportCSVAction()
    {
        $results = $this->getDoctrine()->getManager()
            ->getRepository('MipaUserBundle:User')->findAll();

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
				
				//$file = "/tmp/rapport".$plateforme.".csv";
				//$fp= fopen($file, "w");
				//fwrite($fp,$csv);
				//fclose($fp);
            }
        );
        //$response->headers->set('Content-Type', 'text/csv');
        //$response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');
		return $response;
    }
}
