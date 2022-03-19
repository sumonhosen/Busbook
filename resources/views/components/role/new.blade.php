<div class="container-fluid">
   
  <div class="row">
    <div class="col-lg-12">
        <div class="card">
          <div class="card-body">

            <h4 class="mb-3 header-title">
              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addUser">
                Add Role Group
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
                  <form action="{{ url('role/new/action') }}" method="POST">
                    <div class="modal-body">                      
                      @csrf

                      <div class="form-group">
                        <label for="name">Title</label>
                        <input type="text"  class="form-control" id="name" name="title" placeholder="Title" required>
                      </div>

                      <div class="form-group">
                        <label for="phone">Remark</label>
                        <input type="text"  class="form-control" id="phone" name="remark" placeholder="Remark" required>
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
                      <th>Title</th>
                      <th>Remark</th>
                                                   
                    </tr>
                </thead>            
            
                <tbody id="TableList">
                  @foreach ($list as $index=>$item)
                  <tr>
                    <td>{{ ++$index }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->remark }}</td>
                    <td>
                        <!-- Scrollable modal -->
                        <a href="{{ url('role/view', $item->token) }}" class="btn btn-info mr-1" >View</button>
                        
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
    
   



  });
</script>
@endpush