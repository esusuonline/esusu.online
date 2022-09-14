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
                <li class="sidebar-menu-item {{ menuActive('staff.dashboard') }}">
                    <a href="{{ route('staff.dashboard') }}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('staff.loan*',3)}}">
                        <i class="menu-icon las la-comment-dollar"></i>
                        <span class="menu-title">@lang('Loan') </span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('staff.loan*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive(['staff.loan.plan', 'staff.loan.apply'])}} ">
                                <a href="{{route('staff.loan.plan')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Apply for loan')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('staff.loan.pending')}} ">
                                <a href="{{route('staff.loan.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('staff.loan.active')}} ">
                                <a href="{{route('staff.loan.active')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Active')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('staff.loan.paid')}} ">
                                <a href="{{route('staff.loan.paid')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Paid')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('staff.loan.closed')}} ">
                                <a href="{{route('staff.loan.closed')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Closed')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('staff.loan.all')}} ">
                                <a href="{{route('staff.loan.all')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All')</span>
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('staff.savings*',3)}}">
                        <i class="menu-icon las la-coins"></i>
                        <span class="menu-title">@lang('Savings') </span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('staff.savings*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive(['staff.savings.plan', 'staff.savings.apply'])}} ">
                                <a href="{{route('staff.savings.plan')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Apply for savings')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('staff.savings.pending')}} ">
                                <a href="{{route('staff.savings.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('staff.savings.active')}} ">
                                <a href="{{route('staff.savings.active')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Active')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('staff.savings.paid')}} ">
                                <a href="{{route('staff.savings.paid')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Paid')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('staff.savings.closed')}} ">
                                <a href="{{route('staff.savings.closed')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Closed')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('staff.savings.all')}} ">
                                <a href="{{route('staff.savings.all')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('staff.payment*',3)}}">
                        <i class="menu-icon las la-history"></i>
                        <span class="menu-title">@lang('Payment History') </span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('staff.payment*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('staff.payment.history')}} ">
                                <a href="{{route('staff.payment.history')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('staff.payment.loan.history')}} ">
                                <a href="{{route('staff.payment.loan.history')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Loan')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('staff.payment.savings.history')}} ">
                                <a href="{{route('staff.payment.savings.history')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Savings')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('staff.collection*',3)}}">
                        <i class="menu-icon las la-credit-card"></i>
                        <span class="menu-title">@lang('Collection')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('staff.collection*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('staff.collection.loan.pending')}} ">
                                <a href="{{route('staff.collection.loan.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending Loan')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('staff.collection.savings.pending')}} ">
                                <a href="{{route('staff.collection.savings.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending Savings')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('staff.collection.loan.paid')}} ">
                                <a href="{{route('staff.collection.loan.paid')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Paid Loan')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('staff.collection.savings.paid')}} ">
                                <a href="{{route('staff.collection.savings.paid')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Paid Savings')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('staff.ticket*',3)}}">
                        <i class="menu-icon las la-envelope"></i>
                        <span class="menu-title">@lang('Support Ticket')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('staff.ticket*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('staff.ticket.open')}} ">
                                <a href="{{route('staff.ticket.open')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Open Ticket')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('staff.ticket')}} ">
                                <a href="{{route('staff.ticket')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('My Tickets')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item {{ menuActive('staff.profile') }}">
                    <a href="{{ route('staff.profile') }}" class="nav-link ">
                        <i class="menu-icon las la-user"></i>
                        <span class="menu-title">@lang('Profile')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('staff.twofactor') }}">
                    <a href="{{ route('staff.twofactor') }}" class="nav-link ">
                        <i class="menu-icon las la-shield-alt"></i>
                        <span class="menu-title">@lang('2FA Security')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('staff.change.password') }}">
                    <a href="{{ route('staff.change.password') }}" class="nav-link ">
                        <i class="menu-icon las la-lock"></i>
                        <span class="menu-title">@lang('Chnage Password')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('staff.logout') }}">
                    <a href="{{ route('staff.logout') }}" class="nav-link ">
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
        <span class="dropdown-menu__caption mr-3 text-dark">@lang('Withdrawable Funds') <br> <span class="float-right text-dark">(&#8358; {{ number_format(Auth::guard('staff')->user()->withdrawable_funds, 2) }})</span></span>  </a>
        <div class="dropdown-divider"></div> 
        
        <a class="dropdown-item" href="#"><i style="color: #E91E63" class="dropdown-menu__icon fas fa-coins mr-2"></i>
        <span class="dropdown-menu__caption mr-3 text-dark">@lang('Total Assets') <br> <span class="float-right text-dark">(&#8358; {{ number_format(Auth::guard('staff')->user()->total_assets, 2) }}) <i class="fas fa-lock ml-1"></i></span></span></a>
        <div class="dropdown-divider"></div>
        
        <a class="dropdown-item" href="#"><i style="color: #E91E63" class="dropdown-menu__icon fas fa-wallet mr-2"></i>
        <span class="dropdown-menu__caption mr-3 text-dark">@lang('Active Savings Balance') <br> <span class="float-right text-dark">(&#8358; {{ number_format(Auth::guard('staff')->user()->active_savings_balance, 2) }}) <i class="fas fa-lock ml-1"></i></span></span></a>

      </div>
    </div>

    <div class="navbar__right">
        <a href="{{ route('staff.profile') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2 border-0">
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
