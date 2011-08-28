<?php

/**
 * This file is applied CC0 <http://creativecommons.org/publicdomain/zero/1.0/>
 */

namespace Societo\Util\StorageBundle\Tests\Entity;

use Societo\Util\StorageBundle\Entity\File as FileEntity;
use Societo\BaseBundle\Test\EntityTestCase;

class File extends EntityTestCase
{
    public function createTestEntityManager($entityPaths = array())
    {
        if (!$entityPaths) {
            $entityPaths = $this->createClassFileDirectoryPaths(array(
                'Societo\Util\StorageBundle\Entity\File',
            ));
        }

        return parent::createTestEntityManager($entityPaths);
    }

    public function testConstructor()
    {
        $em = $this->createTestEntityManager();
        $this->rebuildDatabase($em);

        $em->persist(new FileEntity('A', array('B' => 'C')));
        $em->persist(new FileEntity('D'));
        $em->flush();

        $file = $em->getRepository('Societo\Util\StorageBundle\Entity\File')->find(1);
        $this->assertEquals('A', $file->getFilename());
        $this->assertEquals(array('B' => 'C'), $file->getParameters());

        $file = $em->getRepository('Societo\Util\StorageBundle\Entity\File')->find(2);
        $this->assertEquals('D', $file->getFilename());
        $this->assertEquals(array(), $file->getParameters());
    }

    public function testSetRandomizedFilename()
    {
        $file = new FileEntity('fix');
        $file->setRandomizedFilename();
        $this->assertTrue('fix' !== $file->getFilename());

        $results = array();
        for ($i = 0; $i < 2; $i++ ) {
            $file->setRandomizedFilename('prefix_');
            $results[] = $file->getFilename();
        }
        $this->assertTrue(false !== strpos($results[0], 'prefix_'));
        $this->assertTrue($results[0] !== $results[1]);

        $results = array();
        for ($i = 0; $i < 2; $i++ ) {
            $file->setRandomizedFilename('', '_suffix');
            $results[] = $file->getFilename();
        }
        $this->assertTrue(false !== strpos($results[0], '_suffix'));
        $this->assertTrue($results[0] !== $results[1]);
    }

    public function testSetGetFilename()
    {
        $em = $this->createTestEntityManager();
        $this->rebuildDatabase($em);

        $entity = new FileEntity('A');
        $entity->setFilename($entity->getFilename().'B');

        $em->persist($entity);
        $em->flush();

        $entity = $em->getRepository('Societo\Util\StorageBundle\Entity\File')->find(1);
        $this->assertEquals('AB', $entity->getFilename());
    }

    public function testAddGetResetUriList()
    {
        $em = $this->createTestEntityManager();
        $this->rebuildDatabase($em);

        $entity = new FileEntity('A');
        $entity->addUri('http://example.com/');

        $em->persist($entity);
        $em->flush();

        $entity = $em->getRepository('Societo\Util\StorageBundle\Entity\File')->find(1);
        $this->assertEquals(array('http://example.com/'), $entity->getUriList());

        $entity->addUri('http://ext.example.com/');
        $this->assertEquals(array('http://example.com/', 'http://ext.example.com/'), $entity->getUriList());

        $entity->resetUriList();
        $this->assertEquals(array(), $entity->getUriList());
    }
}
