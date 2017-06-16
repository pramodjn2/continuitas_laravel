    <header id="header">
				<nav class="navbar navbar-default nopadding" >
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>						
						<button type="button" id="menu-open" class="navbar-toggle menu-toggler pull-left">
							<span class="sr-only">Toggle sidebar</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="#" id="logo-panel">
							<img src="{{url('assets/img/logo.png')}}" alt="Golabi Admin">
						</a>						
						
						
					</div>
					<form action="#" class="form-search-mobile pull-right">
						<input id="search-fld" class="search-mobile" type="text" name="param" placeholder="Search ...">
						<button id="submit-search-mobile" type="submit">
							<i class="fa fa-search"></i>
						</button>
						<a href="#" id="cancel-search-mobile" title="Cancel Search"><i class="fa fa-times"></i></a>
					</form>
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						
						
						<ul class="nav navbar-nav navbar-right">
							<li id="search-show-li" class="dropdown">
								<a href="#" id="search-mobile-show" class="dropdown-toggle" >
									<i class="fa fa-search"></i>
								</a>
							</li>
							
							
							<li class="dropdown">
								
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<img title="{{session('fullName')}}" alt="{{session('fullName')}}" src="{{url('uploads/'.session('avtar'))}}" height="50" width="50" class="img-circle" />
                                    {{session('fullName')}}
									<strong class="caret"></strong>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="{{url('cms/profile')}}"><span class="fa fa-user pull-right"></span>{{trans('common.myProfile')}}</a>
									</li>
                                   
                                    <li> 
										<a href="{{url('cms/editProfile')}}"><span class="fa fa-pencil-square-o pull-right"></span>{{trans('common.editProfile')}}</a>
									</li>
                                    
                                    <li>
										<a href="{{url('cms/changePassword')}}">{{trans('common.changePassword')}}</a>
									</li>
                                    
                                    
									
									<li class="divider">
									</li>
									<li>
										<a href="{{url('cms/logout')}}"><span class="fa fa-power-off pull-right"></span>{{trans('common.signOut')}}</a>
									</li>
								</ul>
							</li>

						</ul>
						
						<ul class="nav navbar-nav navbar-right">

							<li id="fullscreen-li">
								<a href="#" id="fullscreen" class="dropdown-toggle" >
									<i class="fa fa-arrows-alt"></i>
								</a>
							</li>
							
							<li id="side-hide-li" class="dropdown">
								<a href="#" id="side-hide" class="dropdown-toggle" >
									<i class="fa fa-reorder"></i>
								</a>
							</li>

							
						</ul>
						<!-- search form in header -->
						
						
						
					</div>
					
				</nav>
			</header>