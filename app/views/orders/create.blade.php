@extends('layouts.scaffold')

@section('main')

<h1>Create Order</h1>

@if ($errors->any())
    <ul>
        {{ implode('', $errors->all('<li class="error">:message</li>')) }}
    </ul>
@endif

{{ Form::open(array('route' => 'orders.store')) }}
	<ul>
        <li>
            {{ Form::label('order_number', 'Order_number:') }}
            {{ Form::text('order_number') }}
        </li>

        <li>
            {{ Form::label('amount', 'Amount:') }}
            {{ Form::input('number', 'amount') }}
        </li>

        <li>
            {{ Form::label('order_date', 'Order_date:') }}
            {{ Form::text('order_date') }}
        </li>

		<li>
			{{ Form::submit('Submit', array('class' => 'btn btn-info')) }}
		</li>
	</ul>
{{ Form::close() }}

@stop


