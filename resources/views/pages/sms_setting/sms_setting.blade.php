@extends('layouts.master')   

@section('title', 'Sms Setting')

@section('content')
<style type="text/css">
	.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
    <div class="container-fluid"> 
	  <div class="row">
	    <div class="col-lg-12">
	        <div class="card">
	        	@if(Session::has('success'))
			       <button class="btn btn-success">{{ Session::get('status' ) }}</button>
			       @endif
	          	<div class="card-body">
                <form action="{{ url('is_admin_sms') }}" method="POST">
                  <div class="modal-body">                      
                    @csrf
                    <input type="hidden" name="sms_id" id="sms_id" value="{{ $sms->id }}">
                      <div class="row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4 text-center">
                         <h4 for="title">Admin Counter Sms</h4>
                          <label class="switch">
                            <input type="checkbox" id="admin_counter_sms" value="{{ $sms->is_admin_counter }}" @if($sms->is_admin_counter==1) checked @endif>
                            <span class="slider round"></span>
                          </label>
                          <div id="admin_counter_phone">
                            
                          </div>
                        </div>
                        <div class="col-sm-4 text-center">
                         <h4 for="title">Admin App User Sms</h4>
                           <label class="switch">
                            <input type="checkbox" id="admin_app_sms" value="{{ $sms->is_admin_app }}" @if($sms->is_admin_app==1) checked @endif>
                            <span class="slider round"></span>
                          </label>
                           <div id="admin_app_phone">
                            
                          </div>
                        </div>
                      </div>
                       <div class="row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4 text-center">
                         <h4 for="title">Counter Sms</h4>
                          <label class="switch">
                            <input type="checkbox" id="counter_sms" value="{{ $sms->counter_sms }}" @if($sms->counter_sms==1) checked @endif>
                            <span class="slider round"></span>
                          </label>
       
                        </div>
                        <div class="col-sm-4 text-center">
                         <h4 for="title">App User Sms</h4>
                           <label class="switch">
                            <input type="checkbox" id="app_sms" value="{{ $sms->app_sms }}" @if($sms->app_sms==1) checked @endif>
                            <span class="slider round"></span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="sub_btn text-center">
                      <button class="btn btn-success">Submit</button>
                    </div>
                  </form> 
                </div>
              </div>
            </div>
		      </div>
        </div>

@push('scripts')
<script>

    $('#admin_counter_sms').on('click', function(){
      var sms_id = $('#sms_id').val();
      var admin_counter_sms = $('#admin_counter_sms').val();

      if(admin_counter_sms==1){
          $('#admin_counter_sms').attr('value',0);
          $('#admin_counter_phone').html('');
       }else if(admin_counter_sms==0){
         $('#admin_counter_sms').attr('value',1);
          $('#admin_counter_phone').html('<input type="number" class="form-control" name="admin_counter_sms" placeholder="Enter mobile number">');
       }
        var _token = $("#_token").val();
        var admin_counter_sms = $('#admin_counter_sms').val();
         $.ajax({
            url: "{{ url('/is_admin_counter') }}",
            type: "get",
            data: { admin_counter_sms:admin_counter_sms,sms_id:sms_id,_token:_token },
            success: function(response){
              if(data == "success")
                alert(response); 
            }
        });

    });
  $('#admin_app_sms').on('click', function(){
      var sms_id = $('#sms_id').val();
      var admin_app_sms = $('#admin_app_sms').val();

      if(admin_app_sms==1){
          $('#admin_app_sms').attr('value',0);
          $('#admin_app_phone').html('');
       }else if(admin_app_sms==0){
         $('#admin_app_sms').attr('value',1);
          $('#admin_app_phone').html('<input type="number" class="form-control" name="admin_app_sms" placeholder="Enter mobile number">');
       }
        var _token = $("#_token").val();
        var admin_app_sms = $('#admin_app_sms').val();
         $.ajax({
            url: "{{ url('/is_admin_app') }}",
            type: "get",
            data: { admin_app_sms:admin_app_sms,sms_id:sms_id,_token:_token },
            success: function(response){
              if(data == "success")
                alert(response); 
            }
        });

    });


     $('#counter_sms').on('click', function(){
      var sms_id = $('#sms_id').val();
      var counter_sms = $('#counter_sms').val();

       if(counter_sms==1){
          $('#counter_sms').attr('value',0);
          $('#counter_phone').html('');
       }else if(counter_sms==0){
         $('#counter_sms').attr('value',1);
       }
        var _token = $("#_token").val();
        var counter_val = $('#counter_sms').val();
         $.ajax({
            url: "{{ url('/counter_sms') }}",
            type: "get",
            data: { counter_sms:counter_val,sms_id:sms_id,_token:_token },
            success: function(response){
              if(data == "success")
                alert(response); 
            }
        });
  });

    $('#app_sms').on('click', function(){
      var sms_id = $('#sms_id').val();
      var app_sms = $('#app_sms').val();

         if(app_sms==1){
            $('#app_sms').attr('value',0);
    
         }else if(app_sms==0){
            $('#app_sms').attr('value',1);
          }
          var _token = $("#_token").val();
          var app_val = $('#app_sms').val();

          $.ajax({
            url: "{{ url('/app_sms') }}",
            type: "get",
            data: { app_sms:app_val,sms_id:sms_id,_token:_token },
            success: function(response){
              if(data == "success")
                alert(response); 
            }
        });
  });




</script>
@endpush
@endsection
