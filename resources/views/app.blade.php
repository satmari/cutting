<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cutting App</title>

	<!-- <link href="{{ asset('/css/app.css') }}" rel="stylesheet"> -->
	<!-- <link href="{{ asset('/css/css.css') }}" rel="stylesheet"> -->
	<!-- <link href="{{ asset('/css/custom.css') }}" rel="stylesheet"> -->
	<link href="{{ asset('/css/custom.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/bootstrap.min.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/bootstrap-table.css') }}" rel='stylesheet' type='text/css'>
	<!-- <link href="{{ asset('/css/jquery.dataTables.min.css') }}" rel='stylesheet' type='text/css'> -->
	<link href="{{ asset('/css/jquery-ui.min.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/app.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/choosen.css') }}" rel='stylesheet' type='text/css'>
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				@if(Auth::check() AND (Auth::user()->level() >= 5 OR Auth::user()->level() == 3 OR Auth::user()->level() == 1 OR Auth::user()->level() == 20))
				@else
				<a class="navbar-brand" href="http://172.27.161.171/preparation"><b>Preparation</b></a>
				<a class="navbar-brand" href="#">|</a>
				<a class="navbar-brand" href="http://172.27.161.171/trebovanje"><b>Trebovanje</b></a>
				<a class="navbar-brand" href="#">|</a>
				<a class="navbar-brand" href="http://172.27.161.171/downtime"><b>Downtime</b></a>
				<a class="navbar-brand" href="#">|</a>
				@endif
				<a class="navbar-brand" href="http://172.27.161.171/cutting"><b>Cutting</b></a>
				<a class="navbar-brand" href="#">|</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				
				@if(Auth::check() && Auth::user()->level() != 4)
			
					@if(Auth::user()->name == 'cutting')
						<ul class="nav navbar-nav">
							<li><a href="{{ url('/table_select') }}">Requests from lines</a></li>
						</ul>
						<ul class="nav navbar-nav">
							<li><a href="{{ url('/wastage_cut') }}">TPP wastage</a></li>
						</ul>
						<ul class="nav navbar-nav">
							<li><a href="{{ url('/import') }}">Import TPP material</a></li>
						</ul>
						<ul class="nav navbar-nav">
							<li><a href="{{ url('/cutting_xml') }}">SAP phase adv</a></li>
						</ul>
					@endif

					@if(Auth::user()->name == 'magacin')
						<ul class="nav navbar-nav">
							<li><a href="{{ url('/wastage_wh') }}">TPP wastage (wh)</a></li>
						</ul>
					@endif

					@if(Auth::user()->level() == 1)
						<ul class="nav navbar-nav">
							<li><a href="{{ url('#') }}">Admin test</a></li>
							<!-- <li><a href="{{ url('import') }}">Import</a></li> -->
							<li><a href="{{ url('operators') }}">Operators</a></li>

						</ul> 
						<ul class="nav navbar-nav navbar-rig ht">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Tables<span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="{{ url('marker') }}">Markers (xml)</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('pro_skeda') }}">Pro skeda (excel)</a></li>
									<li><a href="{{ url('paspul') }}">Paspul (excel)</a></li>
									<li><a href="{{ url('mattress') }}">Mattress (excel)</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('paspul_bin') }}">Paspul bin</a></li>
									
								</ul>
							</li>
							<li><a href="{{ url('plan_mattress/BOARD') }}">Plan mattress</a></li>
							<li><a href="{{ url('plan_mini_marker') }}">Plan mini-markers</a></li>
							<li><a href="{{ url('plan_paspul/NOT_SET') }}">Plan paspul</a></li>
							<li><a href="{{ url('print_mattress_multiple') }}">Print nalog</a></li>
							
						</ul>
						
					@endif

					@if(Auth::user()->level() == 3)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">Planner test</a></li> -->
							@if (isset($operators))
							<li>
								<form class="form-inline" style="width:350px; padding: 8px;" 
								action="{{ url('operator_login_planner') }}" method="get" >
								@if (!isset($operator))
								<select name="selected_operator" class="select form-control select-form" 
								style="width:150px !important">
	                                <option value="" selected></option>
	                        	    @foreach ($operators as $line)
	                                <option value="{{ $line->operator }}">
	                                    {{ $line->operator }}
	                            	</option>
	                            	@endforeach
	                            </select>
	                            <input type="submit" value="Login" class="btn btn-success">
	                            @else
	                            <span style="color: white;"><big>Operator is: <b>{{ $operator }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
	                            <a href="{{ url('operator_logout_planner')}}" class="btn btn-danger">Logout</a>
	                            @endif
	                        </li>
							@else
								@if(Session::has('operator'))
								<li>
									<div style="width:350px; padding: 8px;">
			                            <span style="color: white;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
				                        <a href="{{ url('operator_logout_planner')}}" class="btn btn-danger">Logout</a>
			                    	</div>
			                    </li>
		                        @endif
		                    @endif

							<li><a href="{{ url('import') }}">Import</a></li>
							<li><a href="{{ url('operators') }}">Operators</a></li>
						</ul>
						<ul class="nav navbar-nav navbar-rig ht">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Tables<span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="{{ url('marker') }}">Markers (xml)</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('pro_skeda') }}">Pro skeda (excel)</a></li>
									<li><a href="{{ url('paspul') }}">Paspul (excel)</a></li>
									<li><a href="{{ url('mattress') }}">Mattress (excel)</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('paspul_bin') }}">Paspul bin</a></li>
								</ul>
							</li>
							<li><a href="{{ url('plan_mattress/BOARD') }}">Plan mattress</a></li>
							<li><a href="{{ url('plan_mini_marker') }}">Plan mini-mattress</a></li>
							<li><a href="{{ url('plan_paspul/NOT_SET') }}">Plan paspul</a></li>
							<li><a href="{{ url('print_mattress_multiple') }}">Print nalog</a></li>
							
						</ul>

					@endif

					@if(Auth::user()->level() == 20)
						<ul class="nav navbar-nav">
							<li><a href="{{ url('#') }}">Guest test</a></li>
						</ul>
					@endif

					@if(Auth::user()->level() == 10)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">SP test</a></li> -->

						@if (isset($operators))
						<li>
							<form class="form-inline" style="width:350px; padding: 8px;" 
							action="{{ url('operator_login') }}" method="get" >
							@if (!isset($operator))
							<select name="selected_operator" class="select form-control select-form" 
							style="width:150px !important">
                                <option value="" selected></option>
                        	    @foreach ($operators as $line)
                                <option value="{{ $line->operator }}">
                                    {{ $line->operator }}
                            	</option>
                            	@endforeach
                            </select>
                            <input type="submit" value="Login" class="btn btn-success">
                            @else
                            <span style="color: white;"><big>Operator is: <b>{{ $operator }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ url('operator_logout')}}" class="btn btn-danger">Logout</a>
                            @endif
                        </li>
						@else
							@if(Session::has('operator'))
							<li>
							<div style="width:350px; padding: 8px;">
	                            <span style="color: white;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
		                        <a href="{{ url('operator_logout')}}" class="btn btn-danger">Logout</a>
	                        </div>
	                        </li>
	                        @endif
	                    @endif

						</ul>

					@endif

					@if(Auth::user()->level() == 11)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">MS test</a></li> -->

						@if (isset($operators))
						<li>
							<form class="form-inline" style="width:350px; padding: 8px;" 
							action="{{ url('operator_login') }}" method="get" >
							@if (!isset($operator))
							<select name="selected_operator" class="select form-control select-form" 
							style="width:150px !important">
                                <option value="" selected></option>
                        	    @foreach ($operators as $line)
                                <option value="{{ $line->operator }}">
                                    {{ $line->operator }}
                            	</option>
                            	@endforeach
                            </select>
                            <input type="submit" value="Login" class="btn btn-success">
                            @else
                            <span style="color: white;"><big>Operator is: <b>{{ $operator }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ url('operator_logout')}}" class="btn btn-danger">Logout</a>
                            @endif
                        </li>
						@else
							@if(Session::has('operator'))
							<li>
							<div style="width:350px; padding: 8px;">
	                            <span style="color: white;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
		                        <a href="{{ url('operator_logout')}}" class="btn btn-danger">Logout</a>
	                        </div>
	                        </li>
	                        @endif
	                    @endif

						</ul>
					@endif

					@if(Auth::user()->level() == 12)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">MM test</a></li> -->

						@if (isset($operators))
						<li>
							<form class="form-inline" style="width:350px; padding: 8px;" 
							action="{{ url('operator_login') }}" method="get" >
							@if (!isset($operator))
							<select name="selected_operator" class="select form-control select-form" 
							style="width:150px !important">
                                <option value="" selected></option>
                        	    @foreach ($operators as $line)
                                <option value="{{ $line->operator }}">
                                    {{ $line->operator }}
                            	</option>
                            	@endforeach
                            </select>
                            <input type="submit" value="Login" class="btn btn-success">
                            @else
                            <span style="color: white;"><big>Operator is: <b>{{ $operator }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ url('operator_logout')}}" class="btn btn-danger">Logout</a>
                            @endif
                        </li>
						@else
							@if(Session::has('operator'))
							<li>
							<div style="width:350px; padding: 8px;">
	                            <span style="color: white;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
		                        <a href="{{ url('operator_logout')}}" class="btn btn-danger">Logout</a>
	                        </div>
	                        </li>
	                        @endif
	                    @endif
						</ul>
					@endif

					@if(Auth::user()->level() == 13)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">LR test</a></li> -->
							@if (isset($operators))
						<li>
							<form class="form-inline" style="width:350px; padding: 8px;" 
							action="{{ url('operator_login_lr') }}" method="get" >
							@if (!isset($operator))
							<select name="selected_operator" class="select form-control select-form" 
							style="width:150px !important">
                                <option value="" selected></option>
                        	    @foreach ($operators as $line)
                                <option value="{{ $line->operator }}">
                                    {{ $line->operator }}
                            	</option>
                            	@endforeach
                            </select>
                            <input type="submit" value="Login" class="btn btn-success">
                            @else
                            <span style="color: white;"><big>Operator is: <b>{{ $operator }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ url('operator_logout_lr')}}" class="btn btn-danger">Logout</a>
                            @endif
                        </li>
						@else
							@if(Session::has('operator'))
							<li>
							<div style="width:350px; padding: 8px;">
	                            <span style="color: white;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
		                        <a href="{{ url('operator_logout_lr')}}" class="btn btn-danger">Logout</a>
	                        </div>
	                        </li>
	                        @endif
	                    @endif
						</ul>
					@endif

					@if(Auth::user()->level() == 14)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">PSO test</a></li> -->
							@if (isset($operators))
						<li>
							<form class="form-inline" style="width:350px; padding: 8px;" 
							action="{{ url('operator_login_pso') }}" method="get" >
							@if (!isset($operator))
							<select name="selected_operator" class="select form-control select-form" 
							style="width:150px !important">
                                <option value="" selected></option>
                        	    @foreach ($operators as $line)
                                <option value="{{ $line->operator }}">
                                    {{ $line->operator }}
                            	</option>
                            	@endforeach
                            </select>
                            <input type="submit" value="Login" class="btn btn-success">
                            @else
                            <span style="color: white;"><big>Operator is: <b>{{ $operator }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ url('operator_logout_pso')}}" class="btn btn-danger">Logout</a>
                            @endif
                        </li>
						@else
							@if(Session::has('operator'))
							<li>
							<div style="width:350px; padding: 8px;">
	                            <span style="color: white;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
		                        <a href="{{ url('operator_logout_pso')}}" class="btn btn-danger">Logout</a>
	                        </div>
	                        </li>
	                        @endif
	                    @endif
						</ul>
					@endif

					@if(Auth::user()->level() == 15)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">PRW test</a></li> -->
							@if (isset($operators))
						<li>
							<form class="form-inline" style="width:350px; padding: 8px;" 
							action="{{ url('operator_login_prw') }}" method="get" >
							@if (!isset($operator))
							<select name="selected_operator" class="select form-control select-form" 
							style="width:150px !important">
                                <option value="" selected></option>
                        	    @foreach ($operators as $line)
                                <option value="{{ $line->operator }}">
                                    {{ $line->operator }}
                            	</option>
                            	@endforeach
                            </select>
                            <input type="submit" value="Login" class="btn btn-success">
                            @else
                            <span style="color: white;"><big>Operator is: <b>{{ $operator }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ url('operator_logout_prw')}}" class="btn btn-danger">Logout</a>
                            @endif
                        </li>
						@else
							@if(Session::has('operator'))
							<li>
							<div style="width:350px; padding: 8px;">
	                            <span style="color: white;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
		                        <a href="{{ url('operator_logout_prw')}}" class="btn btn-danger">Logout</a>
	                        </div>
	                        </li>
	                        @endif
	                    @endif
						</ul>
					@endif

					@if(Auth::user()->level() == 16)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">PCO test</a></li> -->
								@if (isset($operators))
							<li>
								<form class="form-inline" style="width:350px; padding: 8px;" 
								action="{{ url('operator_login_pco') }}" method="get" >
								@if (!isset($operator))
								<select name="selected_operator" class="select form-control select-form" 
								style="width:150px !important">
	                                <option value="" selected></option>
	                        	    @foreach ($operators as $line)
	                                <option value="{{ $line->operator }}">
	                                    {{ $line->operator }}
	                            	</option>
	                            	@endforeach
	                            </select>
	                            <input type="submit" value="Login" class="btn btn-success">
	                            @else
	                            <span style="color: white;"><big>Operator is: <b>{{ $operator }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
	                            <a href="{{ url('operator_logout_pco')}}" class="btn btn-danger">Logout</a>
	                            @endif
	                        </li>
							@else
								@if(Session::has('operator'))
								<li>
								<div style="width:350px; padding: 8px;">
		                            <span style="color: white;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <a href="{{ url('operator_logout_pco')}}" class="btn btn-danger">Logout</a>
		                        </div>
		                        </li>
		                        @endif
		                    @endif
						</ul>
					@endif

					@if(Auth::user()->level() == 17)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">PACK test</a></li> -->
							@if (isset($operators))
							<li>
								<form class="form-inline" style="width:350px; padding: 8px;" 
								action="{{ url('operator_login_pack') }}" method="get" >
								@if (!isset($operator))
								<select name="selected_operator" class="select form-control select-form" 
								style="width:150px !important">
	                                <option value="" selected></option>
	                        	    @foreach ($operators as $line)
	                                <option value="{{ $line->operator }}">
	                                    {{ $line->operator }}
	                            	</option>
	                            	@endforeach
	                            </select>
	                            <input type="submit" value="Login" class="btn btn-success">
	                            @else
	                            <span style="color: white;"><big>Operator is: <b>{{ $operator }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
	                            <a href="{{ url('operator_logout_pack')}}" class="btn btn-danger">Logout</a>
	                            @endif
	                        </li>
							@else
								@if(Session::has('operator'))
								<li>
								<div style="width:350px; padding: 8px;">
		                            <span style="color: white;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <a href="{{ url('operator_logout_pack')}}" class="btn btn-danger">Logout</a>
		                        </div>
		                        </li>
		                        @endif
		                    @endif
						</ul>

					@endif

					@if(Auth::user()->level() == 18)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">PLOT test</a></li> -->
							@if (isset($operators))
							<li>
								<form class="form-inline" style="width:350px; padding: 8px;" 
								action="{{ url('operator_login_plot') }}" method="get" >
								@if (!isset($operator))
								<select name="selected_operator" class="select form-control select-form" 
								style="width:150px !important">
	                                <option value="" selected></option>
	                        	    @foreach ($operators as $line)
	                                <option value="{{ $line->operator }}">
	                                    {{ $line->operator }}
	                            	</option>
	                            	@endforeach
	                            </select>
	                            <input type="submit" value="Login" class="btn btn-success">
	                            @else
	                            <span style="color: white;"><big>Operator is: <b>{{ $operator }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
	                            <a href="{{ url('operator_logout_plot')}}" class="btn btn-danger">Logout</a>
	                            @endif
	                        </li>
							@else
								@if(Session::has('operator'))
								<li>
								<div style="width:350px; padding: 8px;">
		                            <span style="color: white;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <a href="{{ url('operator_logout_plot')}}" class="btn btn-danger">Logout</a>
		                        </div>
		                        </li>
		                        @endif
		                    @endif
						</ul>
					@endif

					@if(Auth::user()->level() == 19)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">CUT test</a></li> -->
							@if (isset($operators))
							<li>
								<form class="form-inline" style="width:350px; padding: 8px;" 
								action="{{ url('operator_login_cut') }}" method="get" >
								@if (!isset($operator))
								<select name="selected_operator" class="select form-control select-form" 
								style="width:150px !important">
	                                <option value="" selected></option>
	                        	    @foreach ($operators as $line)
	                                <option value="{{ $line->operator }}">
	                                    {{ $line->operator }}
	                            	</option>
	                            	@endforeach
	                            </select>
	                            <input type="submit" value="Login" class="btn btn-success">
	                            @else
	                            <span style="color: white;"><big>Operator is: <b>{{ $operator }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
	                            <a href="{{ url('operator_logout_cut')}}" class="btn btn-danger">Logout</a>
	                            @endif
	                        </li>
							@else
								@if(Session::has('operator'))
								<li>
								<div style="width:350px; padding: 8px;">
		                            <span style="color: white;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <a href="{{ url('operator_logout_cut')}}" class="btn btn-danger">Logout</a>
		                        </div>
		                    	</li>
		                        @endif
		                    @endif
						</ul>
					@endif

					

				@endif

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						{{--<li><a href="{{ url('/auth/register') }}">Register</a></li>--}}
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')
<!-- Scripts -->
	
	<script src="{{ asset('/js/jquery.min.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('/js/jquery-ui.min.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('/js/bootstrap-table.js') }}" type="text/javascript" ></script>
	
	<!-- <script src="{{ asset('/js/jquery.dataTables.min.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/jquery.tablesorter.min.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/custom.js') }}" type="text/javascript" ></script>-->
	<script src="{{ asset('/js/tableExport.js') }}" type="text/javascript" ></script>
	<!--<script src="{{ asset('/js/jspdf.plugin.autotable.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/jspdf.min.js') }}" type="text/javascript" ></script>-->
	<script src="{{ asset('/js/FileSaver.min.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('/js/bootstrap-table-export.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('/js/choosen.js') }}" type="text/javascript" ></script>

	<script type="text/javascript">
	   $.ajaxSetup({
	       headers: {
	           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	       }
	   });
	</script>

<script type="text/javascript">
$(function() {
// console.log(5 + 6);

	$(function() {
		$('.box').change(function(){
			// console.log(5 + 6);
	        var total = 0;
	        $('.box:checked').each(function(){
		    	// console.log( ($this).val());
		    	// console.log($(this).parent().parent().find('.amount').text());
		    	// 	console.log($(this).parent().parent().next('td').find('.amount').text());

	            // total=parseFloat($(this).parent().next('tr').find('.amount').text());
	            total+=parseFloat($(this).parent().parent().find('.amount').text());
	            // total+= total;

	        });
	        $('#total').text(total);
	    });
	});

    	
	$('#item').autocomplete({
		minLength: 1,
		autoFocus: true,
		source: '{{ URL('getitemdata')}}'
	});
	$('#variant').autocomplete({
		minLength: 1,
		autoFocus: true,
		source: '{{ URL('getvariantdata')}}'
	});
	$('#batch').autocomplete({
		minLength: 1,
		autoFocus: true,
		source: '{{ URL('getbatchdata')}}'
	});
	$('#po').autocomplete({
		minLength: 3,
		autoFocus: true,
		source: '{{ URL('getpodata')}}'
	});
	$('#por').autocomplete({
		minLength: 1,
		autoFocus: true,
		source: '{{ URL('getpordata')}}'
	});

	$('#filter').keyup(function () {

        var rex = new RegExp($(this).val(), 'i');
        $('.searchable tr').hide();
        $('.searchable tr').filter(function () {
            return rex.test($(this).text());
        }).show();
	});


	// $('#myTabs a').click(function (e) {
    // 		e.preventDefault()
    // 		$(this).tab('show')
	// });
	// $('#myTabs a:first').tab('show') // Select first tab

	$(function() {
    	$( "#datepicker" ).datepicker();
  	});
  	
	$('#sort').bootstrapTable({
    	
	});

	$(".chosen").chosen();


	//$('.table tr').each(function(){
  		
  		//$("td:contains('pending')").addClass('pending');
  		//$("td:contains('confirmed')").addClass('confirmed');
  		//$("td:contains('back')").addClass('back');
  		//$("td:contains('error')").addClass('error');
  		//$("td:contains('TEZENIS')").addClass('tezenis');

  		// $("td:contains('TEZENIS')").function() {
  		// 	$(this).index().addClass('tezenis');
  		// }
	//});

	// $('.days').each(function(){
	// 	var qty = $(this).html();
	// 	//console.log(qty);

	// 	if (qty < 7 ) {
	// 		$(this).addClass('zeleno');
	// 	} else if ((qty >= 7) && (qty <= 15)) {
	// 		$(this).addClass('zuto');
	// 	} else if (qty > 15 ) {	
	// 		$(this).addClass('crveno');
	// 	}
	// });


	// $('.status').each(function(){
	// 	var status = $(this).html();
	// 	//console.log(qty);

	// 	if (status == 'pending' ) {
	// 		$(this).addClass('pending');
	// 	} else if (status == 'confirmed') {
	// 		$(this).addClass('confirmed');
	// 	} else {	
	// 		$(this).addClass('back');
	// 	}
	// });

	// $('td').click(function() {
	//    	var myCol = $(this).index();
 	//    	var $tr = $(this).closest('tr');
 	//    	var myRow = $tr.index();

 	//    	console.log("col: "+myCol+" tr: "+$tr+" row:"+ myRow);
	// });

});
</script>
<script>
  $(document).ready(function() {

  	$("#sortable1 , #sortable2 , #sortable3 , #sortable4 , #sortable5, #sortable6, #sortable7, #sortable7, #sortable8, #sortable9" ).sortable({
    	connectWith: ".connectedSortable_ul_1",
    	dropOnEmpty: true
    }).disableSelection();

  //   var $tabs=$('#table-draggable2')
  //   $( "tbody.connectedSortable_table" )
  //       .sortable({
  //           connectWith: ".connectedSortable_table",
  //           // items: "> tr:not(:first)",
  //           items: "> tr",
  //           appendTo: $tabs,
  //           helper:"clone",
  //           zIndex: 999990
  //       })
  //       .disableSelection()
  //   ;
    
  //   var $tab_items = $( ".nav-tabs > li", $tabs ).droppable({
  //     accept: ".connectedSortable_table tr",
  //     hoverClass: "ui-state-hover",
      
  //     drop: function( event, ui ) {
  //       return false;
  //     }
  //   });

    $('#sortable2').sortable({
        // axis: 'y',
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            console.log("position changed 2");
            console.log(data);
            // POST to server using $.post or $.ajax
          	  $.ajax({
          	      data: data,
          	      type: 'POST',
          	      url: '{{ route('posts.reposition2') }}'
         	});
    	}
    });
    $('#sortable3').sortable({
        // axis: 'y',
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            console.log("position changed 3");
            console.log(data);
            // POST to server using $.post or $.ajax
          	  $.ajax({
          	      data: data,
          	      type: 'POST',
          	      url: '{{ route('posts.reposition3') }}'
         	});
    	}
    });
    $('#sortable4').sortable({
        // axis: 'y',
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            console.log("position changed 4");
            console.log(data);
            // POST to server using $.post or $.ajax
          	  $.ajax({
          	      data: data,
          	      type: 'POST',
          	      url: '{{ route('posts.reposition4') }}'
         	});
    	}
    });
    $('#sortable5').sortable({
        // axis: 'y',
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            console.log("position changed 5");
            console.log(data);
            // POST to server using $.post or $.ajax
          	  $.ajax({
          	      data: data,
          	      type: 'POST',
          	      url: '{{ route('posts.reposition5') }}'
         	});
    	}
    });
    $('#sortable6').sortable({
        // axis: 'y',
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            console.log("position changed 6");
            console.log(data);
            // POST to server using $.post or $.ajax
          	  $.ajax({
          	      data: data,
          	      type: 'POST',
          	      url: '{{ route('posts.reposition6') }}'
         	});
    	}
    });
    $('#sortable7').sortable({
        // axis: 'y',
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            console.log("position changed 7");
            console.log(data);
            // POST to server using $.post or $.ajax
          	  $.ajax({
          	      data: data,
          	      type: 'POST',
          	      url: '{{ route('posts.reposition7') }}'
         	});
    	}
    });
    $('#sortable8').sortable({
        // axis: 'y',
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            console.log("position changed 8");
            console.log(data);
            // POST to server using $.post or $.ajax
          	  $.ajax({
          	      data: data,
          	      type: 'POST',
          	      url: '{{ route('posts.reposition8') }}'
         	});
    	}
    });
    $('#sortable9').sortable({
        // axis: 'y',
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            console.log("position changed 9");
            console.log(data);
            // POST to server using $.post or $.ajax
          	  $.ajax({
          	      data: data,
          	      type: 'POST',
          	      url: '{{ route('posts.reposition9') }}'
         	});
    	}
    });

    var ids = [];
    $('#sortable10').sortable({
     	// var ids = [];
     	
	    // items: "li",
	    // start: function(event, ui) {
	    //   //Empty the array to avoid duplication.
	    //   ids = [];
	    //   //Store the ids in the array.
	    //   $.each(event.target.children, function() {
	    //     ids.push($(this).attr('id'));
	    //   })
	    // },

        axis: 'y',
        update: function (event, ui) {
            var ids = [];

			console.log("position changed sortable10");

		    //Store the ids in the array.
	      	$.each(event.target.children, function() {
	      		
	        	// ids.push($(this).attr('id'));
	        	ids.push($(this).attr('id'));
	        	// console.log($(this).attr('id'));
	        	// var ids = '';
	      	})
		    // console.log(ids.﻿toString());
		    data = ids.toString().replace(/\,/g, '&');
		    console.log(data);

		    $.ajax({
                data: data,
                type: 'POST',
                url: '{{ route('posts.reposition') }}'
         	});
    	}
    });

     var ids = [];
    $('#sortable11').sortable({
     	// var ids = [];
     	
	    // items: "li",
	    // start: function(event, ui) {
	    //   //Empty the array to avoid duplication.
	    //   ids = [];
	    //   //Store the ids in the array.
	    //   $.each(event.target.children, function() {
	    //     ids.push($(this).attr('id'));
	    //   })
	    // },

        axis: 'y',
        update: function (event, ui) {
            var ids = [];

			console.log("position changed sortable11");

		    //Store the ids in the array.
	      	$.each(event.target.children, function() {
	      		
	        	// ids.push($(this).attr('id'));
	        	ids.push($(this).attr('id'));
	        	// console.log($(this).attr('id'));
	        	// var ids = '';
	      	})
		    // console.log(ids.﻿toString());
		    data = ids.toString().replace(/\,/g, '&');
		    console.log(data);

		    $.ajax({
                data: data,
                type: 'POST',
                url: '{{ route('posts.reposition_pas') }}'
         	});
    	}
    });


	$('.connectedSortable_ul_1 li').tooltip({
	    track: true
	});

	// $(document).ready(function() { $('#exampleModalCenter').modal("show"); });
	// showEditor() {
	//     $("#EditModal").modal("show");
	//     $("#EditModal").appendTo("body");
	// }

	// $('.connectedSortable_ul_1 li span').tooltip({

	// $(document).ready(function() {
	//   $('a[data-toggle=modal], button[data-toggle=modal]').click(function () {
	//   	console.log("modal run");
	//     var data_id = '';
	//     // if (typeof $(this).data('id') !== 'undefined') {
	//       data_id = $(this).data('id');
	//       data_value = $(this).attr('data-value');
	//       data_push = $(this).attr('data-push');
	//       console.log(data_id);
	//       console.log(data_value);
	//       console.log(data_push);
	//     // }
	//     // $('#my_element_id').text('test');
	//     $('#my_element_id').text(data_id);
	//     $('#my_element_value').text(data_value);
	//     $('#my_element_push').text(data_push);
	    
	//   })
	// });
	
});
</script>

</body>
</html>
