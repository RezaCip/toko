@extends('testing')
@section('title', "Convert")
@section('judul', "Convert")
@section('content')
<div class="row">
	<div class="col-xl-12">
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">CSVtoDB</h6>
			</div>
			<div class="card-body">
				<form action="{{url('/csv')}}" method="post" enctype="multipart/form-data">
					{{csrf_field()}}
					<input type="file" name="file">
					<button class="btn btn-primary float-right" type="submit">Upload</button>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection