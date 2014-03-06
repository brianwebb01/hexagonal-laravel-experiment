<?php

class OrderTest extends TestCase
{

    /**
     * Test to ensure that the entity implements
     * the required interfaces
     *
     * @return void
     */
    public function testInterfacesArePresent()
    {
        $refl = new \ReflectionClass("Order");

        $this->assertTrue(
            $refl->implementsInterface('Contracts\Instances\InstanceInterface'),
            'Order does not implement InstanceInterface'
        );
    }
}
