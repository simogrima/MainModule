<?php

namespace MainModule\Validator\Doctrine;
use DoctrineModule\Validator\NoObjectExists as DoctrineNoObjectExists;
/**
 * Class that validates if objects does not exist. 
 * Add exclude param form update mode.
 *
 * @license MIT
 * @link    http://www.simogrima.com/
 * @author  Grimani Simone <simogrima@gmail.com>
 */
class NoObjectExists extends DoctrineNoObjectExists
{
    
        /**
     * @var mixed
     */
    protected $exclude = null;
    
    /**
     * Returns the set exclude clause
     *
     * @return string|array
     */
    public function getExclude()
    {
        return $this->exclude;
    }

    /**
     * Sets a new exclude clause
     *
     * @param string|array $exclude
     * @return self Provides a fluent interface
     */
    public function setExclude($exclude)
    {
        $this->exclude = $exclude;
        return $this;
    }    

    /**
     * {@inheritDoc}
     */
    public function isValid($value)
    {
        $value = $this->cleanSearchValue($value);
        $qb = $this->objectRepository->createQueryBuilder('a');
        $field = key($value);
            $qb->where($qb->expr()->eq('a.'.  $field, ":{$field}"));
            $qb->setParameter($field, $value[$field]);
        
        $exclude = $this->getExclude();
        if (isset($exclude)) {
            $qb->andWhere($qb->expr()->not($qb->expr()->eq('a.'.$exclude['field'], ":{$exclude['field']}")));
            $qb->setParameter($exclude['field'], $exclude['value']);
        }
        
        $match = $qb->getQuery()
            ->getOneOrNullResult();
        
        if (is_object($match)) {
            $this->error(self::ERROR_OBJECT_FOUND, $value);

            return false;
        }
        return true;
    }
}
