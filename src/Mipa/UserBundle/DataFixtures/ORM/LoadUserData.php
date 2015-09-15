<?php
namespace Mipa\UserBundle\DataFixtures\ORM;
 
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Mipa\UserBundle\Entity\User;
 
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $em)
  {
    $user_one = new User();
    $user_one->setSubscription($em->merge($this->getReference('subscription-one_year')));
    $user_one->setName('John Smith');
    $user_one->setGender('Male');
    $user_one->setAddress('42, Bradford str.');
    $user_one->setEmail('user@mail.com');
    
 
    $user_two = new User();
    $user_two->setSubscription($em->merge($this->getReference('subscription-six_months')));
    $user_two->setName('Mary Lambert');
    $user_two->setGender('Female');
    $user_two->setAddress('33, Krone str.');
    $user_two->setEmail('user2@mail.com');
 
	  
	  
    $em->persist($user_one);
    $em->persist($user_two);
	
	for($i = 100; $i <= 130; $i++)
	  {
		$user_n = new User();
		$user_n->setSubscription($em->merge($this->getReference('subscription-six_months')));
		$user_n->setName('new user');
		$user_n->setGender('Female');
		$user_n->setAddress('33, Krone str.');
		$user_n->setEmail('userN@mail.com');
		
		$em->persist($user_n);
	  }
	
    $em->flush();
  }
 
  public function getOrder()
  {
    return 2; // the order in which fixtures will be loaded
  }
}
?>