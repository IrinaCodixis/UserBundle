<?php
namespace Mipa\UserBundle\DataFixtures\ORM;
 
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Mipa\UserBundle\Entity\Subscription;
 
class LoadSubscriptionData extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $em)
  {
    $one_month = new Subscription();
    $one_month->setName('One Month');
 
    $six_months = new Subscription();
    $six_months->setName('Six Month');
 
    $one_year = new Subscription();
    $one_year->setName('Ome Year');
 
    $premium = new Subscription();
    $premium->setName('Premium');
 
    $em->persist($one_month);
    $em->persist($six_months);
    $em->persist($one_year);
    $em->persist($premium);
 
    $em->flush();
 
    $this->addReference('subscription-one_month', $one_month);
    $this->addReference('subscription-six_months', $six_months);
    $this->addReference('subscription-one_year', $one_year);
    $this->addReference('subscription-premium', $premium);
  }
 
  public function getOrder()
  {
    return 1; // the order in which fixtures will be loaded
  }
}
?>