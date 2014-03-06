<?php namespace Services\Orders;

use Contracts\Repositories\OrderRepositoryInterface;
use Contracts\Notification\CreatorInterface;
use Validators\OrderValidator;

class OrderCreator
{

    protected $validator;


    /**
     * Inject the validator that will be used for
     * creation
     * 
     * @param OrderValidator $validator
     */
    public function __construct(OrderValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Attempt to create a new order with the given attributes and
     * notify the $listener of the success or failure
     * 
     * @param  OrderRepositoryInterface $order     
     * @param  CreatorInterface         $listener  
     * @param  array                    $attributes
     * @return mixed - returned value from the $listener                        
     */
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
