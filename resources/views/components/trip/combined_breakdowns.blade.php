<div class="container-fluid">

  <div class="row">
    <div class="col-lg-12">
        <div class="card">
          <div class="card-body">

            <h4 class="mb-3 header-title">
              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addTrip">
                Add New Breakdown
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
                  <form action={{ url("trip/breakdown").'/'.$trip->token.'/new/action' }} method="POST">
                    <div class="modal-body">
                      @csrf

                      <input type="hidden" name="tripToken" value="{{ $trip->token }}">
                       <input type="hidden" name="busToken" value="{{ $trip->bus_token }}">
                      <input type="hidden" name="bannerToken" value="{{ $trip->banner_token }}">

                      <div class="form-group">
                        <label for="departurePoint">Departure Point</label>
                        <select class="form-control bus-custom-departurePoint" id="departurePoint" name="departurePoint">
                          <option selected>Select Departure Point</option>
                             @foreach($placeList as $index => $item)
                                <option value='{{ $item->token }}'>{{ $item->title }}</option>
                             @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="relatedDepartureCounter">Related Departure Counter</label>
                        <select class="form-control bus-custom-relatedDepartureCounter" id="relatedDepartureCounter" name="relatedDepartureCounter">
                          <option selected>Select Related Departure Counter</option>
                             @foreach($counterList as $index => $item)
                                <option value='{{ $item->token }}'>{{ $item->title }}</option>
                             @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="destinationPoint">Destination Point</label>
                        <select class="form-control bus-custom-destinationPoint" id="destinationPoint" name="destinationPoint">
                          <option selected>Select Related Destination Point</option>
                             @foreach($placeList as $index => $item)
                                <option value='{{ $item->token }}'>{{ $item->title }}</option>
                             @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="relatedDestinationCounter">Related Destination Counter</label>
                        <select class="form-control bus-custom-relatedDestinationCounter" id="relatedDestinationCounter" name="relatedDestinationCounter">
                          <option selected>Select Related Destination Counter</option>
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
                        <label for="fare">Fare</label>
                        <input type="text" placeholder="Route Fare" id="fare" class="form-control" name="fare" required>
                      </div>

                      <div class="form-group">
                        <label for="onlineCharge">Online Charge</label>
                        <input type="text" placeholder="Online Charge" id="onlineCharge" class="form-control" name="onlineCharge" required>
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

        <h4 class="mb-3 header-title">Breakdowns of Trip Number: <span class="text-success">{{ $trip->trip_number }}</span> & Banner: <span class="text-success">{{ $trip->banner->title_bangla }}</span></h4>
        <div class="form-group">
            <input type="text" id="search" class="form-control" placeholder="Search">
        </div>

        <div class="table-responsive">
            <table  class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                      <th>SL</th>
                      <th>Departure Point</th>
                      <th>Related Departure Counter</th>
                      <th>Destination Point</th>
                      <th>Related Destination Counter</th>
                      <th>Departure Time</th>
                      <th>Destination Time</th>
                      <th>Fare</th>
                      <th>Online Charge</th>
                      <th>Action</th>
                    </tr>
                </thead>

                <tbody id="TableList">
                  <input type="hidden"  value="{{ $list->count() }}" id="count">
                  @foreach($list as $index => $item)
                    <tr>
                        <td>{{ ++$index }}</td>
                        <td>{{ $item->departurePoint->title }}</td>
                        <td>{{ $item->relatedDepartureCounter->title }}</td>
                        <td>{{ $item->destinationPoint->title }}</td>
                        <td>{{ $item->relatedDestinationCounter->title }}</td>
                        <td>{{ date('h:i A', strtotime($item->departure_time)) }}</td>
                        <td>{{ date('h:i A', strtotime($item->destination_time)) }}</td>
                        <td>{{ $item->fare }}</td>
                        <td>{{ $item->online_charge }}</td>
                        <td>
                          <div class="btn-group">
                            <!-- Scrollable modal -->
                            <button  type="button" class="btn btn-info mr-1" data-toggle="modal" data-target="#view{{$item->id}}">View</button>
                            <button  type="button" class="btn btn-warning mr-1" data-toggle="modal" data-target="#edit{{$item->id}}">Edit</button>
                            <!-- Scrollable modal -->
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

                        <div class="form-group">
                          <label >Departure Point</label>
                          <input type="text" placeholder="Departure Point"  class="form-control" value="{{ $item->departurePoint->title }}" readonly>
                        </div>

                        <div class="form-group">
                          <label >Related Departure Counter</label>
                          <input type="text" placeholder="Related Departure Counter" class="form-control" value="{{ $item->relatedDepartureCounter->title }}" readonly>
                        </div>

                        <div class="form-group">
                          <label >Destination Point</label>
                          <input type="text" placeholder="Related Departure Counter"  class="form-control" value="{{ $item->destinationPoint->title }}" readonly>
                        </div>

                        <div class="form-group">
                          <label >Related Destination Counter</label>
                          <input type="text" placeholder="Related Destination Counter"  class="form-control" value="{{ $item->relatedDestinationCounter->title }}" readonly>
                        </div>

                        <div class="form-group">
                          <label >Departure Time</label>
                          <input type="time" placeholder="Departure Time"  class="form-control" value="{{ $item->departure_time }}" readonly>
                        </div>

                        <div class="form-group">
                          <label>Destination Time</label>
                          <input type="time" placeholder="Destination Time"  class="form-control" value="{{ $item->destination_time }}" readonly>
                        </div>

                        <div class="form-group">
                          <label >Fare</label>
                          <input type="text" placeholder="Route Fare"  class="form-control" name="fare" value="{{ $item->fare }}" readonly>
                        </div>

                        <div class="form-group">
                          <label >Online Charge</label>
                          <input type="text" placeholder="Online Charge" class="form-control" name="onlineCharge" value="{{ $item->online_charge }}" readonly>
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
                        <h5 class="modal-title" id="edit{{$item->id}}">Breakdown Edit</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form action="{{ url("trip/breakdown").'/'.$item->token.'/edit/action' }}" method="POST">
                        <div class="modal-body">
                          @csrf


                          <div class="form-group">
                            <label >Departure Point</label>
                            <select class="form-control bus-custom-departurePoint" name="departurePoint" required>
                              <option value="" selected>Select Departure Point</option>
                                 @foreach($placeList as $index => $value)
                                    <option value='{{ $value->token }}' {{ $value->token == $item->departure_point ? 'selected' : '' }}>{{ $value->title }}</option>
                                 @endforeach
                            </select>
                          </div>

                          <div class="form-group">
                            <label >Related Departure Counter</label>
                            <select class="form-control bus-custom-relatedDepartureCounter"  name="relatedDepartureCounter" required>
                              <option value="" selected>Select Related Departure Counter</option>
                                 @foreach($counterList as $index => $value)
                                    <option value='{{ $value->token }}' {{ $value->token == $item->related_departure_counter ? 'selected' : '' }}>{{ $value->title }}</option>
                                 @endforeach
                            </select>
                          </div>

                          <div class="form-group">
                            <label >Destination Point</label>
                            <select class="form-control bus-custom-destinationPoint"  name="destinationPoint" required>
                              <option value="" selected>Select Related Destination Point</option>
                                 @foreach($placeList as $index => $value)
                                    <option value='{{ $value->token }}' {{ $value->token == $item->destination_point ? 'selected' : '' }}>{{ $value->title }}</option>
                                 @endforeach
                            </select>
                          </div>

                          <div class="form-group">
                            <label >Related Destination Counter</label>
                            <select class="form-control bus-custom-relatedDestinationCounter"  name="relatedDestinationCounter" required>
                              <option value="" selected>Select Related Destination Counter</option>
                              @foreach($counterList as $index => $value)
                                <option value='{{ $value->token }}' {{ $value->token == $item->related_destination_counter ? 'selected' : '' }}>{{ $value->title }}</option>
                              @endforeach
                            </select>
                          </div>

                          <div class="form-group">
                            <label >Departure Time</label>
                            <input type="time" placeholder="Departure Time"  class="form-control" name="departureTime" value="{{ $item->departure_time }}" required>
                          </div>

                          <div class="form-group">
                            <label >Destination Time</label>
                            <input type="time" placeholder="Destination Time"   class="form-control" name="destinationTime" value="{{ $item->destination_time }}" required>
                          </div>

                          <div class="form-group">
                            <label >Fare</label>
                            <input type="text" placeholder="Route Fare"   class="form-control" name="fare" value="{{ $item->fare }}" required>
                          </div>

                          <div class="form-group">
                            <label >Online Charge</label>
                            <input type="text" placeholder="Online Charge"   class="form-control" name="onlineCharge" value="{{ $item->online_charge }}" required>
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
    $('.bus-custom-departurePoint').select2();
    $('.bus-custom-relatedDepartureCounter').select2();
    $('.bus-custom-destinationPoint').select2();
    $('.bus-custom-relatedDestinationCounter').select2();

    let count = $("#count").val();
    let i;



    function userDelete(){
      if (confirm("Do!")) {
        txt = "You pressed OK!";
      } else {
        txt = "You pressed Cancel!";
      }
    }

  });
</script>
@endpush
