<?php

/**
 * This file is applied CC0 <http://creativecommons.org/publicdomain/zero/1.0/>
 */

namespace Societo\Util\StorageBundle\Tests\Entity;

use Societo\Util\StorageBundle\Entity\FileBinary as FileBinaryEntity;
use Societo\BaseBundle\Test\EntityTestCase;

class FileBinaryTest extends EntityTestCase
{
    public function createTestEntityManager($entityPaths = array())
    {
        if (!$entityPaths) {
            $entityPaths = $this->createClassFileDirectoryPaths(array(
                'Societo\Util\StorageBundle\Entity\FileBinary',
            ));
        }

        return parent::createTestEntityManager($entityPaths);
    }

    public function testConstructor()
    {
        $em = $this->createTestEntityManager();
        $this->rebuildDatabase($em);

        $em->persist(new FileBinaryEntity('A', 'BIN'));
        $em->flush();

        $entity = $em->getRepository('Societo\Util\StorageBundle\Entity\FileBinary')->find(1);
        $this->assertEquals('A', $entity->getFilename());
        $this->assertEquals('BIN', $entity->getBin());
    }

    public function testSetGetFilename()
    {
        $em = $this->createTestEntityManager();
        $this->rebuildDatabase($em);

        $entity = new FileBinaryEntity('A');
        $entity->setFilename($entity->getFilename().'B');

        $em->persist($entity);
        $em->flush();

        $entity = $em->getRepository('Societo\Util\StorageBundle\Entity\FileBinary')->find(1);
        $this->assertEquals('AB', $entity->getFilename());
    }

    public function testSetGetBin()
    {
        $em = $this->createTestEntityManager();
        $this->rebuildDatabase($em);

        $entity = new FileBinaryEntity('A', 'BIN');
        $entity->setBin($entity->getBin().'!');

        $em->persist($entity);
        $em->flush();

        $entity = $em->getRepository('Societo\Util\StorageBundle\Entity\FileBinary')->find(1);
        $this->assertEquals('BIN!', $entity->getBin());
    }
}
