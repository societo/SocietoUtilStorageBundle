<?php

/**
 * SocietoUtilStorageBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\Util\StorageBundle\Stream;

class DoctrineDriver
{
    public function __construct($em)
    {
        if (!in_array('doctrine.orm', stream_get_wrappers())) {
            stream_wrapper_register('doctrine.orm', '\Societo\Util\StorageBundle\Stream\DoctrineStreamWrapper');
        }

        stream_context_get_default(array(
            'doctrine.orm' => array(
                'em' => $em,
            ),
        ));
    }

    public function generateUri($filename)
    {
        return 'doctrine.orm://SocietoUtilStorageBundle.FileBinary?filename='.$filename.'#bin';
    }
}
