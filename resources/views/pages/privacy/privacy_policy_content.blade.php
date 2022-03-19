@extends('layouts.master')   

@section('title', 'Privacy Policy Content')

@section('content')
<div class="container-fluid">
   
  <div class="row">
    <div class="col-lg-12">
        <div class="card">
          <div class="card-body">

            <h4 class="mb-3 header-title">
              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addPrivacyPolicy">
                Privacy Policy Content
              </button>
            </h4>

            <!-- Modal -->
            <div class="modal fade" id="addPrivacyPolicy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Privacy Policy Content</h5>
                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="{{ url('role/privacy/policy/content/action') }}" method="POST">
                    <div class="modal-body">                      
                      @csrf

                      <div class="form-group">
                        <label for="title">Privacy Policy</label>
                        <select class="form-control" name="privacy_policy_token" required>
                          <option value="" selected disabled>Select Privacy Policy</option>
                          @foreach($policies as $policy)
                          <option value="{{ $policy->token }}">{{ $policy->privacy_policy }}</option>
                          @endforeach
                        </select>
                      </div>
                       <div class="form-group">
                        <label for="title">Privacy Policy Content</label>
                        <textarea class="form-control" cols="50" rows="5" name="privacy_policy_content" required></textarea>
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