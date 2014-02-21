<?php

use Contracts\Instances\InstanceInterface;

class Order extends Eloquent implements InstanceInterface
{
    protected $guarded = [];

    public function identity()
    {
        return $this->id;
    }
}
