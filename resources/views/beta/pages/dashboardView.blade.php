@extends('beta.layouts.index')

@section('content')

<div class="container " id="dashboardPage">
  <div class="row ">
    <div class="col-md-12 ">

      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Name">
      </div>

      <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
      </div>

      <div class="mb-3">
        <label for="from" class="form-label">From</label>
        <select class="form-select bb-select-list" id="from" aria-label="Default select example" >
          <option value="" selected>Open this select menu</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="to" class="form-label">To</label>
        <select class="form-select bb-select-list" id="to" aria-label="Default select example" >
          <option value="" selected>Select </option>
        </select>
      </div>

      <div class="mb-3">
        <label for="date" class="form-label">Date</label>
        <input type="date" id="date" class="form-control">
      </div>

      <div class="mb-3">
        <button type="button" class="btn btn-success" id="submit" >Submit </button>
      </div>

    </div>
  </div>

  <div class="row ">
    <h3 class="text-center">Trips</h3>
    <div class="col-md-12 ">
      <ol class="list-group list-group-numbered" id="trips">
      </ol>
    </div>
  </div>

  <div class="row ">
    <h3 class="text-center">Seats</h3>
    <div class="col-md-12 ">
      <ol class="list-group list-group-numbered" id="seats">

      </ol>
    </div>
  </div>
  <div class="row ">
    <h3 class="text-center">Booking Details</h3>
    <div class="col-md-12 ">
      <ol class="list-group list-group-numbered" id="booking_seat">

      </ol>
    </div>
  </div>
</div>

@endsection


@push('scripts')
  <script>

    function payNow(){
        $("#booking_form").submit(function(e) {
            e.preventDefault();

                let trip_break_token   = $("input[name=trip_break_token]").val();
                let trip_booking_token = $("input[name=trip_booking_token]").val();
                let start_time         = $("input[name=start_time]").val();
                let _token = "{{ csrf_token() }}";

                $.ajax({
                type: 'POST',
                url : "{{ url('beta/ajax/booking_seat') }}",
                data:  {_token:_token,trip_break_token:trip_break_token,trip_booking_token:trip_booking_token,start_time:start_time},
                success: function (data) {

                },
            });
        });
    }
    function getSeat(){
        $("#my_form").submit(function(e) {
            e.preventDefault();

            let trip_booking_token = $("input[name=trip_booking_token]").val();
            let trip_break_token   = $("input[name=trip_break_token]").val();
            let start              = $("input[name=start]").val();
            let end                = $("input[name=end]").val();

            let inputs = $('.seat_inputs:checked').map(function () {
                return $(this).val();
            });;
            let seats = inputs.get();
            let _token = "{{ csrf_token() }}";

                $.ajax({
                type: 'POST',
                url : "{{ url('beta/ajax/show_seat') }}",
                data:  {_token:_token,seats:seats,trip_booking_token:trip_booking_token,trip_break_token:trip_break_token,start:start,end:end},
                success: function (data) {
                    console.log(data);
                    let booking_seat = "";
                    $('#booking_seat').empty();
                    booking_seat +=`<form method="post" id="booking_form">
                                        <table border="1">
                                        <tr>
                                          <th>Sl</th>
                                          <th>Seat</th>
                                          <th>Unique</th>
                                          <th>Fare</th>
                                          <th>Charge</th>
                                        </tr>`

                                        $.each(data.booking_seats, function(k, v) {
                                          booking_seat+=`<tr>
                                                          <td><label>${ k+1 }</label></td>
                                                          <td><label>${ v.seat_details }</label></td>
                                                          <td><label>${ v.unique_id }</label></td>
                                                          <td><label>${ v.fare }</label></td>
                                                          <td><label>${ v.online_charge }</label></td>
                                                        </tr>`;
                                                      });
                                            booking_seat+=`</table>
                                                <div class="card" style="width:15%">
                                                    <p >Total Fare : ${ data.total_fare} </p>
                                                    <p>Total Charge : ${ data.total_charge}</p>
                                                    <p>Sub Total : ${ data.sub_total}</p>
                                                    <p>Journey Time : ${ data.start_time}</p>
                                                </div>
                                                <div class="card" style="width:15%">
                                                    <h6>Booking Information</h6>
                                                    <p>Booking Number : ${ data.booking_number} </p>
                                                </div>

                                                <input type="hidden" name="start_time" value="${data.start_time}">
                                                <input type="hidden" name="trip_break_token" value="${data.trip_break_token}">
                                                <input type="hidden" name="trip_booking_token" value="${data.trip_booking_token}">
                                                <input type="submit" class="btn btn-success btn-sm" onclick="payNow()" value="Submit">
                                        </form>
                                        <form method="POST" class="needs-validation" novalidate action="{{ url('beta/pay')}}">
                                        @csrf
                                        <input type="hidden" name="booking_token" value="${data.trip_booking_token}">
                                        <input type="hidden" name="break_token" value="${data.trip_break_token}">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block"> Pay Now
                                            </button>
                                        </form>`;

                                $('#booking_seat').html(booking_seat);
                },
            });
        });
    };


    function seat_list(start,end,bus_token,trip_booking_token,trip_break_token){

        let _token = "{{ csrf_token() }}";
            $.ajax({
            type:'get',
            url:"{{ url('beta/ajax/seat_view') }}",
            data:{_token:_token,start:start,end:end,trip_booking_token:trip_booking_token,trip_break_token:trip_break_token,bus_token:bus_token},

            success:function(data){

                let seatList = "";
                $('#seats').empty();
                seatList +=`<form method="post" id="my_form">
                            <input type="hidden" value="${ trip_booking_token }" name="trip_booking_token">
                            <input type="hidden" value="${ trip_break_token }" name="trip_break_token">
                            <input type="hidden" value="${ start }" name="start">
                            <input type="hidden" value="${ end }" name="end">
                            <input type="hidden" value="${ bus_token }" name="bus_token">
                                <table border="1">`
                                    $.each(data.seats, function(k, v) {
                                        console.log(v);
                                        seatList+=`<tr>
                                                        <td>
                                                            <label><input type="checkbox" class="seat_inputs" name="seat[]" value="${v.s1}">${v.s1}</label>
                                                            <label><input type="checkbox" name="seat[]" class="seat_inputs" value="${v.s2}">${v.s2}</label>
                                                        </td>
                                                        <td>
                                                            <label><input type="checkbox" class="seat_inputs" name="seat[]" value="${v.s4}">${v.s4}</label>
                                                            <label><input type="checkbox" name="seat[]" class="seat_inputs" value="${v.s5}">${v.s5}</label>
                                                        </td>
                                                    </tr>`;
                                            });
                                    seatList+=`</table>
                                    <input type="submit" class="btn btn-success btn-sm" onclick="getSeat()" value="submit">
                            </form>`;
                    $('#seats').html(seatList);
                },
            });
        }

    $( document ).ready(function() {

      $('.bb-select-list').select2();

      if($('#dashboardPage').css("display") != "none" ){
        loadPlaces();

        $('#submit').click(function(){
          tripBooking();
        });

      }



      function tripBooking(){
        let name  = $('#name').val();
        let phone = $('#phone').val();
        let from  = $('#from').val();
        let to    = $('#to').val();
        let date  = $('#date').val();

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

        $.ajax({
          type:'post',
          url:"{{ url('beta/ajax/trip/booking/action') }}",
          data:{_token:_token, name:name, phone:phone, from:from, to:to, date:date},
          success:function(data){
            let trip_booking_token = data.TripBookingToken.token;
            tripList(from, to, trip_booking_token);
          }
        });
      }


      if(localStorage.getItem('DeviceToken') == null){
        DeviceToken();
      }


      function loadPlaces(){
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
          url:"{{ url('beta/ajax/places/list') }}",
          data:{_token:_token},
          success:function(data){
            let optionList = "";

            $.each(data.placeList, function(k, v) {
              optionList += "<option value='"+v.token+"'>"+v.title+"</option>";
            });

            $('#from').append(optionList);
            $('#to').append(optionList);
            }
        });
      }

      function tripList(from, to,trip_booking_token){

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

        $.ajax({
          type:'get',
          url:"{{ url('beta/ajax/trip/list') }}",
          data:{_token:_token, from:from, to:to,trip_booking_token:trip_booking_token},
          success:function(data){

            let optionList = "";
            $('#trips').empty();
            $.each(data.trips, function(k, v) {
              if(v.active == 1){

                optionList +=`<a onclick="seat_list('${v.start}','${v.end}','${v.bus.token}','${trip_booking_token}','${v.token}')"><li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                  <div class="fw-bold">From: ${v.departure_point.title}</div>
                                  <div class="fw-bold">To: ${v.destination_point.title}</div>
                                  Start: ${v.start}<br>
                                  End:   ${v.end}<br>
                                </div>
                                <div class="ms-2 me-auto">
                                  <div class="fw-bold">${v.bus.brand}</div>
                                   Fare: ${v.fare}</br>
                                   Charge: ${v.online_charge}</br>
                                </div>
                              </li></a>`;
                            }
                        });
                    $('#trips').append(optionList);
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
