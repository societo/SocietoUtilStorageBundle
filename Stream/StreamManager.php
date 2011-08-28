<?php

/**
 * SocietoUtilStorageBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\Util\StorageBundle\Stream;

class StreamManager
{
    private $handlers = array();

    public function addStreamHandler($ref)
    {
        $this->handlers[] = $ref;
    }
}
