@extends('layouts.app')
@section('title', 'Home Page')


@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2>Dashboard</h2>
                <p>Welcome, Guest</p>
@if(session('error'))
 <div class="p-2">

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Access Denied!</strong><br>
        {{ session('error') }}
 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  </div>
    </div>
@endif

            </div>
        </div>
    </div>

    <div class="container">
        <p>Welcome to the system!</p>
    </div>


</div>
@endsection
