@extends('layouts.master')

@section('title')
Trail Day Project
@endsection


@section('header')
	
	<!-- League Creation Modal -->
	<div class="modal fade" id="create_league" tabindex="-1" role="dialog" aria-labelledby="create_league" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="create_league">Create A New Cricket League</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <form id="store_league_form" method="post" action="javascript:void(0)">
				@csrf
			  <div class="modal-body">
						
				<div class="form-group">
					<label for="league_name">League Name</label>
					<input type="text"  class="form-control" name="name" id="league_name"  aria-describedby="emailHelp" 
					placeholder="Champions ship league,Super league..." >
					<div id="league_name_required_div" oninput="check_validation()" style="display:hidden" class="invalid-feedback">League name is required.</div>				
				</div>
				
				<div class="form-group">
					<label for="name">Number Of Participating Team</label>
					<input type="number" oninput="check_validation()"  class="form-control" id="no_of_teams" name="no_of_teams"  aria-describedby="emailHelp" 
					placeholder="No of teams" >	
					<div id="no_of_teams_required_div" style="display:hidden" class="invalid-feedback">Team number is required.</div>
					<div id="no_of_teams_required_even_div" style="display:hidden" class="invalid-feedback">Team number should be even.</div>                                               				
				</div>

				<div class="form-group">
					<label for="name">No Of Overs </label>
					<select  class="form-control" id="no_of_overs" name="no_of_overs">
					<option value="50" >ODI</option>
					<option value="20" >T20</option>
					<option value="10" >T10</option>
					</select>	
					                                         				
				</div>				
				
			</div>
			
			<div class="alert alert-success d-none" id="msg_div">
              <span id="res_message"></span>
			</div>
			
			<div class="modal-footer">
				<button type="submit" id="send_form" onclick="create_league()" class="btn btn-success">Create League</button>				
			</div>	
		
		</form>		
		</div>		
	  </div>	  
	</div>
	
	<!-- League Creation Button -->		
	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">	 
		  <div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
			
		
					
			  <li class="nav-item" style="margin-right:0px" >
					
				<h6 class="pull-right"><a href="#" data-toggle="modal" data-target="#create_league">Create New Cricket League</a></h6>
				
			  </li>
			  
			</ul>
		  </div>
		</nav>
	</div>


@endsection


@section('contents')

	
	<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
				    <div class="card-body">
                        <table class="table" >
                            <thead>
                                <tr>
                                    <th scope="col">League</th>                                   
                                    <th scope="col">No Of Overs</th>
                                    <th scope="col">Participating  Teams</th>
									<th scope="col">Total Matches</th>
                                    <th scope="col">Created Date</th>
                                    <th scope="col">View Details</th>
                                </tr>
                            </thead>
                            <tbody id="leagues_table_body">
							@foreach($leagues as $league)								
								<tr>
								<td> {{ $league->name }} </td>   
								<td> {{ $league->no_of_overs }} </td>   
                                <td> {{ $league->teams_count }} </td>           
                                <td> {{ $league->matches_count }} </td>           
                                <td> {{ date("m/d/Y", $league->created_at) }} </td>                          
                                <td><a href="{{url('view/league/matches')}}/{{encrypt($league->id)}}">View Matches</a></td>                           
                                </tr>							
							@endforeach                                
                            </tbody>
                        </table>                       
                    </div>
					<ul class="col-md-12 blog-posts post-list">
						<nav>
							<!-- .pagination -->
							<ul class="pagination">
								<br>
								<br>
								{!! $leagues->links(); !!}
							</ul>
							<!-- .pagination end -->
						</nav>
					<ul>
                </div>
            </div>
        </div>        
    </div> 
@endsection

@section('scripts')

<script type="text/javascript">

	//league cration process validation check 
	function check_validation()
	{		
		var league_name = $("#league_name").val();		
		var no_of_teams = $("#no_of_teams").val();	
		
		var is_valid = true;
		if( league_name == '' ) 
		{ $("#league_name_required_div").show(); is_valid = false;}
		else
		{ $("#league_name_required_div").hide(); }
	
		if( no_of_teams == '' ) 
		{ 	
			$("#no_of_teams_required_even_div").hide();
			$("#no_of_teams_required_div").show();			
			return false;
		}
		else
		{ $("#no_of_teams_required_div").hide(); }
	
		
		if( parseInt(no_of_teams) % 2 != 0 ) 
		{ 
			$("#no_of_teams_required_even_div").show();			
			return false;
		}
		else
		{ $("#no_of_teams_required_even_div").hide(); }
	
		return is_valid;	
	}
    
	var is_submited = false; // 
	// create a new league by ajax call
    function create_league(id){
		
			
		if(! is_submited){
			is_submited = true;
			if(check_validation())
			{
				
				
				$('#send_form').html('Sending...');
				$.ajax({
					type: "GET",
					url:  '{{ route('store_league') }}',
					data: $('#store_league_form').serialize(),
					success: function( response ) {			
					
					$('#send_form').html('Submit');
					$('#res_message').show();
					$('#res_message').html(response.msg);
					$('#msg_div').removeClass('d-none');			 
					document.getElementById("store_league_form").reset(); 							
					
					var created_at = new Date(response.league.created_at * 1000);				
									
					var route = "{{url('view/league/matches')}}"+ "/"+ response.id +"";
					
					
					
					var table_row = '<tr>'+
									'<td>'+ response.league.name +'</td>' +
									'<td>'+ response.league.no_of_overs +'</td>' +
									'<td>'+ response.teamscount +'</td>' +
									'<td>'+ response.league.matches_count +'</td>' +                            
									'<td>'+ created_at.toLocaleString() +'</td> ' +                            
									'<td><a href="'+ route +'">View Matches</a></td>' +                            
									'</tr>';
									
					$('#leagues_table_body').append(table_row);
					
					setTimeout(function(){
						$('#res_message').hide();
						$('#msg_div').hide();
						
						
					},2000);
					is_submited = false;
					},
					error:function(response){
						is_submited = false;
						$('#res_message').show();
						$('#res_message').html('Failed Submission !!!!!!!');
						$('#msg_div').removeClass('d-none');
						$('#msg_div').removeClass('alert-success');
						$('#msg_div').addClass('alert-danger');
						setTimeout(function(){
							$('#res_message').hide();
							$('#msg_div').hide();
							},2000);
							
					}
				});
			}
			else
			{
				alert('Please Check Warnings !!!!!!!');
			}
		}
		else
		{
			alert('Please Wait, Your League is being created !!!!!!!!!!!!1');
		}
    }	



</script>

 
@endsection