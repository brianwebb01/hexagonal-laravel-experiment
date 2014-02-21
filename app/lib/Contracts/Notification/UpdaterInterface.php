<?php namespace Contracts\Notification;

use Validators\Validator;
use Contracts\Instances\InstanceInterface;

interface UpdaterInterface
{
    public function updateSucceeded(InstanceInterface $instance);

    public function updateFailed(InstanceInterface $instance, Validator $validator);
}
