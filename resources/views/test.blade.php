@extends('welcome')

@section('css')
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

	<style>
		.product-image {
			max-width: 100px;
			max-height: 100px;
			object-fit: contain;
		}
	</style>
@endsection

@section('content')
	<div class="container text-center">
		<h1>Test Application</h1>
	</div>
	<div class="container">
		<input class="form-control mb-4 mt-4" type="text" id="search" placeholder="Search products...">
	</div>

	<div class="container">
		<table id="productTable" class="display">
			<thead>
				<tr>
					<th>ID</th>
					<th>Image</th>
					<th>Title</th>
					<th>Price</th>
					<th>Discount Percentage</th>
					<th>Brand</th>
					<th>Category
						<select id="categoryFilter">
							<option value="all">All</option>
						</select>
					</th>
					<th>Stock</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($products as $product)
					<tr>
						<td>{{ $product['id'] }}</td>
						<td>
							<img src="{{ $product['images'][3] ?? '#' }}" alt="Image" width="50"
								onerror="this.src='https://via.placeholder.com/50';">
						</td>
						<td>{{ $product['title'] ?? 'No data found' }}</td>
						<td>{{ $product['price'] ?? 'No data found' }}</td>
						<td>{{ $product['discountPercentage'] ?? 'No data found' }}</td>
						<td>{{ $product['brand'] ?? 'No data found' }}</td>
						<td>{{ $product['category'] ?? 'No data found' }}</td>
						<td>{{ $product['stock'] ?? 'No data found' }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection

@section('js')
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script>
		$(document).ready(function() {
			const table = $('#productTable').DataTable({
				paging: true,
				searching: false,
				sorting: false,
			});

			// Fetch categories for dropdown
			$.get('{{ route('products.categories') }}', function(data) {
				data.forEach(category => {
					$('#categoryFilter').append(
						`<option value="${category.slug}">${category.name}</option>`
					);
				});
			});

			// Based on category display product
			$('#categoryFilter').on('change', function() {
				const category = $(this).val();

				const url = category !== 'all' ?
					`{{ route('products.filter') }}?category=${category}` :
					'{{ route('products.index') }}';

				$.get(url, function(data) {
					table.clear();
					if (data.products && data.products.length > 0) {
						data.products.forEach(product => {
							table.row.add([
								product.id || 'No data found',
								`<img src="${product.images[2] || '#'}" alt="Image" width="50" onerror="this.src='https://via.placeholder.com/50';">`,
								product.title || 'No data found',
								product.price || 'No data found',
								product.discountPercentage || 'No data found',
								product.brand || 'No data found',
								product.category || 'No data found',
								product.stock || 'No data found',
							]);
						});
					} else {
						table.row.add([
							'No products found', '', '', '', '', '', '', ''
						]);
					}
					table.draw();
				});
			});

			// Search functionality
			$('#search').on('keyup', function() {
				const query = $(this).val();
				if (!query) return;

				$.get(`{{ route('products.search') }}?q=${query}`, function(data) {
					table.clear();
					data.products.forEach(product => {
						table.row.add([
							product.id || 'No data found',
							`<img src="${product.images[2] || '#'}" alt="Image" width="50" onerror="this.src='https://via.placeholder.com/50';">`,
							product.title || 'No data found',
							product.price || 'No data found',
							product.discountPercentage || 'No data found',
							product.brand || 'No data found',
							product.category || 'No data found',
							product.stock || 'No data found',
						]);
					});
					table.draw();
				});
			});
		});
	</script>
@endsection
