<?php namespace Services\Orders;

use Contracts\Repositories\OrderRepositoryInterface;
use Contracts\Notification\UpdaterInterface;
use Validators\OrderValidator;

class OrderUpdater
{

    protected $validator;


    public function __construct(OrderValidator $validator)
    {
        $this->validator = $validator;
    }

    public function update(OrderRepositoryInterface $order, UpdaterInterface $listener, $identity, array $attributes = [])
    {
        $instance = $order->find($identity);

        if ($this->validator->validate($attributes)) {

            $instance->update($attributes);

            return $listener->updateSucceeded($instance);

        } else {

            return $listener->updateFailed($instance, $this->validator);
        }
    }
}
