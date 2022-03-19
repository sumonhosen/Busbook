<div class="container-fluid">
   
  <div class="row">
    <div class="col-lg-12">
        <div class="card">
          <div class="card-body">

            <h4 class="mb-3 header-title">
              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addBanner">
                Add Banner
              </button>
            </h4>

            <!-- Modal -->
            <div class="modal fade" id="addBanner" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Banner</h5>
                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="{{ url('banner/new/action') }}" method="POST">
                    <div class="modal-body">                      
                      @csrf

                      <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text"  class="form-control" id="title" name="title" placeholder="Title" required>
                      </div>

                      <div class="form-group">
                        <label for="titleBangla">Title Bangla</label>
                        <input type="text"  class="form-control" id="titleBangla" name="titleBangla" placeholder="Title Bangla" required>
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

        <h4 class="mb-3 header-title">Banner List</h4>
        <div class="form-group">
            <input type="text" id="search" class="form-control" placeholder="Search">          
        </div>
        
        <div class="table-responsive">
            <table  class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                      <th>SL</th>
                      <th>Title</th>
                      <th>Title Bangla</th>
                      <th>Action</th>                              
                    </tr>
                </thead>            
            
                <tbody id="TableList">
                  @foreach ($list as $index=>$item)
                  <tr>
                    <td>{{ ++$index }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->title_bangla }}</td>
                    <td>
                      <div class="btn-group">
                        <!-- Scrollable modal -->
                        <button  type="button" class="btn btn-info mr-1" data-toggle="modal" data-target="#view{{$item->id}}">View</button>
                        <!-- Scrollable modal -->
                        <button  type="button" class="btn btn-warning mr-1" data-toggle="modal" data-target="#edit{{$item->id}}">Edit</button>                        
                        <form action="{{ url('banner/delete', $item->token) }}" method="POST" style="display:inline; margin:0px; padding:0px">
                          @csrf
                          <button type="submit" class="btn btn-danger" onclick="if (!confirm('Are you want to delete this Banner?')) { return false }">Delete</button>
                        </form>
                      </div>
                    </td>                  
                  </tr>                  

                <!-- Start Modal View -->
                <div class="modal fade" id="view{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="view{{$item->id}}" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="view{{$item->id}}">Banner Details</h5>                        
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>                      
                      <div class="modal-body">                        
                        <div class="form-group">
                          <label for="title">Title</label>
                          <input type="text" class="form-control" id="title" name="title" value={{$item->title}}  placeholder="Title" readonly>
                        </div>

                        <div class="form-group">
                          <label for="titleBangla">Title Bangla</label>
                          <textarea type="text" class="form-control" id="titleBangla" name="titleBangla" rows="8" placeholder="Details" readonly>{{$item->title_bangla}}</textarea>
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
                        <h5 class="modal-title" id="edit{{$item->id}}">Banner Edit</h5>
                        
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div> 
                      <form action="{{ url('banner/update', $item->token) }}" method="POST">                     
                        <div class="modal-body">
                          @csrf

                          <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value={{$item->title}} placeholder="Title" required>
                          </div>

                          <div class="form-group">
                            <label for="titleBangla">Title Bangla</label>
                            <textarea type="text" class="form-control" id="titleBangla" name="titleBangla" rows="8" placeholder="Details" required>{{$item->title_bangla}}</textarea>
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
    
   



  });
</script>
@endpush