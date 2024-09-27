@extends('header')

@section('content')
<div class="container mt-5">
    <h3>Import Customer Excel Data</h3>

    <!-- Display success message -->
    @if(session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Display validation errors -->
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form action="/import-excel" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="excelFile" class="form-label">Choose Excel File</label>
            <input type="file" name="excel_file" id="excelFile" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
    </form>
</div>
@endsection