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

use Societo\BaseBundle\Entity\BaseEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="file_binary")
 */
class FileBinary extends BaseEntity
{
    /**
     * @ORM\Column(name="filename", type="string")
     */
    protected $filename;

    /**
     * @ORM\Column(name="bin", type="blob")
     */
    protected $bin;

    public function __construct($filename = '', $bin = '')
    {
        $this->setBin($bin);
        $this->setFilename($filename);
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setBin($bin)
    {
        $this->bin = $bin;
    }

    public function getBin()
    {
        return $this->bin;
    }
}
