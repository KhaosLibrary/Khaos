<?php

namespace Khaos\Schema;

interface Schema
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getSchema();

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function getInstance($data);
}