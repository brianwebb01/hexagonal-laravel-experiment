<?php namespace Repositories;

use Contracts\Repositories\RepositoryInterface;
use Contracts\Instances\InstanceInterface;

abstract class DbRepository implements RepositoryInterface
{

    protected $model;


    public function __construct(InstanceInterface $model){
        $this->model = $model;
    }


    public function all()
    {
        return $this->model->all();
    }


    public function find($id)
    {
        return $this->model->findOrFail($id);
    }


    public function fill(array $array)
    {
        return $this->model->fill($array);
    }


    public function create(array $array)
    {
        return $this->model->create($array);
    }
}
