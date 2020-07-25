<?php

namespace App\Utils\Managers;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Abstract class for the service which manage the Entity Manager
 */
abstract class BaseManager
{
    /**
     * @var EntityManagerInterface The Entity Manager
     */
    protected $em;

    /**
     * BaseManager constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * Getter of the Entity Manager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * Add a repository to this service
     *
     * @param integer $key   Key
     * @param string  $class Class
     *
     * @return void
     */
    public function addRepository($key, $class)
    {
        $this->$key = $this->em->getRepository($class);
    }

    /**
     * Add a service to this service
     *
     * @param integer $key     Key
     * @param string  $service Class
     *
     * @return void
     */
    public function addManager($key, $service)
    {
        $this->$key = $service;
    }
}
