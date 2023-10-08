@extends('layouts.app')
@section('content')
<h1 class="display-6 fw-bold border-bottom">Edit Account Password</h1>
@if ($errors->any())
<ul class="alert alert-danger">
  {!! implode('',$errors->all('<li class="list-group-item bg-danger">:message</li>')) !!}
</ul>
@endif
<form method="POST" action="{{route('user.update-password', $user->id)}}">
  @csrf
  <input type="hidden" name="_method" value="PUT">
  <div class="row p-2">
    <div class="col">
      <label for="lastName" class="form-label fw-bold">Password</label>
      <input type="password" class="form-control" name="password" value="{{ old('password') }}">
      @error('password')
      <strong style="color:red;">{{ $errors->first('password') }}</strong>
      @enderror
    </div>
    <div class="col">
      <label for="lastName" class="form-label fw-bold">Confirm Password</label>
      <input type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}">
      @error('password_confirmation')
      <strong style="color:red;">{{ $errors->first('password_confirmation') }}</strong>
      @enderror
    </div>
  </div>

  <div class="col-1 p-2">
    <button class="btn btn-dark btn-lg" type="submit">Update</button>
  </div>
</form>
@endsection
@section('footer')
@endsection