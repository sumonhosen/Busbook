<div class="container-fluid">

  <div class="row">
    <div class="col-lg-12">
        <div class="card">
          <div class="card-body">

            <h4 class="mb-3 header-title">
              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCounter">
                Add Counter
              </button>
            </h4>

            <!-- Modal -->
            <div class="modal fade" id="addCounter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Counter</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="{{ url('counter/new/action') }}" method="POST">
                    <div class="modal-body">
                      @csrf
                      <div class="form-group">
                        <label for="registrationNumber">Banner</label>
                        <select class="form-control bus-custom-select" name="banner_token" required>
                            <option value="" selected disabled>Select</option>
                            @foreach($banner as $index=>$item)
                            <option value="{{ $item->token }}"> {{$item->title_bangla}}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Title" required>
                      </div>

                      <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
                      </div>

                      <div class="form-group">
                        <label for="note">Note</label>
                        <input type="text" class="form-control" id="note" name="note" placeholder="Note" required>
                      </div>

                      <div class="form-group ">
                        <label  class="col-form-label">Type</label>
                        <select class="form-control bus-custom-select" name="type" required>

                          <option value="Storefront" selected>Counter</option>
                          <option value="Bus">Supervisor</option>
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

        <h4 class="mb-3 header-title">Place List</h4>
        <div class="form-group">
            <input type="text" id="search" class="form-control" placeholder="Search">
        </div>

        <div class="table-responsive">
            <table  class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                      <th>SL</th>
                      <th>Banner</th>
                      <th>Title</th>
                      <th>Address</th>
                      <th>Note</th>
                      <th>Type</th>
                      <th>Action</th>
                    </tr>
                </thead>

                <tbody id="TableList">
                  @foreach ($list as $index=>$item)
                  <tr>
                    <td>{{ ++$index }}</td>
                    <td>{{ $item->banner->title_bangla }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->address }}</td>
                    <td>{{ $item->note }}</td>
                    <td>
                      @if($item->type == 'Storefront')
						Counter
						@elseif($item->type == 'Bus')
						Supervisor
					  @endif
						
                    </td>

                    <td>
                      <div class="btn-group">
                        <!-- Scrollable modal -->
                        <button  type="button" class="btn btn-info mr-1" data-toggle="modal" data-target="#view{{$item->id}}">View</button>
                        <!-- Scrollable modal -->
                        <button  type="button" class="btn btn-warning mr-1" data-toggle="modal" data-target="#edit{{$item->id}}">Edit</button>
                        <form action="{{ url('counter/delete', $item->token) }}" method="POST" style="display:inline; margin:0px; padding:0px">
                          @csrf
                          <button type="submit" class="btn btn-danger mr-1" onclick="if (!confirm('Are you want to delete this Counter?')) { return false }">Delete</button>
                        </form>
                        <a href="{{ url('counter/trip/relation/new/view', $item->token) }}" class="btn btn-success mr-1">Trips</a>
                        <a href="{{ url('counter/user/relation/new/view', $item->token) }}" class="btn btn-primary">Users</a>
                      </div>
                    </td>
                  </tr>

                <!-- Start Modal View -->
                <div class="modal fade" id="view{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="view{{$item->id}}" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="view{{$item->id}}">Counter Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <label for="title">Banner</label>
                          <input type="text" class="form-control" id="title" name="title" value="{{$item->banner->title}}" placeholder="Title" readonly>
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{$item->title}}" placeholder="Title" readonly>
                          </div>

                        <div class="form-group">
                          <label for="address">Address</label>
                          <input type="text" class="form-control" id="address" name="address" value="{{$item->address}}" placeholder="Address" readonly>
                        </div>

                        <div class="form-group">
                          <label for="note">Note</label>
                          <input type="text" class="form-control" id="note" name="note" value="{{$item->note}}" placeholder="Note" readonly>
                        </div>

                        <div class="form-group ">
                          <label  class="col-form-label">Type</label>
                          <select  class="form-control bus-custom-select" name="type" readonly>
                            <option value="">Select Type</option>
                            <option {{ $item->type == 'Storefront' ? 'selected':'' }} value="Storefront">Counter</option>
                            <option {{ $item->type == 'Bus' ? 'selected':'' }} value="Bus">Supervisor</option>
                          </select>
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
                        <h5 class="modal-title" id="edit{{$item->id}}">Counter Edit</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form action="{{ url('counter/update', $item->token) }}" method="POST">
                        <div class="modal-body">
                          @csrf
                          <div class="modal-body">
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
                              <label for="title">Title</label>
                              <input type="text" class="form-control" id="title" name="title" value="{{$item->title}}" placeholder="Title" required>
                            </div>
                            <div class="form-group">
                              <label for="address">Address</label>
                              <input type="text" class="form-control" id="address" name="address" value="{{$item->address}}" placeholder="Address" required>
                            </div>
                            <div class="form-group">
                              <label for="note">Note</label>
                              <input type="text" class="form-control" id="note" name="note" value="{{$item->note}}" placeholder="Note" required>
                            </div>
                            <div class="form-group ">
                                <label  class="col-form-label">Type</label>
                                <select  class="form-control bus-custom-select" name="type" required>
                                    <option value="">Select Type</option>
                                    <option {{ $item->type == 'Storefront' ? 'selected':'' }} value="Storefront">Counter</option>
                                    <option {{ $item->type == 'Bus' ? 'selected':'' }} value="Bus">Supervisor</option>
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
