<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AbstractRepository
 * @package App\Repositories
 */
class AbstractRepository
{
    /**
     * @var Model
     */
    protected $entity;

    /**
     * @var string Model class name
     */
    protected $entityClassName;

    public function __construct()
    {
        $this->entity = $this->getEntity();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->entity->create($data);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function getEntity()
    {
        if (!$this->entityClassName) {
            throw new \Exception('Entity class name not set!');
        }

        return app()->make($this->entityClassName);
    }
}