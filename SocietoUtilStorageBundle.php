<?php

/**
 * SocietoUtilStorageBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\Util\StorageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Societo\Util\StorageBundle\DependencyInjection\Compiler\StreamHandlerPass;

/**
 * SocietoUtilStorageBundle
 *
 * @author Kousuke Ebihara <ebihara@php.net>
 */
class SocietoUtilStorageBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new StreamHandlerPass());
    }
}
