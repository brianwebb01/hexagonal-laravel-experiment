<?php

use Services\OrderCreator;
use Contracts\Repositories\OrderRepositoryInterface;
use Contracts\Instances\InstanceInterface;
use Contracts\Notification\CreatorInterface;
use Contracts\Notification\UpdaterInterface;
use Contracts\Notification\DestroyerInterface;
use Validators\Validator as Validator;

class OrdersController extends BaseController implements CreatorInterface, UpdaterInterface, DestroyerInterface
{

    /**
     * Order Repository
     *
     * @var OrderRepositoryInterface
     */
    protected $order;

    public function __construct(OrderRepositoryInterface $order)
    {
        $this->order = $order;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $orders = $this->order->all();

        return View::make('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('orders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $order_creator = App::make('Services\Orders\OrderCreator');

        return $order_creator->create($this->order, $this, Input::except('_token'));
    }

    /**
     * Handle successful order creation
     *
     * @param  InstanceInterface $order
     * @return Redirect::route
     */
    public function creationSucceeded(InstanceInterface $instance)
    {
        return Redirect::route('orders.index')->with('message', 'Order was successfully created');
    }

    /**
     * Handle an error with order creation
     *
     * @param  Validator\Validator      $validator
     * @return Redirect::route
     */
    public function creationFailed(Validator $validator)
    {
        return Redirect::route('orders.create')
            ->withInput()
            ->withErrors($validator->errors())
            ->with('message', 'Oops, there was an error');
    }

    /**
     * Display the specified resource.
     *
     * @param  int      $id
     * @return Response
     */
    public function show($id)
    {
        $order = $this->order->find($id);

        return View::make('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int      $id
     * @return Response
     */
    public function edit($id)
    {
        $order = $this->order->find($id);

        return View::make('orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int      $id
     * @return Response
     */
    public function update($id)
    {
        $order_updater = App::make('Services\Orders\OrderUpdater');

        return $order_updater->update($this->order, $this, $id, Input::except('_method'));
    }

    /**
     * Handle successful order update
     *
     * @param  InstanceInterface $order
     * @return Redirect::route
     */
    public function updateSucceeded(InstanceInterface $instance)
    {
        return Redirect::route('orders.show', $instance->identity());
    }

    /**
     * Handle an error with order update
     *
     * @param  InstanceInterface $order
     * @param  Validator\Validator      $validator
     * @return Redirect::route
     */
    public function updateFailed(InstanceInterface $instance, Validator $validator)
    {
        return Redirect::route('orders.edit', $instance->identity())
            ->withInput()
            ->withErrors($validator->errors())
            ->with('message', 'Oops, there was an error');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int      $id
     * @return Response
     */
    public function destroy($id)
    {
        $order_destroyer = App::make('Services\Orders\OrderDestroyer');

        return $order_destroyer->destroy($this->order, $this, $id, Input::except('_method'));
    }

    /**
     * Handle successful order destroy
     *
     * @param  InstanceInterface $instance
     * @return Redirect::route
     */
    public function destroySucceeded(InstanceInterface $instance)
    {
        return Redirect::route('orders.index')->with('message', 'Order was successfully deleted');
    }

    /**
     * Handle an error with order destroy
     *
     * @param  InstanceInterface $order
     * @return Redirect::route
     */
    public function destroyFailed(InstanceInterface $instance)
    {
        return Redirect::route('orders.index')->with('message', 'Oops, couldn\'t delete that order');
    }
}
