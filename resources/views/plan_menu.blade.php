@section('plan_menu')
				<div class="btn-group plan_menu" width="width=100%">

				 <a href="{{ url('plan_mattress/DELETED')}}" class="btn btn-default 
				 @if ($location == 'DELETED') plan_menu_a @endif "
				 ><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>

				 <a href="{{ url('plan_mattress/NOT_SET')}}" class="btn btn-default
				 @if ($location == 'NOT_SET') plan_menu_a @endif "
				 >NOT SET</a>
				 <a href="{{ url('plan_mattress/SP1') }}" class="btn btn-primary
				 @if ($location == 'SP1') plan_menu_a @endif "
				 >SP1</a>
				 <a href="{{ url('plan_mattress/SP2') }}" class="btn btn-primary
				 @if ($location == 'SP2') plan_menu_a @endif "
				 >SP2</a>
				 <a href="{{ url('plan_mattress/SP3') }}" class="btn btn-primary
				 @if ($location == 'SP3') plan_menu_a @endif "
				 >SP3</a>

				 <a href="{{ url('plan_mattress/MS1') }}" class="btn btn-success
				 @if ($location == 'MS1') plan_menu_a @endif "
				 >MS1</a>
				 <a href="{{ url('plan_mattress/MS2') }}" class="btn btn-success
				 @if ($location == 'MS2') plan_menu_a @endif "
				 >MS2</a>
				 <a href="{{ url('plan_mattress/MS3') }}" class="btn btn-success
				 @if ($location == 'MS3') plan_menu_a @endif "
				 >MS3</a>

				 <a href="{{ url('plan_mattress/MM1') }}" class="btn btn-info
				 @if ($location == 'MM1') plan_menu_a @endif "
				 >MM1</a>

				 <a href="{{ url('plan_mattress/LR') }}" class="btn btn-default
				 @if ($location == 'LR') plan_menu_a @endif "
				 >LR</a>
				 <a href="{{ url('plan_mattress/PLOT') }}" class="btn btn-default
				 @if ($location == 'PLOT') plan_menu_a @endif "
				 >PLOT</a>

				 <a href="{{ url('plan_mattress/LEC1') }}" class="btn btn-warning
				 @if ($location == 'LEC1') plan_menu_a @endif "
				 >LEC1</a>
				 <a href="{{ url('plan_mattress/LEC2') }}" class="btn btn-warning
				 @if ($location == 'LEC2') plan_menu_a @endif "
				 >LEC2</a>

				 <a href="{{ url('plan_mattress/PACK') }}" class="btn btn-danger
				 @if ($location == 'PACK') plan_menu_a @endif "
				 >PACK</a>
				 <a href="{{ url('plan_mattress/PSO') }}" class="btn btn-danger
				 @if ($location == 'PSO') plan_menu_a @endif "
				 >PSO</a>

				  <a href="{{ url('plan_mattress/PRW') }}" class="btn btn-default
				 @if ($location == 'PRW') plan_menu_a @endif "
				 >PRW</a>
				 <a href="{{ url('plan_mattress/PCO') }}" class="btn btn-default
				 @if ($location == 'PCO') plan_menu_a @endif "
				 >PCO</a>
				</div>
@endsection