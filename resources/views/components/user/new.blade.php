 <div class="container-fluid">
   
  <div class="row">
    <div class="col-lg-12">
        <div class="card">
          <div class="card-body">

            <h4 class="mb-3 header-title">
              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addUser">
                Add User
              </button>
            </h4>

            <!-- Modal -->
            <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="{{ url('user/new/action') }}" method="POST">
                    <div class="modal-body">                      
                      @csrf

                      <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text"  class="form-control" id="name" name="name" placeholder="Name" required>
                      </div>

                      <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text"  class="form-control" id="phone" name="phone" placeholder="Phone" required>
                      </div>

                      <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email"  class="form-control" id="email" name="email" placeholder="Email" required>
                      </div>

                      <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password"  class="form-control" id="password" name="password" placeholder="Password" required>
                      </div>

                      <div class="form-group">
                        <label for="user_type">User Type</label>
                        <select class="form-control bus-custom-select" name="type"  required>
                          <option value="">Select Crud Type</option>  
                          <option value="Office Related" >Office Related</option>  
                          <option value="Bus Related" >Bus Related</option>  
                          <option value="Counter Related" >Counter Related</option>  
                          <option value="App User" >App User</option>  
                        </select>
                      </div>

                      
                      <div class="form-group ">
                        <label for="Address">Address</label>
                        <textarea class="form-control" id="Address" name="address" rows="5" required></textarea>
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

        <h4 class="mb-3 header-title">User List</h4>
        <div class="form-group">
            <input type="text" id="search" class="form-control" placeholder="Search">          
        </div>
        
        <div class="table-responsive">
            <table  class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                      <th>SL</th>
                      <th>Name</th>
                      <th>Phone</th>
                      <th>Email</th>
                      <th>Type</th>
                      <th>Address</th>
                      <th>Action</th>                              
                    </tr>
                </thead>            
            
                <tbody id="TableList">
                  @foreach ($list as $index=>$item)
                    <tr>
                      <td>{{ ++$index }}</td>
                      <td>{{ $item->name }}</td>
                      <td>{{ $item->phone }}</td>
                      <td>{{ $item->email }}</td>
                      <td>{{ $item->type }}</td>
                      <td>{!! $item->address !!}</td>
                      <td>
                        <div class="btn-group">
                          <!-- Scrollable modal -->
                          <button  type="button" class="btn btn-info mr-1" data-toggle="modal" data-target="#view{{$item->id}}">View</button>
                          <!-- Scrollable modal -->
                          <button  type="button" class="btn btn-warning mr-1" data-toggle="modal" data-target="#edit{{$item->id}}">Edit</button>                        
                          <form action="{{ url('user/delete', $item->token) }}" method="POST" style="display:inline; margin:0px; padding:0px">
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="if (!confirm('Are you want to delete this user?')) { return false }">Delete</button>
                          </form>
                        </div>
                      </td>                  
                    </tr>
                  

                    <!-- Modal View -->
                    <div class="modal fade" id="view{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="view{{$item->id}}" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="view{{$item->id}}">User Details</h5>
                            
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>                      
                          <div class="modal-body">
                            
                            <div class="form-group">
                              <label for="name">Name</label>
                              <input type="text"  class="form-control" name="name" value={{$item->name}} readonly>
                            </div>

                            <div class="form-group">
                              <label for="phone">Phone</label>
                              <input type="text"  class="form-control" name="phone" value={{$item->phone}} readonly>
                            </div>

                            <div class="form-group">
                              <label for="email">Email</label>
                              <input type="email"  class="form-control" name="email" value={{$item->email}} readonly>
                            </div>

                            <div class="form-group ">
                              <label for="Address">Address</label>
                              <textarea class="form-control" name="address" rows="5" readonly>{{ $item->address }}</textarea>
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
                          <form action="{{ url('user/update', $item->token) }}" method="POST">                     
                            <div class="modal-body">
                              @csrf

                              <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control"  name="name" value="{{$item->name}}" placeholder="Name" required>
                              </div>

                              <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control"  name="email" value="{{$item->email}}" placeholder="Email" required>
                              </div>

                              <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="phone" class="form-control"  name="phone" value="{{$item->phone}}" placeholder="Phone" required>
                              </div>

                              <div class="form-group ">
                                <label for="Address">Address</label>
                                <textarea class="form-control" name="address" rows="5" required>{!! $item->address !!}</textarea>
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

    $('.bus-custom-select').select2();
    
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