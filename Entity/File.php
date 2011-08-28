<?php

/**
 * SocietoUtilStorageBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\Util\StorageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints as Assert;

use Societo\BaseBundle\Entity\BaseEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="file")
 * @DoctrineAssert\UniqueEntity("filename")
 */
class File extends BaseEntity
{
    /**
     * @ORM\Column(name="filename", type="string", length=128, unique=true)
     */
    protected $filename;

    /**
     * @ORM\Column(name="uri", type="array")
     */
    protected $uri = array();

    /**
     * @ORM\Column(name="parameters", type="array")
     */
    protected $parameters = array();

    /**
     * @Assert\File(maxSize = "700k", mimeTypes={"image/gif", "image/jpeg", "image/pjpeg", "image/png"})
     */
    public $file;

    public function __construct($filename = '', $parameters = array())
    {
        $this->filename = $filename;
        $this->parameters = $parameters;
    }

    public function setRandomizedFilename($prefix = '', $suffix = '', $additionalSource = '')
    {
        $this->setFilename($prefix.md5(time().mt_rand().$additionalSource).$suffix);
    }

    public function setFilenameFromOriginal()
    {
        // TODO:
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function resetUriList()
    {
        $this->uri = array();
    }

    public function addUri($uri)
    {
        $this->uri[] = $uri;
    }

    public function getUriList()
    {
        return $this->uri;
    }
}
