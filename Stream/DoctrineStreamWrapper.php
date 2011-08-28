<?php

/**
 * SocietoUtilStorageBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\Util\StorageBundle\Stream;

class DoctrineStreamWrapper
{
    public $context;

    private $entityName, $fields = array(), $column, $position, $binary;

    public function getEntityManager()
    {
        if (!$this->context) {
            $this->context = stream_context_get_default();
        }
        $options = stream_context_get_options($this->context);

        return $options['doctrine.orm']['em'];
    }

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $url = parse_url($path);
        if (empty($url['host']) && empty($url['query']) && empty($url['fragment'])) {
            return false;
        }

        $this->entityName = strtr($url['host'], '.', ':');
        $this->column = $url['fragment'];

        $queries = explode('&', $url['query']);
        foreach ($queries as $v) {
            $q = explode('=', $v);

            $this->fields[$q[0]] = $q[1];
        }

        return true;
    }

    public function stream_read($count)
    {
        if (!$this->binary) {
            $entity = $this->getEntityManager()->getRepository($this->entityName)->findOneBy($this->fields);
            if (!$entity) {
                return false;
            }

            $getter = 'get'.\Doctrine\Common\Util\Inflector::camelize($this->column);

            $this->binary = $entity->$getter();
        }

        if (!$this->binary) {
            return false;
        }

        $data = substr($this->binary, $this->position, $count);
        $this->position += strlen($data);

        return $data;
    }

    public function stream_flush()
    {
        $className = $this->getEntityManager()->getClassMetadata($this->entityName)->getReflectionClass()->getName();
        $binary = new $className();

        foreach ($this->fields as $field => $value) {
            $setter = 'set'.\Doctrine\Common\Util\Inflector::camelize($field);
            $binary->$setter($value);
        }

        $binSetter = 'set'.\Doctrine\Common\Util\Inflector::classify($this->column);
        $binary->$binSetter($this->binary);

        $this->getEntityManager()->persist($binary);
        $this->getEntityManager()->flush();
        unset($binary, $this->binary);

        return true;
    }

    public function stream_write($data)
    {
        $length = strlen($data);
        $this->binary = substr($this->binary, 0, $this->position)
                      . $data
                      . substr($this->binary, $this->position + $length);
        $this->position += $length;

        return $length;
    }

    public function stream_tell()
    {
        return $this->position;
    }

    public function stream_eof()
    {
        return ($this->position >= strlen($this->binary));
    }

    public function stream_stat()
    {
        $stat = array(
            'size' => strlen($this->binary),
        );

        return $stat;
    }

    public function stream_seek($offset, $step)
    {
        switch ($step) {
            case SEEK_CUR:
                $this->position += $offset;
                break;
            case SEEK_END:
                $this->position = strlen($this->binary) + $offset;
                break;
            case SEEK_SET:
                $this->position = $offset;
                break;
            default:
        }

        return $this->position;
    }
}
