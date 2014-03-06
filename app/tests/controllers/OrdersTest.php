<?php

use Mockery as m;

class OrdersTest extends TestCase
{
    public function __construct()
    {
        $this->entity = m::mock('Order');

        $this->repository = m::mock(
            'Contracts\Repositories\OrderRepositoryInterface',
            [$this->entity]
        );

        $this->collection = m::mock('Illuminate\Database\Eloquent\Collection')
            ->shouldDeferMissing();

        $this->controller = m::mock(
            'OrdersController[destroySucceeded,destroyFailed,updateSucceeded,updateFailed,creationSucceeded,creationFailed]',
            [$this->repository]
        );

        $this->validator = m::mock(
            'Validators\OrderValidator[validate]'
        );
    }



    public function setUp()
    {
        parent::setUp();

        $this->attributes = [
            'id'           => 1,
            'order_number' => '9878786',
            'amount'       => 1000,
            'order_date'   => '2014-03-05 05:53:00',
            'created_at'   => '2014-03-05 05:53:00',
            'updated_at'   => '2014-03-05 05:53:00'
        ];

        $this->app->instance(
            'Order',
            $this->entity
        );

        $this->app->instance(
            'Contracts\Repositories\OrderRepositoryInterface',
            $this->repository
        );

        $this->app->instance('OrdersController', $this->controller);

        $this->app->instance('Validators\OrderValidator', $this->validator);
    }



    public function tearDown()
    {
        m::close();
    }



    public function testIndex()
    {
        $this->repository
            ->shouldReceive('all')
            ->once()
            ->andReturn($this->collection);

        $this->call('GET', 'orders');

        $this->assertViewHas('orders');
    }



    public function testCreate()
    {
        $this->call('GET', 'orders/create');

        $this->assertResponseOk();
    }



    public function testStore()
    {
        $this->validator
            ->shouldReceive('validate')
            ->once()
            ->with($this->attributes)
            ->andReturn(true);

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->with($this->attributes)
            ->andReturn($this->entity);

        $this->controller
            ->shouldReceive('creationSucceeded')
            ->once()
            ->with($this->entity)
            ->andReturn("OK");

        $this->call('POST', 'orders', $this->attributes);
    }



    public function testStoreFails()
    {
        $this->validator
            ->shouldReceive('validate')
            ->once()
            ->with($this->attributes)
            ->andReturn(false);

        $this->controller
            ->shouldReceive('creationFailed')
            ->once()
            ->with($this->validator)
            ->andReturn("OK");

        $this->call('POST', 'orders', $this->attributes);
    }



    public function testShow()
    {
        $this->repository->shouldReceive('find')
                   ->with(1)
                   ->once()
                   ->andReturn((object) $this->attributes);

        $this->call('GET', 'orders/1');

        $this->assertViewHas('order');
    }



    public function testEdit()
    {
        $this->collection->id = 1;

        $this->repository->shouldReceive('find')
                   ->with(1)
                   ->once()
                   ->andReturn($this->collection);

        $this->call('GET', 'orders/1/edit');

        $this->assertViewHas('order');
    }



    public function testUpdate()
    {
        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($this->entity);

        $this->validator
            ->shouldReceive('validate')
            ->once()
            ->with($this->attributes)
            ->andReturn(true);

        $this->entity
            ->shouldReceive('update')
            ->once()
            ->with($this->attributes);

        $this->controller
            ->shouldReceive('updateSucceeded')
            ->once()
            ->with($this->entity)
            ->andReturn("OK");

        $this->call('PATCH', 'orders/1', $this->attributes);
    }



    public function testUpdateFails()
    {
        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($this->entity);

        $this->validator
            ->shouldReceive('validate')
            ->once()
            ->with($this->attributes)
            ->andReturn(false);

        $this->controller
            ->shouldReceive('updateFailed')
            ->once()
            ->with($this->entity, $this->validator)
            ->andReturn("OK");

        $this->call('PATCH', 'orders/1', $this->attributes);
    }



    public function testDestroy()
    {
        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($this->entity);

        $this->entity
            ->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $this->controller
            ->shouldReceive('destroySucceeded')
            ->once()
            ->with($this->entity)
            ->andReturn("OK");

        $this->call('DELETE', 'orders/1');
    }



    public function testDestroyFails()
    {
        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($this->entity);

        $this->entity
            ->shouldReceive('delete')
            ->once()
            ->andReturn(false);

        $this->controller
            ->shouldReceive('destroyFailed')
            ->once()
            ->with($this->entity)
            ->andReturn("OK");

        $this->call('DELETE', 'orders/1');
    }
}
