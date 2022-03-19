<div class="container-fluid">

  <div class="row">
    <div class="col-lg-12">
        <div class="card">
          <div class="card-body">

            <h4 class="mb-3 header-title">
              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addBus">
                Add Bus
              </button>
            </h4>

            <!-- Modal -->
            <div class="modal fade" id="addBus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Bus</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="{{ url('bus/new/action') }}" method="POST">
                    <div class="modal-body">
                      @csrf
                      <div class="form-group">
                        <label for="model">Banner</label>
                            <select class="form-control bus-custom-select" name="banner_token" required>
                                <option value="" selected disabled>Select</option>
                                @foreach($banner as $index=>$item)
                                <option value="{{ $item->token }}"> {{$item->title}}</option>
                                @endforeach
                            </select>
                      </div>
                      <div class="form-group">
                        <label for="registrationNumber">Registration Number</label>
                        <input type="text"  class="form-control" id="registrationNumber" name="registrationNumber" placeholder="Title" required>
                      </div>

                      <div class="form-group">
                        <label for="brand">Brand</label>
                        <input type="text" class="form-control" id="brand" name="brand" placeholder="Brand" required>
                      </div>

                      <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" class="form-control" id="model" name="model" placeholder="Model" required>
                      </div>
                      <div class="form-group">
                        <label for="model">Number of Seat</label>
                            <select class="form-control bus-custom-select" name="num_of_seat" required>
                                <option value="" selected disabled>Select</option>
								@foreach($seat_list as $seat)
								  @php
								   $total_seats = json_decode($seat->title,true);
									$total_seat_count = 0;
										foreach($total_seats as $total){
											foreach($total as $i){
												if($i['name']){
													$total_seat_count++;
												}
											}
										}
								  @endphp
                                <option value="{{ $seat->id }}">{{ $total_seat_count }}</option>
								@endforeach
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

        <h4 class="mb-3 header-title">Bus List</h4>
        <div class="form-group">
            <input type="text" id="search" class="form-control" placeholder="Search">
        </div>

        <div class="table-responsive">
            <table  class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                      <th>SL</th>
                      <th>Banner</th>
                      <th>Registration Number</th>
                      <th>Brand</th>
                      <th>Model</th>
                      <th>Number Of Seat</th>
                      <th>Action</th>
                    </tr>
                </thead>

                <tbody id="TableList">
                  @foreach ($list as $index=>$item)
                  <tr>
                    <td>{{ ++$index }}</td>
                    <td>{{ $item->banner->title }}</td>
                    <td>{{ $item->registration_number }}</td>
                    <td>{{ $item->brand }}</td>
                    <td>{{ $item->model }}</td>
					  @php
					   $total_seats = json_decode($item->seat->title,true);
						$total_seat_count = 0;
							foreach($total_seats as $total){
								foreach($total as $i){
									if($i['name']){
										$total_seat_count++;
									}
								}
							}
					  @endphp
                    <td>{{ $total_seat_count }}</td>

                    <td>
                      <div class="btn-group">
                        <!-- Scrollable modal -->
                        <button  type="button" class="btn btn-info mr-1" data-toggle="modal" data-target="#view{{$item->id}}">View</button>
                        <!-- Scrollable modal -->
                        <button  type="button" class="btn btn-warning mr-1" data-toggle="modal" data-target="#edit{{$item->id}}">Edit</button>
                        <form action="{{ url('bus/delete', $item->token) }}" method="POST" style="display:inline; margin:0px; padding:0px">
                          @csrf
                          <button type="submit" class="btn btn-danger" onclick="if (!confirm('Are you want to delete this Bus?')) { return false }">Delete</button>
                        </form>
                      </div>
                    </td>
                  </tr>

                <!-- Start Modal View -->
                <div class="modal fade" id="view{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="view{{$item->id}}" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="view{{$item->id}}">Bus Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">

                        <div class="form-group">
                          <label for="banner">Banner</label>
                          <input type="text"  class="form-control" id="banner" value="{{$item->banner->title}}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="number_of_seat">Number of Seat</label>
                            <input type="text"  class="form-control" id="number_of_seat" value=""  readonly>
                        </div>
                        <div class="form-group">
                            <label for="registrationNumber">Registration Number</label>
                            <input type="text"  class="form-control" id="registrationNumber" value="{{$item->registration_number}}"  readonly>
                        </div>
                        <div class="form-group">
                          <label for="brand">Brand</label>
                          <input type="text" class="form-control" id="brand" value="{{$item->brand}}" readonly>
                        </div>

                        <div class="form-group">
                          <label for="model">Model</label>
                          <input type="text" class="form-control" id="model" value="{{$item->model}}" readonly>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End Modal View -->

                <!-- Modal Edit -->
                <div class="modal fade" id="edit{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="edit{{$item->id}}" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="edit{{$item->id}}">Bus Edit</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form action="{{ url('bus/update', $item->token) }}" method="POST">
                        <div class="modal-body">
                          @csrf
                          <div class="form-group">
                            <label for="registrationNumber">Banner</label>
                            <select class="form-control bus-custom-select" name="banner_token" required>
                                <option value="" selected disabled>Select</option>
                                @foreach($banner as $index=>$ban_item)

                                <option value="{{ $ban_item->token }}" {{  $ban_item->token==$item->banner_token? 'selected' : '' }} > {{$ban_item->title}}</option>
                                @endforeach
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="registrationNumber">Registration Number</label>
                            <input type="text"  class="form-control" id="registrationNumber" name="registrationNumber" value="{{$item->registration_number}}" placeholder="Registration Number" required>
                          </div>

                          <div class="form-group">
                            <label for="brand">Brand</label>
                            <input type="text" class="form-control" id="brand" name="brand" value="{{$item->brand}}" placeholder="Brand" required>
                          </div>

                          <div class="form-group">
                            <label for="model">Model</label>
                            <input type="text" class="form-control" id="model" name="model" value="{{$item->model}}" placeholder="Model" required>
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
                <!-- Modal Edit -->

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
    $('.bus-custom-select').select2();
  });
</script>
@endpush
