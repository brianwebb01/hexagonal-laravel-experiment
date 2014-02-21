<?php namespace Services\Orders;

use Contracts\Repositories\OrderRepositoryInterface;
use Contracts\Notification\DestroyerInterface;
use Validators\OrderValidator;

class OrderDestroyer
{

    public function destroy(OrderRepositoryInterface $order, DestroyerInterface $listener, $identity, array $attributes = [])
    {
        $instance = $order->find($identity);

        if ($instance->delete()) {

            return $listener->destroySucceeded($instance);

        } else {

            return $listener->destroyFailed($instance);
        }
    }
}
