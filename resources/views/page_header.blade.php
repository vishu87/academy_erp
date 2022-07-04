@if(Auth::check())

	<?php $access_rights = Session::get("access_rights"); ?>
	<div class="top-menu">
		<div class="row">
			<div class="col-8 ">
				<div class="table-div">
					<div>
						<div class="toggle-menu-cont">
							<a class="toggle-menu" href="javascript:;">
								<i></i>
								<i></i>
								<i></i>
						    </a>
						</div>
					</div>
					<div>
						@if(Auth::user()->user_type == 1)
						<div class="switch-tab">
				            <a href="javascript:;" class="main-link">
				            	{{ ucwords($menu) }} <i class="icons icon-arrow-down"></i>
				            </a>
				            <ul>
				            	@if(isset($access_rights["academy"]))
				            	<li>
				                    <a href="{{url('switch/dashboard/academy')}}"><span>Academy</span></a>
				                </li>
				                @endif
				                @if(isset($access_rights["accounts"]))
				                <li>
				                    <a href="{{url('switch/dashboard/accounts')}}"><span>Accounts</span></a>
				                </li>
				                @endif
				                @if(isset($access_rights["leads"]))
				                <li>
				                    <a href="{{url('switch/dashboard/leads')}}"><span>Leads</span></a>
				                </li>
				                @endif
				                @if(isset($access_rights["admin"]))
				                <li>
				                    <a href="{{url('switch/dashboard/admin')}}"><span>Admin</span></a>
				                </li>
				                @endif
				                @if(isset($access_rights["inventory"]))
				                <li>
				                    <a href="{{url('switch/dashboard/inventory')}}"><span>Inventory</span></a>
				                </li>
				                @endif
				                @if(isset($access_rights["communication"]))
				                <li>
				                    <a href="{{url('switch/dashboard/communication')}}"><span>Communication</span></a>
				                </li>
				                @endif
				            </ul>
				        </div>
				        @else
				        	<div class="switch-tab">
					        	<a href="javascript:;" class="main-link">
					            	Parent Dashboard
					            </a>
					        </div>
				        @endif
		        	</div>

		        	<!-- <div>
						<div class="switch-tab" style="margin-left: 10px;">
				            <a href="javascript:;" class="main-link">
				            	All Sports <i class="icons icon-arrow-down"></i>
				            </a>
				            <ul>
				            	
				            </ul>
				        </div>
		        	</div> -->
				</div>
			</div>
			<div class="col-4 text-right">
				<div class="welcome-nav">
					<span class="name">
						{{ Auth::user()->name }}
					</span>
					<div class="menu">
						<ul>
							<li>
								<a href="{{url('/update-password')}}"><i class="icons icon-lock-open"></i> <span>Change Password</span></a>
							</li>

							<li>
								<a href="{{url('/logout')}}"><i class="icons icon-logout"></i> <span>Logout</span></a>
							</li>
						</ul>
					</div>
				</div>
		<!-- 		Welcome,  -->
			</div>
		</div>
	</div>
@endif