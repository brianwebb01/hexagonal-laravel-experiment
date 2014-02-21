<?php namespace Services\Orders;

use Contracts\Repositories\OrderRepositoryInterface;
use Contracts\Notification\CreatorInterface;
use Validators\OrderValidator;

class OrderCreator
{

    protected $validator;


    public function __construct(OrderValidator $validator)
    {
        $this->validator = $validator;
    }

    public function create(OrderRepositoryInterface $order, CreatorInterface $listener, array $attributes = [])
    {
        if ($this->validator->validate($attributes)) {

            $instance = $order->create($attributes);
            
            return $listener->creationSucceeded($instance);

        } else {

            return $listener->creationFailed($this->validator);
        }
    }
}
