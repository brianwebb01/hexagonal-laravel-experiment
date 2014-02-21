@extends('layouts.scaffold')

@section('main')

<h1>All Orders</h1>

<p>{{ link_to_route('orders.create', 'Add new order') }}</p>

@if ($orders->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Order_number</th>
				<th>Amount</th>
				<th>Order_date</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($orders as $order)
				<tr>
					<td>{{{ $order->order_number }}}</td>
					<td>{{{ $order->amount }}}</td>
					<td>{{{ $order->order_date }}}</td>
                    <td>{{ link_to_route('orders.show', 'View', array($order->id), array('class' => 'btn')) }}</td>
                    <td>{{ link_to_route('orders.edit', 'Edit', array($order->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('orders.destroy', $order->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no orders
@endif

@stop
