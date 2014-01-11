<?php

namespace MainModule\Mapper\Db;

use Doctrine\ORM\EntityManager;
use Zend\Stdlib\AbstractOptions as ModuleOptions;
use Zend\Stdlib\Hydrator\HydratorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;


class BaseDoctrine
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Zend\Stdlib\AbstractOptions;
     */
    protected $options;
    
    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    public function __construct(EntityManager $em)
    {
        $this->em      = $em;
        $this->options = $options;
    }

    public function insert($entity)
    {
        return $this->persist($entity);
    }

    public function update($entity)
    {
        return $this->persist($entity);
    }
    
    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }    

    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }
    
    /**
     * Getter hidrator
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = new DoctrineHydrator();
        }
        return $this->hydrator;
    }


    /**
     * Setter hidrator
     * @param \Zend\Stdlib\Hydrator\HydratorInterface $hydrator
     * @return \MyZfcRbac\Mapper\BaseDoctrine
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
        return $this;
    }    
    
    /**
     * Setter module options
     * @param \Zend\Stdlib\AbstractOptions $options
     */
    public function setModuleOptions(ModuleOptions $options)
    {
        $this->options = $options;
    }        

        /**
     * Uses the hydrator to convert the entity to an array.
     * Use this method to ensure that you're working with an array.
     *
     * @param object $entity
     * @return array
     */
    protected function entityToArray($entity, HydratorInterface $hydrator = null)
    {
        if (is_array($entity)) {
            return $entity; // cut down on duplicate code
        } elseif (is_object($entity)) {
            if (!$hydrator) {
                $hydrator = $this->getHydrator();
            }
            return $hydrator->extract($entity);
        }
        throw new Exception\InvalidArgumentException('Entity passed to db mapper should be an array or object.');
    }    
}