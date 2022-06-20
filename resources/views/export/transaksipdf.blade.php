<table class="table" style="border:1px.solid.#add">
	<thead>

	<tr>
		<th>id</th>
		<th>Invoice</th>
		<th>User_id</th>
		<th>Subtotal</th>
		<th>Status_order_id</th>
		<th>Metode_pembayaran</th>
		 

	</tr>
</thead>
<tbody>
	@foreach($transaksi as $order)
	<tr>

		<td>{{$order->id}}</td>
		<td>{{$order->invoice}}</td>
		<td>{{$order->user_id}}</td>
		<td>{{$order->subtotal}}</td>
		<td>{{$order->status_order_id}}</td>
		<td>{{$order->metode_pembayaran}}</td>
	
	</tr>
	@endforeach
</tbody>
</table>
 