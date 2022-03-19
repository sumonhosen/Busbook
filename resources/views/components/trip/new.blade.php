<div class="container-fluid">

  <div class="row">
    <div class="col-lg-12">
        <div class="card">
          <div class="card-body">

            <h4 class="mb-3 header-title">
              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addTrip">
                Add Trip
              </button>
            </h4>

            <!-- Modal -->
            <div class="modal fade" id="addTrip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Trip</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="{{ url('trip/new/action') }}" method="POST">
                    <div class="modal-body">
                      @csrf
                      <div class="form-group ">
                        <label for="type" class="col-form-label">Banner Token</label>
                        <select id="type" class="form-control bus-custom-bannerToken" onchange="getBanner_token(this)" name="bannerToken" required>
                          <option value="" selected disabled>Select Banner</option>
                          @foreach($bannerList as $index => $item)
                            <option value='{{ $item->token }}'>{{ $item->title }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="type" class="col-form-label">Bus List</label>
                        <select id="bus_token" class="form-control bus-custom-token" name="busToken" required>
                          <option selected>Select Bus</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="tripNumber">Trip Number</label>
                        <input type="text" placeholder="Trip Number" id="tripNumber" class="form-control" name="tripNumber" required>
                      </div>

                      <div class="form-group">
                        <label for="departurePoint">Departure Point</label>
                        <select class="form-control bus-custom-departurePoint" id="departurePoint" name="departurePoint" required>
                          <option value="" selected disabled>Select Departure Point</option>
                             @foreach($placeList as $index => $item)
                                <option value='{{ $item->token }}'>{{ $item->title }}</option>
                             @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="relatedDepartureCounter">Related Departure Counter</label>
                        <select class="form-control bus-custom-relatedDepartureCounter" id="relatedDepartureCounter" name="relatedDepartureCounter" required>
                          <option value="" selected disabled>Select Related Departure Counter</option>
                             @foreach($counterList as $index => $item)
                                <option value='{{ $item->token }}'>{{ $item->title }}</option>
                             @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="destinationPoint">Destination Point</label>
                        <select class="form-control bus-custom-destinationPoint" id="destinationPoint" name="destinationPoint" required>
                          <option value="" selected disabled>Select Related Destination Point</option>
                             @foreach($placeList as $index => $item)
                                <option value='{{ $item->token }}'>{{ $item->title }}</option>
                             @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="relatedDestinationCounter">Related Destination Counter</label>
                        <select class="form-control bus-custom-relatedDestinationCounter" id="relatedDestinationCounter" name="relatedDestinationCounter" required >
                          <option value="" selected disabled>Select Related Destination Counter</option>
                          @foreach($counterList as $index => $item)
                            <option value='{{ $item->token }}'>{{ $item->title }}</option>
                          @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="departureTime">Departure Time</label>
                        <input type="time" placeholder="Departure Time" id="departureTime" class="form-control" name="departureTime" required>
                      </div>

                      <div class="form-group">
                        <label for="destinationTime">Destination Time</label>
                        <input type="time" placeholder="Destination Time" id="destinationTime" class="form-control" name="destinationTime" required>
                      </div>
                        <div class="form-group">
                            <label for="destinationTime">Seat Access</label>
                            <select class="form-control seat-access" name="seatAccess" required>
                                <option value="" selected disabled>Select One</option>
                                <option value="1">Counter User</option>
                                <option value="2">App User</option>
                                <option value="3">All User</option>
                          </select>
                        </div>
                    </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info waves-effect waves-light ">Submit</button>
                  </div>
                </form>

                </div>
              </div>
            </div>

        @if(session('status'))
          <div class="alert alert-success" role="alert">
              {{session('status')}}
          </div>
        @endif

        <h4 class="mb-3 header-title">Trip List</h4>
        <div class="form-group">
            <input type="text" id="search" class="form-control" placeholder="Search">
        </div>

        <div class="table-responsive">
            <table  class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                      <th>SL</th>
                      <th>Banner</th>
                      <th>Bus</th>
                      <th>Trip Number</th>
                      <th>Departure Point</th>
                      <th>Related Departure Counter</th>
                      <th>Destination Point</th>
                      <th>Related Destination Counter</th>
                      <th>Departure Time</th>
                      <th>Destination Time</th>
                      <th>Seat Access</th>
                      <th>Action</th>
                    </tr>
                </thead>

                <tbody id="TableList">
                  @foreach($list as $index => $item)
                    <tr>
                        <td>{{ ++$index }}</td>
                        <td>{{ $item->banner->title_bangla }}</td>
                        <td>{{ $item->bus->brand }}</td>
                        <td>{{ $item->trip_number }}</td>
                        <td>{{ $item->departurePoint->title }}</td>
                        <td>{{ $item->relatedDepartureCounter->title }}</td>
                        <td>{{ $item->destinationPoint->title }}</td>
                        <td>{{ $item->relatedDestinationCounter->title }}</td>
                        <td>{{  date('h:i A', strtotime($item->departure_time)) }}</td>
                        <td>{{  date('h:i A', strtotime($item->destination_time)) }}</td>
                        <td>
                            @if($item->seat_access==1) Counter User
                            @elseif($item->seat_access==2) App User
                            @elseif($item->seat_access==3)
                            All User
                            @endif
                        </td>
                        <td>
                        <div class="btn-group">
                          <!-- Scrollable modal -->
                          <button  type="button" class="btn btn-info mr-1" data-toggle="modal" data-target="#view{{$item->id}}">View</button>
                          <button  type="button" class="btn btn-warning mr-1" data-toggle="modal" data-target="#edit{{$item->id}}">Edit</button>
                          <!-- Scrollable modal -->
                          <a type="button" class="btn btn-danger" href="{{ url('trip/breakdowns', $item->token) }}" >Breakdown</a>
                        </div>
                        </td>
                    </tr>

                    <!-- Modal View -->
                <div class="modal fade" id="view{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="view{{$item->id}}" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="view{{$item->id}}">Trip Details</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group ">
                          <label for="type" class="col-form-label">Banner</label>
                          <input type="text" placeholder="Trip Number" id="tripNumber" class="form-control" name="tripNumber" value="{{ $item->banner->title_bangla }}" readonly>
                        </div>

                        <div class="form-group">
                          <label for="tripNumber">Trip Number</label>
                          <input type="text" placeholder="Trip Number" id="tripNumber" class="form-control" value="{{ $item->trip_number }}" readonly>
                        </div>

                        <div class="form-group">
                          <label for="departurePoint">Departure Point</label>
                          <input type="text" placeholder="Departure Point" id="departurePoint" class="form-control" value="{{ $item->departurePoint->title }}" readonly>
                        </div>

                        <div class="form-group">
                          <label for="relatedDepartureCounter">Related Departure Counter</label>
                          <input type="text" placeholder="Related Departure Counter" id="relatedDepartureCounter" class="form-control" value="{{ $item->relatedDepartureCounter->title }}" readonly>
                        </div>

                        <div class="form-group">
                          <label for="destination_point">Destination Point</label>
                          <input type="text" placeholder="Related Departure Counter" id="destination_point" class="form-control" value="{{ $item->destinationPoint->title }}" readonly>
                        </div>

                        <div class="form-group">
                          <label for="relatedDestinationCounter">Related Destination Counter</label>
                          <input type="text" placeholder="Related Destination Counter" id="relatedDestinationCounter" class="form-control" value="{{ $item->relatedDestinationCounter->title }}" readonly>
                        </div>

                        <div class="form-group">
                          <label for="departureTime">Departure Time</label>
                          <input type="time" placeholder="Departure Time" id="departureTime" class="form-control" value="{{ $item->departure_time }}" readonly>
                        </div>

                        <div class="form-group">
                          <label for="destinationTime">Destination Time</label>
                          <input type="time" placeholder="Destination Time" id="destinationTime" class="form-control" value="{{ $item->destination_time }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="destinationTime">Seat Access</label>
                            <input type="text" placeholder="Destination Time" class="form-control" value="@if($item->seat_access==1) Counter User @elseif($item->seat_access==2)App User @elseif($item->seat_access==3) All User @endif" readonly>
                        </div>
                      </div>

                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Modal View -->

                <!-- Modal Edit -->
                <div class="modal fade" id="edit{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="edit{{$item->id}}" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="edit{{$item->id}}">User Edit</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form action="{{ url('trip/update', $item->token) }}" method="POST">
                        <div class="modal-body">
                          @csrf

                          <div class="form-group ">
                            <label for="type" class="col-form-label">Banner Token</label>
                            <select id="type" class="form-control bus-custom-select" name="bannerToken" required>
                              <option selected>Select Departure Point</option>
                              @foreach($bannerList as $index => $value)
                                <option value='{{ $value->token }}' {{ $value->token == $item->banner_token ? 'selected' : '' }}>{{ $value->title }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="form-group ">
                            <label for="type" class="col-form-label">Bust Name</label>
                            <select id="type" class="form-control bus-custom-select" name="busToken" required>
                              <option selected>Select Departure Point</option>
                              @foreach($bus_list as $index => $bus)
                                <option value='{{ $bus->token }}' {{ $bus->token == $item->bus_token ? 'selected' : '' }}>{{ $bus->brand }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="tripNumber">Trip Number</label>
                            <input type="text" placeholder="Trip Number" id="tripNumber" class="form-control" name="tripNumber" value="{{ $item->trip_number }}" required>
                          </div>

                          <div class="form-group">
                            <label for="departurePoint">Departure Point</label>
                            <select class="form-control bus-custom-select" id="departurePoint" name="departurePoint">
                              <option selected>Select Departure Point</option>
                                 @foreach($placeList as $index => $value)
                                    <option value='{{ $value->token }}' {{ $value->token == $item->departure_point ? 'selected' : '' }}>{{ $value->title }}</option>
                                 @endforeach
                            </select>
                          </div>

                          <div class="form-group">
                            <label for="relatedDepartureCounter">Related Departure Counter</label>
                            <select class="form-control bus-custom-select" id="relatedDepartureCounter" name="relatedDepartureCounter" >
                              <option selected>Select Related Departure Counter</option>
                                 @foreach($counterList as $index => $value)
                                    <option value='{{ $value->token }}' {{ $value->token == $item->related_departure_counter ? 'selected' : '' }}>{{ $value->title }}</option>
                                 @endforeach
                            </select>
                          </div>

                          <div class="form-group">
                            <label for="destinationPoint">Destination Point</label>
                            <select class="form-control bus-custom-select" id="destinationPoint" name="destinationPoint">
                              <option value='1' selected>Select Related Destination Point</option>
                                 @foreach($placeList as $index => $value)
                                    <option value='{{ $value->token }}' {{ $value->token == $item->destination_point ? 'selected' : '' }}>{{ $value->title }}</option>
                                 @endforeach
                            </select>
                          </div>

                          <div class="form-group">
                            <label for="relatedDestinationCounter">Related Destination Counter</label>
                            <select class="form-control bus-custom-select" id="relatedDestinationCounter" name="relatedDestinationCounter">
                              <option selected>Select Related Destination Counter</option>
                              @foreach($counterList as $index => $value)
                                <option value='{{ $value->token }}' {{ $value->token == $item->related_destination_counter ? 'selected' : '' }}>{{ $value->title }}</option>
                              @endforeach
                            </select>
                          </div>

                          <div class="form-group">
                            <label for="departureTime">Departure Time</label>
                            <input type="time" placeholder="Departure Time" id="departureTime" class="form-control" name="departureTime" value="{{ $item->departure_time }}" required>
                          </div>

                          <div class="form-group">
                            <label for="destinationTime">Destination Time</label>
                            <input type="time" placeholder="Destination Time" id="destinationTime" class="form-control" name="destinationTime" value="{{ $item->destination_time }}" required>
                          </div>
                          <div class="form-group">
                            <label for="destinationTime">Seat Access</label>
                            <select class="form-control seat-access" name="seatAccess" required>
                                <option value="" selected disabled>Select One</option>
                                <option value="1" {{ $item->seat_access==1 ? 'selected' : '' }}>Counter User</option>
                                <option value="2" {{ $item->seat_access==2 ? 'selected' : '' }}>App User</option>
                                <option value="3" {{ $item->seat_access==3 ? 'selected' : '' }}>All User</option>
                            </select>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-info waves-effect waves-light ">Update</button>
                        </div>

                    </form>


                    </div>
                  </div>
                </div>
                <!-- Modal View -->


                  @endforeach



                </tbody>
            </table>
        </div>


        </div>  <!-- end card-body -->
      </div>  <!-- end card -->
    </div>  <!-- end col -->
  </div>
</div>

@push('scripts')
<script>
  $(document).ready(function(){

    $('.bus-custom-bannerToken').select2();
    $('.bus-custom-token').select2();
    $('.bus-custom-departurePoint').select2();
    $('.bus-custom-relatedDepartureCounter').select2();
    $('.bus-custom-destinationPoint').select2();
    $('.bus-custom-relatedDestinationCounter').select2();
    $('.seat-access').select2();

    function userDelete(){
      if (confirm("Do!")) {
        txt = "You pressed OK!";
      } else {
        txt = "You pressed Cancel!";
      }
    }
  });

    function getBanner_token(token){
        let banner_token = token.value;
        let _token = "{{ csrf_token() }}";

        $.ajax({
        type:'GET',
        url:"{{ url('trip/new/bus_list/view') }}",
        data:{_token:_token, banner_token:banner_token},
        success:function(data){
            let busList = "";
                $('#bus_token').empty();
                    $.each(data, function(k, v) {

                        busList += `<option value="${v.token}">${v.brand}</option>`;
                                });
                            $('#bus_token').append(busList);

                },
            });
    }
</script>
@endpush
