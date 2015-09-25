<?php

namespace Mipa\UserBundle\Entity;

/**
 * CronTask
 */
class CronTask
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
    private $commands;

    /**
     * @var integer
     */
    private $time_period;

    /**
     * @var \DateTime
     */
    private $lastrun;


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
     * @return CronTask
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
     * Set commands
     *
     * @param string $commands
     *
     * @return CronTask
     */
    public function setCommands($commands)
    {
        $this->commands = $commands;

        return $this;
    }

    /**
     * Get commands
     *
     * @return string
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Set timePeriod
     *
     * @param integer $timePeriod
     *
     * @return CronTask
     */
    public function setTimePeriod($timePeriod)
    {
        $this->time_period = $timePeriod;

        return $this;
    }

    /**
     * Get timePeriod
     *
     * @return integer
     */
    public function getTimePeriod()
    {
        return $this->time_period;
    }

    /**
     * Set lastrun
     *
     * @param \DateTime $lastrun
     *
     * @return CronTask
     */
    public function setLastrun($lastrun)
    {
        $this->lastrun = $lastrun;

        return $this;
    }

    /**
     * Get lastrun
     *
     * @return \DateTime
     */
    public function getLastrun()
    {
        return $this->lastrun;
    }
}

