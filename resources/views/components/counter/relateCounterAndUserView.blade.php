<div class="container-fluid">
   
  <div class="row">
    <div class="col-lg-12">
        <div class="card">
          <div class="card-body">

            <h4 class="mb-3 text-primary">Counter And User Related List of Counter: {{ $counter->title }}</h4>

            <h4 class="mb-3 header-title">


              <form action="{{ url('counter/user/relation/new/action') }}" method="post">
                @csrf

                <input type="hidden" name="role_token" value="Counter Manager" required>
                <input type="hidden" name="counter_token" value="{{ $counter->token }}" required>

                <div class="form-group">
                  <label for="user_token">Select A User</label>

                  <select class="form-control bus-custom-select" id="user_token" name="user_token" data-toggle="select2" required>
                    <option value="">Select A User</option>
                    @foreach ($userList as $item)
                      <option value="{{ $item->token }}">{{ $item->name }} - {{ $item->phone }}</option>
                    @endforeach
                  </select>


                </div>

                <div class="form-group">
                  <button type="submit" class="btn btn-primary"> Submit</button>
                </div>

              </form>

            </h4>

           

        @if(session('status'))
          <div class="alert alert-success" role="alert">
              {{session('status')}}
          </div>                    
        @endif

        <h4 class="mb-3 header-title">Counter And User Related List</h4>
        <div class="form-group">
            <input type="text" id="search" class="form-control" placeholder="Search">          
        </div>
        
        <div class="table-responsive">
            <table  class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                      <th>SL</th>
                      <th>User Name</th>
                      <th>Action</th>                              
                    </tr>
                </thead>            
            
                <tbody id="TableList">
                  @foreach ($list as $index=>$item)
                    <tr>
                      <td>{{ ++$index }}</td>
                      <td>{{ $item->user->name }}</td>
                      <td>
                        <form action="{{ url('counter/user/relation/delete', $item->token) }}" method="POST" style="display:inline; margin:0px; padding:0px">
                          @csrf
                          <button type="submit" class="btn btn-danger" onclick="if (!confirm('Do you want to delete this User Relation?')) { return false }">Delete</button>
                        </form>
                      </td>
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