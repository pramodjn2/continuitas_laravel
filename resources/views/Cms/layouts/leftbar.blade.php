<!-- sidebar menu -->

<div id="sidebar" class="sidebar" >
  <div class="tabbable-panel">
    <div class="tabbable-line">
      <ul class="nav nav-tabs nav-justified">
        <li id="tab_menu_a" class="active"> <a href="#tab_menu_1" data-toggle="tab"> <i class="fa fa-reorder"></i> </a> </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_menu_1">
          <form class="search-menu-form" >
            <div class="">
              <input id="menu-list-search" placeholder="" type="text" class="form-control search-menu">
            </div>
          </form>
          
          <!-- sidebar Menu -->
          <div id="MainMenu" class="">
            <ul id="menu-list" class="nav nav-list">
              <?php  
			  
			  function inarray($array, $str){
				  if (in_array($str, $array)) {
						return true;
					}
					return false;
				 }
		       ?>
             
              <li class="<?php if($className == 'dashboard'){ echo 'open active'; }?>"> <a href="{{url('cms/dashboard')}}"> <i class="menu-icon fa fa-tachometer"></i> <span class="menu-text"> {{trans('common.dashboard')}} </span> </a> <b class="arrow"></b> </li>
               <?php 
			  $mclass = array("profile", "editProfile", "changePassword");
			  $rdata = inarray($mclass, $className);  
			  ?>
              <li class="<?php if($rdata){ echo 'open active'; }?>"> <a href="#" class="dropdown-toggle"> 
              <i class="menu-icon fa fa-user"></i> <span class="menu-text"> {{trans('common.profile')}} </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
                <ul class="submenu nav-show"  >
                  <li class="<?php if($className == 'profile') { echo 'active';} ?>"> <a href="{{url('cms/profile')}}" > <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.myProfile')}}</span> </a> <b class="arrow"></b> </li>
                  <li class="<?php if($className == 'editProfile') { echo 'active';} ?>" > <a href="{{url('cms/editProfile')}}"> <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.editProfile')}}</span> </a> <b class="arrow"></b> </li>                 
                  <li class="<?php if($className == 'changePassword') { echo 'active';} ?>" > <a href="{{url('cms/changePassword')}}"> <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.changePassword')}}</span> </a> <b class="arrow"></b> </li>
                </ul>
              </li>            
              <?php 
			  $mclass = array("user", "role", "permission");
			  $rdata = inarray($mclass, $className);
			  ?>
              <li class="<?php if($rdata){ echo 'open active'; }?>"> <a href="#" class="dropdown-toggle"> <i class="menu-icon glyphicon glyphicon-list"></i> <span class="menu-text"> {{trans('common.administration')}} </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
                <ul class="submenu nav-show">
                  <li class="<?php if($className == 'user') { echo 'active';} ?>"> <a href="{{url('cms/user')}}" > <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.users')}}</span> </a> <b class="arrow"></b> </li>
                  <li class="<?php if($className == 'role') { echo 'active';} ?>" > <a href="{{url('cms/role')}}"> <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.roles')}}</span> </a> <b class="arrow"></b> </li>
                 <?php  if(session('role_id') == 8){ ?>
                  <li class="<?php if($className == 'permission') { echo 'active';} ?>" > <a href="{{url('cms/permission')}}"> <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.permissions')}}</span> </a> <b class="arrow"></b> </li>
                  <?php } ?>
                  <!-- <li ><a href="tab.html"> <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.mainClass')}} </span> </a> <b class="arrow"></b> </li> -->
                </ul>
              </li>
              <?php 
			  $mclass = array("client", "company","manager","people","news","event","family","message","pushnotification");
			  $rdata = inarray($mclass, $className);
			  ?>
              <li class="<?php if($rdata){ echo 'open active'; }?>"> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-file-o"></i> <span class="menu-text"> {{trans('common.appCustomerInfo')}} </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
                <ul class="submenu nav-show"  >
                    
                   
                   
                    <li class="<?php if($className == 'manager') { echo 'active';} ?>"> <a href="{{url('cms/manager')}}"> <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.relationManager')}}</span> </a> <b class="arrow"></b> </li>
                     <li class="<?php if($className == 'company') { echo 'active';} ?>"> <a  href="{{url('cms/company')}}"> <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.company')}}</span> </a> <b class="arrow"></b> </li>
                     <li class="<?php if($className == 'client') { echo 'active';} ?>"> <a href="{{url('cms/client')}}" > <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.clients')}}</span> </a> <b class="arrow"></b> </li>
                     
                    
                    <li class="<?php if($className == 'people') { echo 'active';} ?>"> <a href="{{url('cms/people')}}"> <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.ourPeople')}}</span> </a> <b class="arrow"></b> </li>
                    <li class="<?php if($className == 'news') { echo 'active';} ?>" > <a href="{{url('cms/news')}}"> <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.news')}}</span> </a> <b class="arrow"></b> </li>
                    
                    
                    
                    <li class="<?php if($className == 'event') { echo 'active';} ?>"  > <a href="{{url('/cms/event')}}"> <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.event')}}</span> </a> <b class="arrow"></b> </li>
                    <!--<li class="<?php if($className == 'event') { echo 'active';} ?>" > <a href="{{url('/cms/user')}}"> <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.users')}}</span> </a> <b class="arrow"></b> </li>-->
                    
                    <!-- <li class="<?php if($className == 'family') { echo 'active';} ?>" > <a href="{{url('/cms/family')}}"> <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.familiestatuut')}}</span> </a> <b class="arrow"></b> </li> -->
			<li class="<?php if($className == 'message') { echo 'active';} ?>"  > <a href="{{url('/cms/message')}}"> <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.message')}}</span> </a> <b class="arrow"></b> </li>
            
            <li class="<?php if($className == 'pushnotification') { echo 'active';} ?>"  > <a href="{{url('/cms/pushnotification')}}"> <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">Push Notification</span> </a> <b class="arrow"></b> </li>
            
		            </ul>
              </li>
              
              
               <?php 
			  $mclass = array("client_fm", "company_fm","manager_fm");
			  $rdata = inarray($mclass, $className);  
			  ?>
              <li class="<?php if($rdata){ echo 'open active'; }?>"> <a href="#" class="dropdown-toggle"> 
              <i class="menu-icon fa fa-user"></i> <span class="menu-text"> {{trans('common.Familiestatuut')}}  </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
                <ul class="submenu nav-show"  >
                  <li class="<?php if($className == 'client_fm') { echo 'active';} ?>"> <a href="{{url('cms/client_fm')}}" > <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.clients')}} Fm</span> </a> <b class="arrow"></b> </li>
                    <li class="<?php if($className == 'company_fm') { echo 'active';} ?>"> <a  href="{{url('cms/company_fm')}}"> <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.company')}} Fm</span> </a> <b class="arrow"></b> </li>
                    <li class="<?php if($className == 'manager_fm') { echo 'active';} ?>"> <a href="{{url('cms/manager_fm')}}"> <i class="menu-icon fa fa-caret-right"></i> <span class="menu-text">{{trans('common.relationManager')}} Fm</span> </a> <b class="arrow"></b> </li>
                </ul>
              </li>   
              
              
            </ul>
            <a class="sidebar-collapse" id="sidebar-collapse" data-toggle="collapse" data-target="#test"> <i id="icon-sw-s-b" class="fa fa-angle-double-left"></i> </a> </div>
        </div>
      </div>
      <!-- end tab-content--> 
    </div>
    <!-- end tabbable-line --> 
  </div>
  <!-- end tabbable-panel --> 
</div>
<!-- /end #sidebar -->