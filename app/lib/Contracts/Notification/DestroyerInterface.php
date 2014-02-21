<?php namespace Contracts\Notification;

use Validators\Validator;
use Contracts\Instances\InstanceInterface;

interface DestroyerInterface
{
    public function destroySucceeded(InstanceInterface $instance);

    public function destroyFailed(InstanceInterface $instance);
}
