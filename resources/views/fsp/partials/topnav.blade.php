<style>

    .topnav {
        display: none;
    }
    
    @media(max-width: 992px){
        .topnav {
            display: block;
        }
        
        .topnav a {
          text-decoration: none;
          display: block;
        }
    
        #myLinks a{
            margin-bottom: 20px;
        }
        
        #icon-menu-close {
            display: none;
        }
    }

</style>

<!-- navbar-wrapper start -->
<nav class="navbar-wrapper">
    
    <div class="topnav py-2">
      <!--<a href="#home" class="active">Logo</a>-->
      
      <a href="javascript:void(0);" class="icon" onclick="myFunction()">
        <i id="icon-menu-open" class="fa fa-bars"></i> <i id="icon-menu-close" class="fa fa-times"></i>
      </a>

      <div class="topnav mt-4" id="myLinks" style="display: none">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{ menuActive('fsp.dashboard') }}">
                    <a href="{{ route('fsp.dashboard') }}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>
                
                
                <li class="sidebar-menu-item {{ menuActive('fsp.dashboard') }}">
                    <a href="{{ route('fsp.plan.loan.index') }}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Manage Loan Plans')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('fsp.loan*',3)}}">
                        <i class="menu-icon las la-comment-dollar"></i>
                        <span class="menu-title">@lang('Business Loans') </span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('fsp.loan*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.pending')}} ">
                                <a href="{{route('fsp.loan.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending Loan Requests')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.active')}} ">
                                <a href="{{route('fsp.loan.active')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Active Loans')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.closed')}} ">
                                <a href="{{route('fsp.loan.closed')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Closed Loans')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.paid')}} ">
                                <a href="{{route('fsp.loan.paid')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Paid Loans')</span>
                                </a>
                            </li>
                            
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.all')}} ">
                                <a href="{{route('fsp.loan.all')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All Loans')</span>
                                </a>
                            </li>
                            
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.all')}} ">
                                <a href="{{route('fsp.loan.all')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Loan Disburse History')</span>
                                </a>
                            </li>
                            
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.all')}} ">
                                <a href="{{route('fsp.loan.all')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Loan Repay History')</span>
                                </a>
                            </li>
                            
                            
                            
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('fsp.loan*',3)}}">
                        <i class="menu-icon las la-comment-dollar"></i>
                        <span class="menu-title">@lang('Consumer Loans') </span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('fsp.loan*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.pending')}} ">
                                <a href="{{route('fsp.loan.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending Loan Requests')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.active')}} ">
                                <a href="{{route('fsp.loan.active')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Active Loans')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.closed')}} ">
                                <a href="{{route('fsp.loan.closed')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Closed Loans')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.paid')}} ">
                                <a href="{{route('fsp.loan.paid')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Paid Loans')</span>
                                </a>
                            </li>
                            
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.all')}} ">
                                <a href="{{route('fsp.loan.all')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All Loans')</span>
                                </a>
                            </li>
                            
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.all')}} ">
                                <a href="{{route('fsp.loan.all')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Loan Disburse History')</span>
                                </a>
                            </li>
                            
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.all')}} ">
                                <a href="{{route('fsp.loan.all')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Loan Repay History')</span>
                                </a>
                            </li>
                            
                            
                            
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('fsp.collection*',3)}}">
                        <i class="menu-icon las la-credit-card"></i>
                        <span class="menu-title">@lang('Agents Collection')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('fsp.collection*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('fsp.collection.loan.pending')}} ">
                                <a href="{{route('fsp.collection.loan.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending Loans')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('fsp.collection.loan.paid')}} ">
                                <a href="{{route('fsp.collection.loan.paid')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Paid Loans')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('fsp.ticket*',3)}}">
                        <i class="menu-icon las la-envelope"></i>
                        <span class="menu-title">@lang('Support Ticket')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('fsp.ticket*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('fsp.ticket.open')}} ">
                                <a href="{{route('fsp.ticket.open')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Open Ticket')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('fsp.ticket')}} ">
                                <a href="{{route('fsp.ticket')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('My Tickets')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item {{ menuActive('fsp.profile') }}">
                    <a href="{{ route('fsp.profile') }}" class="nav-link ">
                        <i class="menu-icon las la-user"></i>
                        <span class="menu-title">@lang('Profile')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('fsp.twofactor') }}">
                    <a href="{{ route('fsp.twofactor') }}" class="nav-link ">
                        <i class="menu-icon las la-shield-alt"></i>
                        <span class="menu-title">@lang('2FA Security')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('fsp.change.password') }}">
                    <a href="{{ route('fsp.change.password') }}" class="nav-link ">
                        <i class="menu-icon las la-lock"></i>
                        <span class="menu-title">@lang('Change Password')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('fsp.logout') }}">
                    <a href="{{ route('fsp.logout') }}" class="nav-link ">
                        <i class="menu-icon las la-sign-out-alt"></i>
                        <span class="menu-title">@lang('logout')</span>
                    </a>
                </li>
            </ul>
      </div>

    </div>
    <form class="navbar-search" onsubmit="return false;">
        <button type="submit" class="navbar-search__btn">
            <i class="las la-search"></i>
        </button>
        <input type="search" name="navbar-search__field" id="navbar-search__field" placeholder="@lang('Search')">
        <button type="button" class="navbar-search__close"><i class="las la-times"></i></button>

        <div id="navbar_search_result_area">
            <ul class="navbar_search_result"></ul>
        </div>
    </form>
    
    <div class="dropdown">
      <button style="background-color: #7367f0; color: white" class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
        Wallets
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="#"><i style="color: #E91E63" class="dropdown-menu__icon fas fa-hand-holding-usd mr-2"></i>
        <span class="dropdown-menu__caption mr-3 text-dark">@lang('Withdrawable Funds') <br> <span class="float-right text-dark">(&#8358; {{ number_format(Auth::guard('fsp')->user()->withdrawable_funds, 2) }})</span></span>  </a>
        <div class="dropdown-divider"></div> 
        
        <a class="dropdown-item" href="#"><i style="color: #E91E63" class="dropdown-menu__icon fas fa-coins mr-2"></i>
        <span class="dropdown-menu__caption mr-3 text-dark">@lang('Total Assets') <br> <span class="float-right text-dark">(&#8358; {{ number_format(Auth::guard('fsp')->user()->total_assets, 2) }}) <i class="fas fa-lock ml-1"></i></span></span></a>
        <div class="dropdown-divider"></div>

      </div>
    </div>

    <div class="navbar__right">
        <a href="{{ route('fsp.profile') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2 border-0">
            <i class="dropdown-menu__icon las la-user-circle"></i>
            <span class="dropdown-menu__caption">@lang('Profile')</span>
        </a>
    </div>
</nav>
<!-- navbar-wrapper end -->

<script>
    function myFunction() {
      var x = document.getElementById("myLinks");
      var y = document.getElementById("icon-menu-open");
      var z = document.getElementById("icon-menu-close");
      if (x.style.display === "block") {
        x.style.display = "none";
        y.style.display = "block";
        z.style.display = "none";
      } else {
        x.style.display = "block";
        y.style.display = "none";
        z.style.display = "block";
        
      }
}
</script>
