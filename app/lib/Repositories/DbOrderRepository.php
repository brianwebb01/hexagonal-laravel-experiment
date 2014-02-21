<?php namespace Repositories;

use Contracts\Repositories\OrderRepositoryInterface;
use Order;

class DbOrderRepository extends DbRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        $this->model = $model;
    }
}
