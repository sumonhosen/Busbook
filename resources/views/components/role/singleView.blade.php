<div class="container-fluid">
   
  <div class="row">
    <div class="col-lg-12">
        <div class="card">
          <div class="card-body">

            <h4 class="mb-3 header-title">
              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addUser">
                Add Role 
              </button>
            </h4>

            <!-- Modal -->
            <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Role</h5>
                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="{{ url('role/single/view/action') }}" method="POST">
                    <div class="modal-body">                      
                      @csrf

                      <input type="hidden" name="role_group_token" value="{{ $roleToken }}" required>

                      <div class="form-group">
                        <label for="token">Token</label>
                        <input type="text"  class="form-control @error('token') is-invalid @enderror" id="token" name="token" placeholder="Token" required>
                        @error('token')
                          <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="form-group">
                        <label for="name">Title</label>
                        <input type="text"  class="form-control" id="name" name="title" value="{{ old('title') }}" placeholder="Title" required>
                      </div>

                      <div class="form-group">
                        <label for="details">Details</label>
                        <input type="text"  class="form-control" id="details" name="details" value="{{ old('details') }}" placeholder="Details" required>
                      </div>

                      <div class="form-group">
                        <label for="crud_type">Crud Type</label>
                        <select class="form-control bus-custom-select" name="crud_type"  required>
                          <option value="">Select Crud Type</option>  
                          <option value="Create" {{ old('crud_type') == 'Create' ? 'selected':'' }}>Create</option>  
                          <option value="Read" {{ old('crud_type') == 'Read' ? 'selected':'' }}>Read</option>  
                          <option value="Update" {{ old('crud_type') == 'Update' ? 'selected':'' }}>Update</option>  
                          <option value="Delete" {{ old('crud_type') == 'Delete' ? 'selected':'' }}>Delete</option>  
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="entity">Entity</label>
                        <select class="form-control bus-custom-select" name="entity" required>
                          <option value="">Select Crud Type</option>  
                          <option value="Business" {{ old('entity') == 'Business' ? 'selected':'' }}>Business</option>  
                          <option value="Bus" {{ old('entity') == 'Bus' ? 'selected':'' }}>Bus</option>  
                          <option value="Counter" {{ old('entity') == 'Counter' ? 'selected':'' }}>Counter</option>  
                          <option value="Ticket" {{ old('entity') == 'Ticket' ? 'selected':'' }}>Ticket</option>  
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="Permission">Permission</label>
                        <select class="form-control bus-custom-select" name="permission" required>
                          <option value="">Select Crud Type</option>  
                          <option value="Regular" {{ old('permission') == 'Regular' ? 'selected':'' }}>Regular</option>  
                          <option value="Wildcard" {{ old('permission') == 'Wildcard' ? 'selected':'' }}>Wildcard</option>  
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="remark">Remark</label>
                        <input type="text"  class="form-control" id="remark" name="remark" value="{{ old('remark') }}" placeholder="Remark" required>
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

        <h4 class="mb-3 header-title">Role Group List</h4>
        <div class="form-group">
            <input type="text" id="search" class="form-control" placeholder="Search">          
        </div>
        
        <div class="table-responsive">
            <table  class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                      <th>SL</th>
                      <th>Title</th>
                      <th>Details</th>
                      <th>Crud Type</th>
                      <th>Entity</th>
                      <th>Permission</th>
                      <th>Remark</th>
                                                   
                    </tr>
                </thead>            
            
                <tbody id="TableList">
                  @foreach ($list as $index=>$item)
                    <tr>
                      <td>{{ ++$index }}</td>
                      <td>{{ $item->title }}</td>
                      <td>{{ $item->details }}</td>
                      <td>{{ $item->crud_type }}</td>
                      <td>{{ $item->entity }}</td>
                      <td>{{ $item->permission }}</td>
                      <td>{{ $item->remark }}</td>
                    </tr>
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