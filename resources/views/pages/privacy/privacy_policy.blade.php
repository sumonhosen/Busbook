@extends('layouts.master')   

@section('title', 'Privacy Policy')

@section('content')
<div class="container-fluid">
   
  <div class="row">
    <div class="col-lg-12">
        <div class="card">
          <div class="card-body">

            <h4 class="mb-3 header-title">
              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addPrivacyPolicy">
                Privacy Policy
              </button>
            </h4>

            <!-- Modal -->
            <div class="modal fade" id="addPrivacyPolicy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Privacy Policy</h5>
                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="{{ url('role/privacy/policy/action') }}" method="POST">
                    <div class="modal-body">                      
                      @csrf

                      <div class="form-group">
                        <label for="title">Privacy Policy</label>
                        <input type="text" class="form-control" id="title" name="privacy_policy" placeholder="Title" required>
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

        <h4 class="mb-3 header-title">Privacy Policy List</h4>
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
                            

                </tbody>
            </table>
        </div>
        

        </div>  <!-- end card-body -->
      </div>  <!-- end card -->
    </div>  <!-- end col -->
  </div>
</div>

 
@endsection