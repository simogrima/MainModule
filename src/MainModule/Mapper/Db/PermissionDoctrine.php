<?php

namespace MyZfcRbac\Mapper;

use ZfcUserDoctrineORM\Mapper\User as ZfcUserDoctrineMapper;

class PermissionDoctrine extends BaseDoctrine
{
    public function findAll() 
    {
        $er = $this->em->getRepository($this->options->getpermissionEntityClass());
        return $er->findAll();
    }

    

}