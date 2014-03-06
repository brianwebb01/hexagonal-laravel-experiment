<?php

use Contracts\Instances\InstanceInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Order extends Eloquent implements InstanceInterface
{
    protected $guarded = [];

    public function identity()
    {
        return $this->id;
    }
}
