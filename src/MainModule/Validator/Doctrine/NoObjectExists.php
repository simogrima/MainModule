<?php


namespace MainModule\Validator\Doctrine;
use Doctrine\Module\Validator;
/**
 * Class that validates if objects does not exist in a given repository with a given list of matched fields
 *
 * @license MIT
 * @link    http://www.simogrima.com/
 * @author  Grimani Simone <simogrima@gmail.com>
 */
class NoObjectExists extends Validator
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
        var_dump($this->getExclude());
        $value = $this->cleanSearchValue($value);
        var_dump($value);
        echo key($value) . ' => ' . $value[key($value)] . '<br/>';
        $qb = $this->objectRepository->createQueryBuilder('a');
        $field = key($value);
            $qb->where($qb->expr()->eq('a.'.  $field, ":{$field}"));
            $qb->setParameter($field, $value[$field]);
        
        
        $exclude = $this->getExclude();
        if (isset($exclude)) {
            $qb->andWhere($qb->expr()->not($qb->expr()->eq('a.'.$exclude['field'], ":{$exclude['field']}")));
            $qb->setParameter($exclude['field'], $exclude['value']);
        }
        echo $qb;
        
        $match = $qb->getQuery()
            ->getResult();
        
        //$match = $this->objectRepository->findOneBy($value);
        
        

        if (is_object($match)) {
            $this->error(self::ERROR_OBJECT_FOUND, $value);

            return false;
        }

        return true;
    }
}
