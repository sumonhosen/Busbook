@extends('beta.layouts.index')


@section('content')

<div class="container mt-5">
<div class="row mt-5">
  <div class="col-md-12 mt-5">
    <h3 class="text-center">Login </h3>

    <div class="mb-3">
      <label for="phone" class="form-label">Phone</label>
      <input type="tel" class="form-control" id="phone"  name="phone" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" name="password" id="password">
    </div>

    <div class="d-grid gap-2">
      <button type="submit" class="btn  btn-success" id="login">Login</button>
    </div>


    @if(session('status'))
      <div class="alert alert-success" role="alert">
          {{session('status')}}
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger" role="alert">
          {{session('error')}}
      </div>
    @endif

  </div>
</div>



</div>


@endsection


@push('scripts')
  <script >

    $( document ).ready(function() {

      if(localStorage.getItem('DeviceToken') == null){
        DeviceToken();
      }

      $("#login").click(function(){
        login();
        $("#login").attr("disabled", true);
      });

      function login(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'AppToken' : 'BetaCounterApp',
                'Source' : 'BetaCounterApp',
                'DeviceToken' : localStorage.getItem('DeviceToken'),
            }
        });
        let _token = "{{ csrf_token() }}";
        let phone = $('#phone').val();
        let password = $('#password').val();
        let baseUrl = "{{ url('/') }}";

        $.ajax({
          type:'post',
          url:"{{ url('beta/ajax/login/action') }}",
          data:{_token:_token, phone:phone, password:password },
          success:function(data){
            $("#login").attr("disabled", false);

            if(data.status == 'Success'){
              localStorage.setItem('SessionToken', data.sessionToken);
              localStorage.setItem('CounterToken', data.counter.token);
              localStorage.setItem('UserToken', data.user.token);
              localStorage.setItem('userName', data.user.name);
              localStorage.setItem('userphone', data.user.phone);
              localStorage.setItem('counterTitle', data.counter.title);

              window.location.href = baseUrl + '/admin';
            }
            else{
              alert('Wrong Credential');
            }



          }
        });
      }






      function DeviceToken(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'AppToken' : 'BetaCounterApp',
                'Source' : 'BetaCounterApp'
            }
        });
        let _token = "{{ csrf_token() }}";
        $.ajax({
          type:'post',
          url:"{{ url('beta/ajax/device/token') }}",
          data:{_token:_token},
          success:function(data){

            localStorage.setItem('DeviceToken', data.DeviceToken.token);
          }
        });
      }





    });


  </script>
@endpush
