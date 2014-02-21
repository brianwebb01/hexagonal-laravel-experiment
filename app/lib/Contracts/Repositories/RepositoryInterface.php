<?php namespace Contracts\Repositories;

interface RepositoryInterface
{
    public function all();

    public function find($id);

    public function create(array $array);
}
