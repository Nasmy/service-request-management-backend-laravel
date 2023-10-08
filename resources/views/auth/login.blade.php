
@extends('layouts.guest')
@section('title', 'Home')

@section('content')

<section class="form-signin">
    <form method="POST" action="{{route('authenticate')}}">
    @csrf
    <img class="img-fluid" src="{{ asset('img/smallLogo.png') }}" >
    <h1 class="mb-3 text-center">CitizenBox</h1>
      <p class="fw-lighter text-center">Admin Portal</p>
      <div class="form-floating mb-4">
        <input type="text" name="username" class="form-control" id="floatingInput" placeholder="Email">
          @error('username')
          <strong style="color:red;">{{ $errors->first('username') }}</strong>
          @enderror
        <label for="floatingInput">User</label>
      </div>
      <div class="form-floating mb-4">
         <input type="password"  name="password" class="form-control" id="floatingPassword" placeholder="Password">
          @error('password')
          <strong style="color:red;">{{ $errors->first('password') }}</strong>
          @enderror
        <label for="floatingPassword">Password</label>
      </div>
      <button class="w-100 btn btn-lg btn-dark" type="submit">Log in</button>
    </form>
</section>
@endsection

