<?php include 'db_connect.php' ?>
<h1><div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-body">
			<div class="d-flex w-100 px-1 py-2 justify-content-center align-items-center">
				<label for="">Enter Tracking Number :</label>
				<div class="input-group col-sm-5">
                    <input type="search" id="ref_no" class="form-control form-control-sm" placeholder="Type the tracking number here">
                    <div class="input-group-append">
                        <button type="button" id="track-btn" class="btn btn-sm btn-primary btn-gradient-primary">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
			</div>
		</div>
	</div>
	<!-- This holds the dynamically generated timeline items -->
	<!-- This is the div where the data will be displayed after fetching from the database  -->
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<div class="timeline" id="parcel_history">
				
			</div>
		</div>
	</div>
</div>
<div id="clone_timeline-item" class="d-none">
	<div class="iitem">
	    <i class="fas fa-box bg-blue"></i>
	    <div class="timeline-item">
	      <span class="time"><i class="fas fa-clock"></i> <span class="dtime">12:05</span></span>
	      <div class="timeline-body">
	      	asdasd
	      </div>
	    </div>
	  </div>
</div>
<script>
	//track_now() initiates the tracking process
	function track_now(){
		start_load()
		var tracking_num = $('#ref_no').val()
		if(tracking_num == ''){
			$('#parcel_history').html('')
			end_load()
		}else{
			//Here we are initiating an AJAX request using jQuery's ajax() function
			$.ajax({
				url:'ajax.php?action=get_parcel_history', 
				method:'POST',
				
				data:{ref_no:tracking_num}, //Specifies the data to be sent along with AJAX req. ref_no is key here while tracking_num is a parameter
				error:err=>{  // Error callback function if request fails
					console.log(err)
					alert_toast("An error occured",'error')
					end_load()
				}, 
				success:function(resp){ //Success callback function if request succeeds
					//Checks whether resp is an object or array. This is to ensure that the parsed response is a valid JSON format
					if(typeof resp === 'object' || Array.isArray(resp) || typeof JSON.parse(resp) === 'object'){
						//Parses the response as a JSON object
						resp = JSON.parse(resp)
						//Confirms that resp is not an empty object
						if(Object.keys(resp).length > 0){
							//Clears the HTML content of this element to ensure that its empty before appending the data
							$('#parcel_history').html('')

							Object.keys(resp).map(function(k){
								//Clones the hidden timeline template
								var tl = $('#clone_timeline-item .iitem').clone()
								//finds element with class=dtime and sets its content to date_created and status
								tl.find('.dtime').text(resp[k].date_created)
								tl.find('.timeline-body').text(resp[k].status)
								//Appends in our webpage
								$('#parcel_history').append(tl)
							})
						}
					}else if(resp == 2){
						alert_toast('Unkown Tracking Number.',"error")
					}
				}
				//Defines a complete callback function that will execute whether AJAX request succeeds or fails
				,complete:function(){
					end_load()
				}
			})
		}
	}
	$('#track-btn').click(function(){
		track_now()
	})
	$('#ref_no').on('search',function(){
		track_now()
	})
</script></h1>
