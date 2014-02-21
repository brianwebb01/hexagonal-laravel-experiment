<?php namespace Contracts\Instances;

interface InstanceInterface
{
    public function fill(array $array);

    public function identity();

    public function update(array $array);

    public function save();

    public function delete();
}
