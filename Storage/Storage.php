<?php

/**
 * SocietoUtilStorageBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\Util\StorageBundle\Storage;

use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

class Storage
{
    private $handlers = array();
    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function addStreamHandler($ref)
    {
        $this->handlers[] = $ref;
    }

    public function store($filename, $binary, $parameters = array())
    {
        $file = $this->em->getRepository('SocietoUtilStorageBundle:File')->findOneBy(array(
            'filename' => $filename,
        ));
        if (!$file) {
            $file = new \Societo\Util\StorageBundle\Entity\File($filename, $parameters);
        }

        $file->resetUriList();

        foreach ($this->handlers as $handler) {
            $uri = $handler->generateUri($filename);
            $byte = file_put_contents($uri, $binary);
            if (false !== $byte) {
                $file->addUri($uri);

                break;
            }
        }

        $this->em->persist($file);
        $this->em->flush();

        return $file;
    }

    public function restore($filename)
    {
        $file = $this->em->getRepository('SocietoUtilStorageBundle:File')->findOneBy(array(
            'filename' => $filename,
        ));

        if (!$file) {
            return false;
        }

        foreach ($file->getUriList() as $uri) {
            $binary = file_get_contents($uri);
            if ($binary) {
                return $binary;
            }
        }

        return false;
    }

    public function storeFromEntity(\Societo\Util\StorageBundle\Entity\File $file)
    {
        $binary = file_get_contents($file->file);
        $type = MimeTypeGuesser::getInstance()->guess($file->file);

        return $this->store($file->getFilename(), $binary, array(
            'type' => $type,
        ));
    }
}
