<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	@if (config('app.global_variable') == 'gordon')
		<title>Cutting App - Gordon</title>
	@elseif (config('app.global_variable') == 'fiorano')
		<title>Cutting App - Fiorano</title>
	@elseif (config('app.global_variable') == 'adrianatex')
		<title>Cutting App - Adrianatex</title>
	@elseif (config('app.global_variable') == 'itaca')
		<title>Cutting App - Itaca</title>
	@else 
		<title>Cutting App - plant </title>
	@endif
		
	
	<!-- global variable: {{ config('app.global_variable') }} -->
	

	<!-- <link href="{{ asset('/css/app.css') }}" rel="stylesheet"> -->
	<!-- <link href="{{ asset('/css/css.css') }}" rel="stylesheet"> -->
	<!-- <link href="{{ asset('/css/custom.css') }}" rel="stylesheet"> -->
	@if (config('app.global_variable') == 'gordon')
		<link href="{{ asset('/css/custom.css') }}" rel='stylesheet' type='text/css'>
	@elseif (config('app.global_variable') == 'fiorano')
		<link href="{{ asset('/css/customf.css') }}" rel='stylesheet' type='text/css'>
	@elseif (config('app.global_variable') == 'adrianatex')
		<link href="{{ asset('/css/customa.css') }}" rel='stylesheet' type='text/css'>
	@elseif (config('app.global_variable') == 'itaca')
		<link href="{{ asset('/css/customi.css') }}" rel='stylesheet' type='text/css'>
	@else
		<link href="{{ asset('/css/custom.css') }}" rel='stylesheet' type='text/css'>
	@endif

	<!-- <link href="{{ asset('/css/jquery.dataTables.min.css') }}" rel='stylesheet' type='text/css'> -->
	<link href="{{ asset('/css/jquery-ui.min.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/app.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/choosen.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/bootstrap.min.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/bootstrap-table.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/select2.min.css') }}" rel='stylesheet' type='text/css'>
	<!-- <link rel="manifest" href="{{ asset('/css/manifest.json') }}"> -->
	
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
				@if (config('app.global_variable') == 'gordon')
					@if(Auth::check() AND (Auth::user()->level() >= 5 OR Auth::user()->level() == 3 OR Auth::user()->level() == 1 OR Auth::user()->level() == 20))
					@else
					<a class="navbar-brand" href="http://172.27.161.193/preparation"><b>Preparation</b></a>
					<a class="navbar-brand" href="#">|</a>
					<a class="navbar-brand" href="http://172.27.161.171/trebovanje"><b>Trebovanje</b></a>
					<a class="navbar-brand" href="#">|</a>
					<!-- <a class="navbar-brand" href="http://172.27.161.171/downtime"><b>Downtime</b></a>
					<a class="navbar-brand" href="#">|</a> -->
					@endif
				@else
				@endif

				@if (config('app.global_variable') == 'gordon')
					<a class="navbar-brand" href="{{ url('/') }}"><b>Cutting</b></a>
				@elseif (config('app.global_variable') == 'fiorano')
					<a class="navbar-brand" href="{{ url('/') }}"><b>Cutting Fiorano</b></a>
				@elseif (config('app.global_variable') == 'adrianatex')
					<a class="navbar-brand" href="{{ url('/') }}"><b>Cutting Adrianatex</b></a>
				@elseif (config('app.global_variable') == 'itaca')
					<a class="navbar-brand" href="{{ url('/') }}"><b>Cutting Itaca</b></a>
				@else
					<a class="navbar-brand" href="{{ url('/') }}"><b>Cutting </b></a>
				@endif
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
							<!-- <li><a href="{{ url('#') }}">Planner </a></li> -->
							@if (isset($operators))
							<li>
								
	                        </li>
							@else
								@if(Session::has('operator'))
								<li>
									<br>
			                        <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
			                        </strong>
		                        </li>
		                        @endif
		                    @endif
						</ul>
						<ul class="nav navbar-nav">
							<li><a href="{{ url('/wastage_wh') }}">TPP wastage (wh)</a></li>
						</ul>
						<ul class="nav navbar-nav">
							<li><a href="{{ url('/inbound_delivery_index') }}">Inbound deliveries</a></li>
						</ul>
					@endif

					<!-- admin -->
					@if(Auth::user()->level() == 1)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">Planner test</a></li> -->
							@if (isset($operators))
							<li>
								@if (!isset($operator))
									<form class="form-inline" style="width:400px; padding: 8px;" 
									action="{{ url('operator_login_planner') }}" method="get" >
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
		                            </form>
	                            @else
	                            <br>
	                            <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ $operator }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
	                            <a href="{{ url('operator_logout_planner')}}" class="btn btn-danger btn-xs">Logout</a></strong>
	                            @endif
	                        </li>
							@else
								@if(Session::has('operator'))
								<br>
	                            <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ $operator }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
	                            <a href="{{ url('operator_logout_planner')}}" class="btn btn-danger btn-xs">Logout</a></strong>
		                        @endif
		                    @endif
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
									<li><a href="{{ url('paspul_bin') }}">Paspul bin (excel)</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('mat_con_file') }}">MATCON file</a></li>
									<li><a href="{{ url('consumption_sap') }}">Consumption SAP</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('skeda_comments') }}">Skeda comments</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('material_comment_table') }}">Material comments</a></li>
								</ul>
							</li>
							<li><a href="{{ url('import') }}">Import</a></li>
							<li><a href="{{ url('operators') }}">Operators</a></li>
							<li><a href="{{ url('print_mattress_multiple') }}">Print nalog</a></li>
							<li><a href="{{ url('plan_mattress/BOARD') }}"><b><sapn style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;">Plan mattress</span></b></a></li>
							<li><a href="{{ url('plan_mini_marker') }}"><b><sapn style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;">Plan mini-mattress</span></b></a></li>
							<li><a href="{{ url('plan_paspul/BOARD') }}"><b><sapn style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;">Plan paspul</span></b></a></li>
							<li><a href="{{ url('papsul_stock') }}"><b><sapn style="color: white;">Paspul stock</span></b></a></li>
						</ul>
					@endif

					<!-- planner -->
					@if(Auth::user()->level() == 3)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">Planner </a></li> -->
							@if (isset($operators))
							<li>
								@if (!isset($operator))
									<form class="form-inline" style="width:400px; padding: 8px;" 
									action="{{ url('operator_login_planner') }}" method="get" >
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
		                            </form>
	                            @else
	                            <br>
	                            <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ $operator }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
	                            <a href="{{ url('operator_logout_planner')}}" class="btn btn-danger btn-xs">Logout</a></strong>
	                            @endif
	                        </li>
							@else
								@if(Session::has('operator'))
								<li>
									<br>
			                        <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <a href="{{ url('operator_logout_planner')}}" class="btn btn-danger btn-xs">Logout</a></strong>
		                        </li>
		                        @endif
		                    @endif
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
									<li><a href="{{ url('paspul_bin') }}">Paspul bin (excel)</a></li>
									<li><a href="{{ url('paspul_locations') }}">Paspul locations</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('mat_con_file') }}">MATCON file</a></li>
									<li><a href="{{ url('consumption_sap') }}">Consumption SAP</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('skeda_comments') }}">Skeda comments</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('material_comment_table') }}">Material standard comments</a></li>
								</ul>
							</li>
							<li><a href="{{ url('import') }}">Import</a></li>
							<li><a href="{{ url('operators') }}">Operators</a></li>
							<li><a href="{{ url('print_mattress_multiple') }}">Print nalog</a></li>
							<li><a href="{{ url('plan_mattress/BOARD') }}"><b><sapn style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;">Plan mattress</span></b></a></li>
							<li><a href="{{ url('plan_mini_marker') }}"><b><sapn style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;">Plan mini-mattress</span></b></a></li>
							<li><a href="{{ url('plan_paspul/BOARD') }}"><b><sapn style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;">Plan paspul</span></b></a></li>
							
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Paspul stock<span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="{{ url('paspul_stock') }}"><b><sapn style="">Paspul stock</span></b></a></li>
									<li><a href="{{ url('paspul_stock_log') }}"><b><sapn style="c">Paspul stock log</span></b></a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('paspul_req_list') }}"><b><sapn style="color: green;">Paspul request</span></b></a></li>
									<li><a href="{{ url('paspul_req_list_log') }}"><b><sapn style="color: green;">Paspul request logs</span></b></a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('paspul_remove_valy') }}"><b><sapn style="color: blue;">Remove RECEIVED_IN_VALY</span></b></a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('search_u_cons') }}"><span style="color: red;" >Paspul unitary consumption table</span></a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Search<span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="{{ url('recap_by_skeda_mattress') }}"><b><sapn style="color: gray">Mattresses by skeda</span></b></a></li>
									<li><a href="{{ url('recap_by_g_bin_mattress') }}"><b><sapn style="color: orange">Mattresses by g_bin</span></b></a></li>
									<li><a href="{{ url('recap_by_sku_sp') }}"><b><sapn style="color: green;">Mattresses by style and color on all SP</span></b></a></li>
									<li><a href="{{ url('recap_by_sku_sp0') }}"><b><sapn style="color: green;">Mattresses by style and color on SP0</span></b></a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('recap_by_skeda_paspul') }}"><b><sapn style="color: blue;">Paspuls by skeda</span></b></a></li>
									
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Phase adv<span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="{{ url('cutting_xml') }}"><b><sapn style="color: gray">Cutting XML</span></b></a></li>
									<li><a href="{{ url('cutting_bansek_xml') }}"><b><sapn style="color: orange">Bansek XML</span></b></a></li>
									<li><a href="{{ url('cutting_bansek_errors') }}"><b><sapn style="color: red">Bansek XML - errors</span></b></a></li>
								</ul>
							</li>
							<li><a href="{{ url('cpo') }}">Cut parts inspection</a></li>
							<li><a href="{{ url('fabric_reservation') }}">Fabric reservation</a></li>
							
						</ul>
					@endif

					<!-- guest -->
					@if(Auth::user()->level() == 20)
						<ul class="nav navbar-nav">
							<li><a href="{{ url('#') }}">Guest test</a></li>
						</ul>
					@endif

					<!-- SP -->
					@if(Auth::user()->level() == 10)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">SP test</a></li> -->
							<li><a href="{{ url('request_material_table') }}">Material Request table</a></li>
							

						@if (isset($operators))
						<li>
							@if (!isset($operator))
								<form class="form-inline" style="width:400px; padding: 8px;" 
								action="{{ url('operator_login') }}" method="get" >
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
	                            </form>
                            @else
                            <br>
                            <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ $operator }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ url('operator_logout')}}" class="btn btn-danger btn-xs">Logout</a></strong>
                            @endif
                        </li>
						@else
							@if(Session::has('operator'))
								<li>
									<br>
			                        <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <a href="{{ url('operator_logout')}}" class="btn btn-danger btn-xs">Logout</a></strong>
		                        </li>
	                        @endif
	                    @endif
						</ul>
					@endif

					<!-- MS -->
					@if(Auth::user()->level() == 11)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">MS test</a></li> -->
							<li><a href="{{ url('request_material_table') }}">Material Request table</a></li>
						@if (isset($operators))

						<li>
							
							@if (!isset($operator))
							<form class="form-inline" style="width:400px; padding: 8px;" name="1" action="{{ url('operator_login') }}" method="get" >
								<select name="selected_operator" class="select form-control select-form" style="width:150px !important">
	                                <option value="" selected></option>
	                        	    @foreach ($operators as $line)
	                                <option value="{{ $line->operator }}">
	                                    {{ $line->operator }}
	                            	</option>
	                            	@endforeach
	                            </select>
	                            <input type="submit" value="Login" class="btn btn-success"/>
                            </form>
                            @else
                            <form class="form-inline" style="width:400px; padding: 8px;">
	                            <span style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ $operator }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
	                            <a href="{{ url('operator_logout')}}" class="btn btn-danger">Logout</a>
                        	</form>
                            @endif
                        </li>
                        <li>
							
							@if (!isset($operator2))
							<form class="form-inline" style="width:400px; padding: 8px;" name="2" action="{{ url('operator_login2') }}" method="get" >
								<select name="selected_operator2" class="select form-control select-form" style="width:150px !important">
	                                <option value="" selected></option>
	                        	    @foreach ($operators as $line)
	                                <option value="{{ $line->operator }}">
	                                    {{ $line->operator }}
	                            	</option>
	                            	@endforeach
	                            </select>
	                            <input type="submit" value="Login" class="btn btn-success"/>
                            </form>
                            @else
                            <form class="form-inline" style="width:400px; padding: 8px;">
	                            <span style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator2 is: <b>{{ $operator2 }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
	                            <a href="{{ url('operator_logout2')}}" class="btn btn-danger">Logout</a>
                        	</form>
                            @endif
                        </li>

						@else
							@if(Session::has('operator'))
							<li>
							<div style="width:400px; padding: 8px;">
	                            <span style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
		                        <a href="{{ url('operator_logout')}}" class="btn btn-danger">Logout</a>
	                        </div>
	                        </li>
	                        @endif
	                        @if(Session::has('operator2'))
							<li>
							<div style="width:400px; padding: 8px;">
	                            <span style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator2 is: <b>{{ Session::get('operator2') }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
		                        <a href="{{ url('operator_logout2')}}" class="btn btn-danger">Logout</a>
	                        </div>
	                        </li>
	                        @endif
	                    @endif

						</ul>
					@endif

					<!-- MM -->
					@if(Auth::user()->level() == 12)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">MM test</a></li> -->

						@if (isset($operators))
						<li>
							@if (!isset($operator))
							<form class="form-inline" style="width:400px; padding: 8px;" name="1" action="{{ url('operator_login') }}" method="get" >
								<select name="selected_operator" class="select form-control select-form" style="width:150px !important">
	                                <option value="" selected></option>
	                        	    @foreach ($operators as $line)
	                                <option value="{{ $line->operator }}">
	                                    {{ $line->operator }}
	                            	</option>
	                            	@endforeach
	                            </select>
	                            <input type="submit" value="Login" class="btn btn-success"/>
                            </form>
                            @else
                            <form class="form-inline" style="width:400px; padding: 8px;">
	                            <span style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ $operator }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
	                            <a href="{{ url('operator_logout')}}" class="btn btn-danger">Logout</a>
                        	</form>
                            @endif
                        </li>
                        <li>
							
							@if (!isset($operator2))
							<form class="form-inline" style="width:400px; padding: 8px;" name="2" action="{{ url('operator_login2') }}" method="get" >
								<select name="selected_operator2" class="select form-control select-form" style="width:150px !important">
	                                <option value="" selected></option>
	                        	    @foreach ($operators as $line)
	                                <option value="{{ $line->operator }}">
	                                    {{ $line->operator }}
	                            	</option>
	                            	@endforeach
	                            </select>
	                            <input type="submit" value="Login" class="btn btn-success"/>
                            </form>
                            @else
                            <form class="form-inline" style="width:400px; padding: 8px;">
	                            <span style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator2 is: <b>{{ $operator2 }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
	                            <a href="{{ url('operator_logout2')}}" class="btn btn-danger">Logout</a>
                        	</form>
                            @endif

                        </li>
						@else
							@if(Session::has('operator'))
							<li>
							<div style="width:400px; padding: 8px;">
	                            <span style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
		                        <a href="{{ url('operator_logout')}}" class="btn btn-danger">Logout</a>
	                        </div>
	                        </li>
	                        @endif
	                        @if(Session::has('operator2'))
							<li>
							<div style="width:400px; padding: 8px;">
	                            <span style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator2 is: <b>{{ Session::get('operator2') }}</b></big></span>&nbsp;&nbsp;&nbsp;&nbsp;
		                        <a href="{{ url('operator_logout2')}}" class="btn btn-danger">Logout</a>
	                        </div>
	                        </li>
	                        @endif
	                    @endif
	                    <li><a href="{{ url('/o_roll_scan') }}">Return leftover roll</a></li>
						</ul>
					@endif

					<!-- TUB -->
					@if(Auth::user()->level() == 21)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">TUB test</a></li> -->
							<li><a href="{{ url('request_material_table') }}">Material Request table</a></li>
							<li><a href="{{ url('tubolare_weight') }}">Tubolare scan</a></li>
						@if (isset($operators))
							<li>
								@if (!isset($operator))
									<form class="form-inline" style="width:400px; padding: 8px;" 
									action="{{ url('operator_login_tub') }}" method="get" >
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
		                            </form>
                            	@else
		                            <br>
		                            <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ $operator }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
		                            <a href="{{ url('operator_logout_tub')}}" class="btn btn-danger btn-xs">Logout</a></strong>
                            	@endif
                        	</li>
						@else
							@if(Session::has('operator'))
								<li>
									<br>
			                        <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <a href="{{ url('operator_logout_tub')}}" class="btn btn-danger btn-xs">Logout</a></strong>
		                        </li>
	                        @endif
	                    @endif
						</ul>
					@endif

					<!-- LR -->
					@if(Auth::user()->level() == 13)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">LR test</a></li> -->
							@if (isset($operators))
								<li>
									@if (!isset($operator))
										<form class="form-inline" style="width:400px; padding: 8px;" 
										action="{{ url('operator_login_lr') }}" method="get" >
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
			                            </form>
		                            @else
		                            <br>
		                            <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ $operator }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
		                            <a href="{{ url('operator_logout_lr')}}" class="btn btn-danger btn-xs">Logout</a></strong>
		                            @endif
		                        </li>
							@else
								@if(Session::has('operator'))
								<li>
									<br>
			                        <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <a href="{{ url('operator_logout_lr')}}" class="btn btn-danger btn-xs">Logout</a></strong>
		                        </li>
		                        @endif
		                    @endif
						</ul>
					@endif

					<!-- PSO -->
					@if(Auth::user()->level() == 14)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">PSO test</a></li> -->
							@if (isset($operators))
								<li>
									@if (!isset($operator))	
										<form class="form-inline" style="width:400px; padding: 8px;" 
										action="{{ url('operator_login_pso') }}" method="get" >
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
			                            </form>
	                            	@else
	                            	<br>
	                            	<strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ $operator }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
	                            	<a href="{{ url('operator_logout_pso')}}" class="btn btn-danger btn-xs">Logout</a></strong>

	                            @endif
	                        </li>
							@else
								@if(Session::has('operator'))
								<li>
									<br>
			                        <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <a href="{{ url('operator_logout_pso')}}" class="btn btn-danger btn-xs">Logout</a></strong>
		                        </li>
		                        @endif
		                    @endif
						</ul>
					@endif

					<!-- PRW -->
					@if(Auth::user()->level() == 15)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">PRW test</a></li> -->
							<li><a href="{{ url('request_material_table') }}">Material Request table</a></li>

							@if (isset($operators))
								<li>
									@if (!isset($operator))
										<form class="form-inline" style="width:400px; padding: 8px;" 
										action="{{ url('operator_login_prw') }}" method="get" >
								
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
	                            		</form>
	                            	@else
	                            	<br>
	                            	<strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ $operator }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
	                            	<a href="{{ url('operator_logout_prw')}}" class="btn btn-danger btn-xs">Logout</a></strong>
	                            	
	                            	@endif
	                        </li>
							@else
								@if(Session::has('operator'))
								<li>
									<br>
			                        <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <a href="{{ url('operator_logout_prw')}}" class="btn btn-danger btn-xs">Logout</a></strong>
		                        </li>
		                        @endif
		                    @endif
						</ul>
					@endif

					<!-- PCO -->
					@if(Auth::user()->level() == 16)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">PCO test</a></li> -->
							@if (isset($operators))
								<li>
									@if (!isset($operator))
									<form class="form-inline" style="width:400px; padding: 8px;" 
									action="{{ url('operator_login_pco') }}" method="get" >
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
		                            </form>
		                            @else
		                            	<br>
			                            <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ $operator }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
			                            <a href="{{ url('operator_logout_pco')}}" class="btn btn-danger btn-xs">Logout</a></strong>
		                            @endif
	                        </li>
							@else
								@if(Session::has('operator'))
								<li>
									<br>
			                        <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <a href="{{ url('operator_logout_pco')}}" class="btn btn-danger btn-xs">Logout</a></strong>
		                        </li>
		                        @endif
		                    @endif
						</ul>
					@endif

					<!-- PACK -->
					@if(Auth::user()->level() == 17)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">PACK test</a></li> -->
							@if (isset($operators))
							<li>
								@if (!isset($operator))
									<form class="form-inline" style="width:400px; padding: 8px;" 
									action="{{ url('operator_login_pack') }}" method="get" >
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
		                        	</form>    
	                            @else
		                            <br>
		                            <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ $operator }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
		                            <a href="{{ url('operator_logout_pack')}}" class="btn btn-danger btn-xs">Logout</a></strong>
	                            @endif
	                        </li>
							@else
								@if(Session::has('operator'))
								<li>
									<br>
			                        <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <a href="{{ url('operator_logout_pack')}}" class="btn btn-danger btn-xs">Logout</a></strong>
		                        </li>
		                        @endif
		                    @endif
						</ul>
					@endif

					<!-- PLOT -->
					@if(Auth::user()->level() == 18)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">PLOT test</a></li> -->
							@if (isset($operators))
								<li>
									@if (!isset($operator))
										<form class="form-inline" style="width:400px; padding: 8px;" 
										action="{{ url('operator_login_plot') }}" method="get" >
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
			                            </form>
		                            @else
			                            <br>
			                            <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ $operator }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
			                            <a href="{{ url('operator_logout_plot')}}" class="btn btn-danger btn-xs">Logout</a></strong>
		                            @endif
	                        </li>
							@else
								@if(Session::has('operator'))
								<li>
									<br>
			                        <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <a href="{{ url('operator_logout_plot')}}" class="btn btn-danger btn-xs">Logout</a></strong>
		                        </li>
		                        @endif
		                    @endif
						</ul>
					@endif

					<!-- LEC -->
					@if(Auth::user()->level() == 19)
						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('#') }}">CUT test</a></li> -->
							@if (isset($operators))
							<li>
								@if (!isset($operator))
									<form class="form-inline" style="width:400px; padding: 8px;" 
									action="{{ url('operator_login_cut') }}" method="get" >
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
		                            </form>
	                            @else
		                            <br>
		                            <strong style="color: white; text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ $operator }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
		                            <a href="{{ url('operator_logout_cut')}}" class="btn btn-danger btn-xs">Logout</a></strong>
	                            @endif
	                        </li>
							@else
								@if(Session::has('operator'))
								<li>
									<br>
			                        <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <a href="{{ url('operator_logout_cut')}}" class="btn btn-danger btn-xs">Logout</a></strong>
		                        </li>
		                        @endif

		                    @endif
						</ul>
					@endif

					<!-- K-PREP -->
					@if(Auth::user()->level() == 22)
						<ul class="nav navbar-nav">
							<li><a href="{{ url('req_lost') }}">Request for LOST BB</a></li>
							
						</ul>
					@endif

					<!-- PSS -->
					@if(Auth::user()->level() == 23)
						<ul class="nav navbar-nav">
							<li><a href="{{ url('paspul_locations') }}">Paspul locations</a></li>
							<li><a href="{{ url('import') }}">Import paspul</a></li>
							<li><a href="{{ url('paspul_table_log/Subotica') }}">Paspul log table</a></li>
							<li><a href="{{ url('search_u_cons') }}">Paspul unitary consumption table</a></li>
							
						</ul>
					@endif

					<!-- PSK -->
					@if(Auth::user()->level() == 24)
						<ul class="nav navbar-nav">
							<li><a href="{{ url('paspul_locations') }}">Paspul locations</a></li>
							<li><a href="{{ url('import') }}">Import paspul</a></li>
							<li><a href="{{ url('paspul_table_log/Kikinda') }}">Paspul log table</a></li>
							<li><a href="{{ url('search_u_cons') }}">Paspul unitary consumption table</a></li>
							
						</ul>
					@endif

					<!-- PSZ -->
					@if(Auth::user()->level() == 25)
						<ul class="nav navbar-nav">
							<li><a href="{{ url('paspul_locations') }}">Paspul locations</a></li>
							<li><a href="{{ url('import') }}">Import paspul</a></li>
							<li><a href="{{ url('paspul_table_log/Senta') }}">Paspul log table</a></li>
							<li><a href="{{ url('search_u_cons') }}">Paspul unitary consumption table</a></li>
							
						</ul>
					@endif

					<!-- WHS -->
					@if(Auth::user()->level() == 26)
						<ul class="nav navbar-nav">
							<li><a href="{{ url('paspul_table_log/WHSU') }}">Paspul log table</a></li>
							
						</ul>
					@endif

					<!-- FO -->
					@if(Auth::user()->level() == 27)

						<ul class="nav navbar-nav">
							<!-- <li><a href="{{ url('request_material_table') }}">Material Request table</a></li> -->
							@if (isset($operators))
							<li>
								@if (!isset($operator))
									<form class="form-inline" style="width:400px; padding: 8px;" 
									action="{{ url('operator_login_fo') }}" method="get" >
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
		                            </form>
	                            @else
		                            <br>
		                            <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ $operator }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
		                            <a href="{{ url('operator_logout_fo')}}" class="btn btn-danger btn-xs">Logout</a></strong>
	                            @endif
	                            
	                        </li>
							@else
								@if(Session::has('operator'))
								<li>
									<br>
			                        <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <a href="{{ url('operator_logout_fo')}}" class="btn btn-danger btn-xs">Logout</a></strong>
		                        </li>
		                        @endif
		                    @endif
						</ul>
					@endif

					<!-- CPO -->
					@if(Auth::user()->level() == 28)
						<ul class="nav navbar-nav">
							<li><a href="{{ url('cpo') }}">G_bin statuses</a></li>
							<li><a href="{{ url('cpo_scan') }}">New g_bin check</a></li>
							&nbsp;&nbsp;&nbsp;
							@if (isset($operators))
							<li>
								@if (!isset($operator))
									<form class="form-inline" style="width:400px; padding: 8px;" 
									action="{{ url('operator_login_cpo') }}" method="get" >
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
		                            </form>
	                            @else
		                            <br>
		                            <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ $operator }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
		                            <a href="{{ url('operator_logout_cpo')}}" class="btn btn-danger btn-xs">Logout</a></strong>
	                            @endif
	                        </li>
							@else
								@if(Session::has('operator'))
								<li>
									<br>
			                        <strong style="color: white;text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;"><big>Operator is: <b>{{ Session::get('operator') }}</b></big>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <a href="{{ url('operator_logout_cpo')}}" class="btn btn-danger btn-xs">Logout</a></strong>
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
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span style="font-weight:500; font-size:large;  text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;">{{ Auth::user()->name }}</span> <span class="caret"></span></a>
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
    	
	<!-- <script src="{{ asset('/js/jquery.dataTables.min.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/jquery.tablesorter.min.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/custom.js') }}" type="text/javascript" ></script>-->
	<script src="{{ asset('/js/tableExport.js') }}" type="text/javascript" ></script>
	<!--<script src="{{ asset('/js/jspdf.plugin.autotable.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/jspdf.min.js') }}" type="text/javascript" ></script>-->
	<script src="{{ asset('/js/FileSaver.min.js') }}" type="text/javascript" ></script>
	
	<script src="{{ asset('/js/choosen.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('/js/select2.min.js') }}" type="text/javascript" ></script>
	
	<!-- // <script src="{{ asset('/js/bootstrap-table-export.js') }}" type="text/javascript" ></script> -->
	<!-- // <script src="{{ asset('/js/bootstrap-table.js') }}" type="text/javascript" ></script> -->
	<script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript" ></script>
    
    

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
  	
	// $('#sort').bootstrapTable({
    	
	// });

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
<script type="text/javascript">
		$(document).ready(function() {
			
			$("#select2").select2({
			  
			});
			$("#select3").select2({
			  
			});
			$("#select4").select2({
			  
			});
			$("#select5").select2({
			  
			});
			$("#select6").select2({
			  
			});
		});
</script>
<script>
  $(document).ready(function() {

  	$("#sortable0 ,#sortable1 , #sortable2 , #sortable3 , #sortable4 , #sortable5, #sortable6, #sortable7, #sort_able8, #sort_able9, #sortable_p_1, #sortable_p_2" ).sortable({
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
	
	$('#sortable0').sortable({
        // axis: 'y',
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            // console.log("position changed 2");
            // console.log(data);
            // POST to server using $.post or $.ajax
          	  $.ajax({
          	      data: data,
          	      type: 'POST',
          	      url: '{{ route('posts.reposition0') }}'
         	});
    	}
    });

    $('#sortable2').sortable({
        // axis: 'y',
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            // console.log("position changed 2");
            // console.log(data);
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
            // console.log("position changed 3");
            // console.log(data);
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
            // console.log("position changed 4");
            // console.log(data);
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
            // console.log("position changed 5");
            // console.log(data);
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
            // console.log("position changed 6");
            // console.log(data);
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
            // console.log("position changed 7");
            // console.log(data);
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
            // console.log("position changed 8");
            // console.log(data);
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
            // console.log("position changed 9");
            // console.log(data);
            // POST to server using $.post or $.ajax
          	  $.ajax({
          	      data: data,
          	      type: 'POST',
          	      url: '{{ route('posts.reposition9') }}'
         	});
    	}
    });
    $('#sortable91').sortable({
        // axis: 'y',
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            // console.log("position changed 9");
            // console.log(data);
            // POST to server using $.post or $.ajax
          	 
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

    $('#sortable_p_1').sortable({
        // axis: 'y',
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            console.log("position changed p 1");
            // console.log(data);
            // POST to server using $.post or $.ajax
          	  $.ajax({
          	      data: data,
          	      type: 'POST',
          	      url: '{{ route('posts.reposition_p_1') }}'
         	});
    	}
    });

    $('#sortable_p_2').sortable({
    	
   		update: function (event, ui) {
            var data = $(this).sortable('serialize');
            console.log("position changed p 2");
            // console.log(data);
            // POST to server using $.post or $.ajax
          	$.ajax({
          	      data: data,
          	      type: 'POST',
          	      url: '{{ route('posts.reposition_p_2') }}'
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

	$("#checkAll").click(function () {
    	$(".check").prop('checked', $(this).prop('checked'));
	});
	
	$(".sortable2 ul:nth-child(2) li").each(function(index) {
  		console.log("trdt");
	});

	// In your Javascript (external .js resource or <script> tag)
	$(document).ready(function() {
		$('.js-example-basic-single').select2();
	});


});
</script>
<script>
	function goBack() {
	  window.history.back();
	}
</script>


</body>
</html>
