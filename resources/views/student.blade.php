@extends('layouts.app')

@section('content')
<form method="post" action="{{url('/store')}}">
	@csrf
  <div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input type="text" name="full_name" class="form-control" id="" placeholder="Enter Name">
    
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Email</label>
    <input type="email" name="email" class="form-control" id="exampleInputPassword1" placeholder="Enter Email">
  </div>
   <div class="form-group">
    <label for="exampleInputPassword1">Phone</label>
    <input type="text" name="phone" class="form-control" id="exampleInputPassword1" placeholder="Enter Phone">
  </div>
 
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection