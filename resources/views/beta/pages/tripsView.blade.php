@extends('beta.layouts.index')   


@section('content')

<div class="container mt-5">
<div class="row mt-5">
  <h3 class="text-center">Trips</h3>
  <div class="col-md-12 mt-5">
    <ul class="list-group list-group-flush">
      <li class="list-group-item">An item</li>
      <li class="list-group-item">A second item</li>
      <li class="list-group-item">A third item</li>
    </ul>

  </div>
</div>

  

</div>


@endsection


@push('scripts')
  <script >

    $( document ).ready(function() {


      loadTrips();

      function loadTrips(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'AppToken' : 'BetaCounterApp',
                'Source' : 'BetaCounterApp',
                'SessonToken': localStorage.getItem('SessonToken'),
                'UserToken': localStorage.getItem('UserToken'),
                'CounterToken': localStorage.getItem('CounterToken'),
            }
        });

        let _token = "{{ csrf_token() }}";
        let counterToken = localStorage.getItem('CounterToken');
        
        $.ajax({
          type:'get',
          url:"{{ url('beta/ajax/trips/') }}"+counterToken,
          data:{_token:_token},
          success:function(data){

            console.log(data);

          }
        });
      }

      



    });


  </script>
@endpush
