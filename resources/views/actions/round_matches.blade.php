@extends('layouts.master')

@section('title')
Trail Day Project
@endsection


@section('header')	

@endsection


@section('contents')

	
    <div class="container">
        <div class="row" style="margin-top:50px">
            <div class="col-md-12 col-lg-12">                
				<div class="card">
                    <div class="card-header">					
                        @if($is_league_ended)
						<span>Cricket League Team Results Table</span>
						@else
                        <span>
						@if($round > 0) 
							Teams Results Table For Round {{$round}}
						@else	
							Cricket League Team Results Table
						@endif 
                        </span>
						@endif						
						<a  href="{{url('/')}}" style="float:right"  >View Main Page</a>
                    </div>
					<!-- Each Team Results Summary -->
                    <div class="card-body">
                        <table class="table col">
                            <thead>
                                <tr>
                                    <th scope="col" colspan="2">Teams</th>                                   
                                    <th scope="col">MP</th>									
                                    <th scope="col">W</th>
									 <th scope="col">L</th>
                                    <th scope="col">N/R</th>                                   
                                    <th scope="col">R</th>                                   
                                    <th scope="col">NRR</th>                                   
									<th scope="col">PTS</th>
                                    <th scope="col">Form</th>
                                </tr>
                            </thead>
                            <tbody>
								@foreach($teams as $team)
                                <tr>
                                    <td><img src="{{asset('uploads')}}/countries/{{$team->flag}}"height="15px" width="17px"/></td>
									<td>{{ $team->name }}</th>
									<td>{{ $team->played_matches_count }}</td>
									<td>{{ $team->won_matches_count }}</td>
									<td>{{ $team->lost_matches_count }}</td>
									<td>{{ $team->drawn_matches_count }}</td>
									<td>{{ $team->total_runs .':'.  $team->received_runs }}</td>
                                    <td>{{ $team->net_run_rate > 0 ? ('+'.$team->net_run_rate) : $team->net_run_rate }}</td>	
									<td>{{ $team->won_matches_count * 2 + $team->drawn_matches_count }}</td>									
                                    <td>
									@foreach($team->last_five_played_matches as $recent_match)
									{{ $recent_match->result == 'lost' ? 'L' : ( $recent_match->result == 'won' ? 'W' : 'N/R' ) }}
									&nbsp;
									&nbsp;
									@endforeach
									</td>									
                                </tr>
								@endforeach
                            </tbody>
                        </table>
						
                        @if(!$is_league_ended)
							@if( $league->max_round != ( decrypt($round) !='all'?  decrypt($round) - 1 : 'all' ) )							 
							<a href="{{url('play/round/matches')}}/{{encrypt(decrypt($round)) }}/{{$league_id}}"
							style="float:right"  >Play Round Match</a>
							@else
								 <span class="well col-md-10 col-sm-10 col-lg-10">View Matches For round </span>										
								<a href="{{url('view/league/matches')}}/{{$league_id}}/{{'all'}}"
								style="float:right;padding-left:20px"  >all</a>										
								@for($j = $league->max_round ; $j >=1 ; $j--)
								<a href="{{url('view/league/matches')}}/{{$league_id}}/{{$j+1}}"
										style="float:right;padding-left:20px"  >{{ $j  }}</a>												
								@endfor												
							@endif	                           								
                        @else                       							
							View round wise Matches
							<a href="{{url('view/league/matches')}}/{{$league_id}}/{{'all'}}"
										style="float:right;padding-left:20px"  >all</a>
                            @for($j = $league->max_round ; $j >= 1 ; $j--)
								<a href="{{url('view/league/matches')}}/{{$league_id}}/{{$j+1}}"
										style="float:right;padding-left:20px"  >{{ $j  }}</a>										
							@endfor										
						@endif						
                    </div>
                </div>           
		   </div>

			<!-- Each Played Match Results Or Standings Table-->
			@if(count($matches) > 0)
			<div class="col-md-12 col-lg-12" >                
				<div class="card">
                    <div class="card-header">
						<span>
                            Match Results  @if($round) For round {{ ( decrypt($round) !='all'?  decrypt($round) - 1 : 'all' ) }} @endif 
                        </span>						
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">                         
                            <tbody>
								@php($round_match = $match->round)
								@foreach($matches as $match)									
									@if($match->round != $round_match )	
										@if($round_match != '' )<tr><td colspan="6" style="border:none" >&nbsp;</td></tr> @endif	
										<tr><td colspan="6" style="text-align:center" class="bg-info">Matches For round {{ $match->round }}</td></tr>											
									@endif										
									@php($result = '' )
									@php($finished = 'Finished' )
									@php($time = '' )
									@foreach($match->match_teams as $match_team)
									<tr>										
									@php($background = "" )
									@if( $match_team->result=='won' ) 
									@php($background = "bg-success" )
									@elseif($match_team->result=='lost')
									@php($background = "bg-danger" )
									@else @php($background = "bg-secondary" )
									@endif											
									<th  class="{{$background}}" style="text-align:left;padding:5px 5px"> @if( $time == '' ) {{ $match_team->start_time }} - {{ $match_team->end_time }} @endif </th>		
									<th  class="{{$background}}" style="text-align:left;padding:5px 5px"> {{$finished}} </th>	
									<th  class="{{$background}}" style="text-align:left;padding:5px 5px"><img src="{{asset('uploads')}}/countries/{{$match_team->team->flag}}"	height="15px" width="17px"/></td>								
									<th  class="{{$background}}" style="text-align:left;padding:5px 5px;font-size:10px">{{ $match_team->team->name }}</th>
									<th  class="{{$background}}" style="text-align:left;padding:5px 5px;font-size:10px">{{ $match_team->runs ."/". $match_team->wicket_lost."(".($match_team->played_balls / 6 ).")" }}</th>											
									<th  class="{{$background}}" style="text-align:left;padding:5px 5px;font-size:10px">{{ round( $match_team->runs / ($match_team->played_balls / 6 ),2)  }}</th>											
									</tr>									
									@if($match_team->result=='won')
										@if( $match_team->elected_to == 'bat' )
											@php($result = $match_team->team->name.'Won By'. (10 - $match_team->wicket_lost).'wickets')
										@else	
											@php($result = $match_team->team->name.'Won By'. ($match_team->runs - $match_team->received_runs).'Runs')												
										@endif												
									@endif	
									@php($finished='')
									@endforeach
									<tr><td colspan="6" style="text-align:center" class="bg-info">{{$result}}</td></tr>																	
									@php($round_match = $match->round)
								@endforeach
                            </tbody>
                        </table
                    </div>
                </div>           
		   </div>
		@endif		   
	</div>
	<!-- End Match Results -->
	
			<!-- Not Started Match -->
			@if( count($not_started_matches) > 0 && count($started_matches) == 0 )
			<div class="col-md-12 col-lg-12" style="margin-top:50px" >                
				<div class="card">
                    <div class="card-header">
						<span>
                            Start Matches @if($round) For round {{ ( decrypt($round) !='all'?  decrypt($round)  : 'all' ) }} @endif 
                        </span>						
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">                         
                            <tbody>	
								@php($round_match = $round)
								@foreach($not_started_matches as $match)	
									@if($match->round != $round_match )	
									@if($round_match != '' )
										<tr>
										<td colspan="2" style="text-align:center" >Start Matches For round {{ $match->round }} </td>
										<td style="text-align:center" >
											<a href="{{url('play/round/matches')}}/{{encrypt( decrypt($round) ) }}/{{$league_id}}" style="float:right"  >Play Round Match</a>
										</td>
										</tr>
									@endif
									@endif
									@php($result = '' )									
									@php($time = '' )
									@foreach($match->match_teams as $match_team)
									<tr>																
									<th  style="text-align:left;padding:5px 5px"> @if( $time == '' ) {{ $match_team->start_time }} - {{ $match_team->end_time }} @endif </th>		
									<th  style="text-align:left;padding:5px 5px"><img src="{{asset('uploads')}}/countries/{{$match_team->team->flag}}"	height="15px" width="17px"/></td>								
									<th  style="text-align:left;padding:5px 5px;font-size:10px">{{ $match_team->team->name }}</th>
																		
									</tr>	
									@if($result=='')
									@php( $result = $match_team->toss == 'won' ?  $match_team->team->name.' Won the toss and elected to '. $match_team->elected_to .' first': '' )									
									@endif
									@endforeach									
									<tr><td colspan="6" style="text-align:left" >{{$result}}</td></tr>
								@php($round_match = $match->round)
								@endforeach
                            </tbody>
                        </table
                    </div>
                </div>           
		   </div>
		@endif		   
	
			<!-- End Not Started Match -->
			
			<!-- started Match Results Or Standings Table-->
			@if( count($started_matches) > 0 )
			<div class="col-md-12 col-lg-12" style="margin-top:50px" >                
				<div class="card">
                    <div class="card-header">
						<span>
                            Current Matches @if($round) For round {{ ( decrypt($round) !='all'?  decrypt($round) - 1  : 'all' ) }} @endif 
                        </span>						
                    </div>
                    <div class="card-body">
                        
						        <table class="table table-bordered">                         
									<tbody>	
										
										@foreach($started_matches as $match)
											@php($time = '' )
											@php($result = '' )
											@foreach($match->match_teams as $match_team)
											@php($team_viewed = false )
											<tr>
												@foreach($match->match_teams as $match_team)
													<td  style="text-align:left;padding:5px 5px">
													<img src="{{asset('uploads')}}/countries/{{$match_team->team->flag}}" height="15px" width="17px"/>
													&nbsp;{{ $match_team->team->name }}
													</td>																																
													@if(!$team_viewed)
													<td  style="text-align:left;padding:5px 5px;" align="center">Vs.</td>																																
													@endif	
													@php($team_viewed = true)
												@endforeach
											</tr>
											@if($match_team->result=='won')
														@if( $match_team->elected_to == 'bat' )
															@php($result = $match_team->team->name.'Won By'. (10 - $match_team->wicket_lost).'wickets')
														@else	
															@php($result = $match_team->team->name.'Won By'. ($match_team->runs - $match_team->received_runs).'Runs')												
														@endif	
											<tr>
												<td  style="text-align:left;padding:5px 5px;align:center" colspan="3">
														{{$result}}
												</td>																				
											</tr>				
											@endif	
											
											
											<tr>
												<td  style="text-align:left;padding:5px 5px;align:center" colspan="3">
												<img src="{{asset('uploads')}}/countries/{{$match_team->team->flag}}" height="15px" width="17px"/>
												&nbsp;{{ $match_team->team->name }} Inning
												</td>																				
											</tr>
											<tr>
												<td colspan="3">
													<table class="table table-bordered"> 
															<tbody>
																	<tr>
																		<th>image</th>                                   
																		<th>Batsman</th>                                   
																		<th>status	</th>									
																		<th>runs</th>
																		 <th>b</th>
																		<th>4s</th>                                   
																		<th>6s</th>                                   
																		<th>s/r</th>                                   
																	</tr>			
																	@php($not_out_count = 0 )
																	@foreach($match_team->batting_players as $batting_player)
																		@if( ($batting_player->status == 'not_out' || $batting_player->status == 'out') && $not_out_count < 2  )
																		<tr>																
																			<td><img src="{{asset('uploads')}}/players/{{$batting_player->player->image}}"	height="15px" width="17px"/></td>								
																			<td style="font-size10px">{{ $batting_player->player->name }}</td>								
																			<td style="font-size10px">{{ $batting_player->status }}</td>	
																			<td style="font-size10px">{{ $batting_player->runs }}</td>	
																			<td style="font-size10px">{{ $batting_player->description }}</td>								
																			<td style="font-size10px">{{ $batting_player->balls_played }}</td>								
																			<td style="font-size10px">{{ $batting_player->fours }}</td>								
																			<td style="font-size10px">{{ $batting_player->sixes }}</td>								
																		</tr>
																			@if($batting_player->status == 'not_out')
																				@php($not_out_count++)
																			@endif
																		@endif			
																	@endforeach
																
																	<tr>																
																		<td style="font-size:10px" colspan="2" style="text-align:right">Total:</td>								
																		<td style="font-size:10px">( {{ round( $match_team->played_balls / 6, 2 ) }} )</td>	
																		<td style="font-size:10px">{{ $match_team->runs . '/' . $match_team->wicket_lost }}</td>	
																		<td style="font-size:10px"  colspan="4">( {{ round( $match_team->runs / (($match_team->played_balls / 6) == 0 ? 1 : ($match_team->played_balls / 6))  , 2 ) }} runs per over )</td>																																	
																	</tr>
																	
																	<tr>
																		<th>image</th>                                   
																		<th>Bowler</th>                                   
																		<th>overs</th>                                   
																		<th>m</th>									
																		<th>r</th>									
																		<th>w</th>
																		<th colspan="2">e/r</th>																	                                 
																	</tr>
																	@php($not_bowled_count = 0 )
																	@foreach($match_team->bowling_players as $bowling_player)
																		@if( $not_bowled_count < 2  || $bowling_player->balls_played > 0 )																			
																			<tr>																
																				<td><img src="{{asset('uploads')}}/players/{{$bowling_player->player->image}}"	height="15px" width="17px"/></td>								
																				<td>{{ $bowling_player->player->name }}</td>								
																				<td>{{ $bowling_player->balls_played  / 6 }}</td>	
																				<td>{{ $bowling_player->maiden }}</td>	
																				<td>{{ $bowling_player->runs }}</td>								
																				<td>{{ $bowling_player->wickets }}</td>								
																				<td colspan="2">{{ $bowling_player->runs / ($bowling_player->balls_played == 0 ? 1 : $bowling_player->balls_played ) }}</td>																													
																			</tr>
																			@if($bowling_player->balls_played == 0 )
																				@php($not_bowled_count++)
																			@endif			
																		@endif			
																	@endforeach
																	<tr>																
																		<td style="font-size:10px" colspan="8">&nbsp;</td>								
																	</tr>																	
															</tbody>
														</table>
													</td>
											</tr>										
									@endforeach
								@endforeach
                            </tbody>
                        </table
                    </div>
				</div>
              </div>           
		   </div>
		@endif
		
		@if(count($started_matches) > 0 )
			<h1>Time Has Finished , So You Can See The Database Structure For All The Feild You Needed , But Unfortunately The Time Finished. If You Request Me To Complete I Will Go On</h1>
		@endif	

		
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

	// create a new league by ajax call
    function create_league(id){
		
			
		
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
								
				var route = "{{url('view/league/matches')}}"+ "/{{encrypt("+ response.league.id +")}}";
				
				var table_row = '<tr>'+
                                '<td>'+ response.league.name +'</td>' +
                                '<td>'+ response.league.teams_count +'</td>' +
                                '<td>'+ response.league.matches_count +'</td>' +                            
                                '<td>'+ created_at.toLocaleString().substr(6) +'</td> ' +                            
                                '<td><a href="'+ route +'">View Details</a></td>' +                            
                                '</tr>';
								
				$('#leagues_table_body').append(table_row);
				
				setTimeout(function(){
				$('#res_message').hide();
				$('#msg_div').hide();
				},2000);
				
				},
				error:function(response){
					
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



</script>

 
@endsection