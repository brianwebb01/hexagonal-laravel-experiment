<?php namespace Contracts\Notification;

use Validators\Validator;
use Contracts\Instances\InstanceInterface;

interface CreatorInterface
{
    public function creationSucceeded(InstanceInterface $instance);

    public function creationFailed(Validator $validator);
}
