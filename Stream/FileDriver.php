<?php

/**
 * SocietoUtilStorageBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\Util\StorageBundle\Stream;

class FileDriver
{
    private $path;

    public function __construct($filesystem, $path)
    {
        $this->path = $path;

        $filesystem->mkdir($this->path);
    }

    public function generateUri($filename)
    {
        return 'file://'.$this->path.'/'.$filename;
    }
}
