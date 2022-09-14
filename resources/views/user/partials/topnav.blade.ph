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
                <li class="sidebar-menu-item {{menuActive('user.dashboard')}}">
                    <a href="{{route('user.dashboard')}}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>
                
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('user.savings*',3)}}">
                        <i class="menu-icon las la-coins"></i>
                        <span class="menu-title">@lang('Savings') </span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('user.savings*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('user.savings.plan')}} ">
                                <a href="{{route('user.savings.plan')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Apply for savings')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('user.savings.pending')}} ">
                                <a href="{{route('user.savings.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('user.savings.active')}} ">
                                <a href="{{route('user.savings.active')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Active')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('user.savings.paid')}} ">
                                <a href="{{route('user.savings.paid')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Paid')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('user.savings.closed')}} ">
                                <a href="{{route('user.savings.closed')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Closed')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('user.savings.all')}} ">
                                <a href="{{route('user.savings.all')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('user.loan*',3)}}">
                        <i class="menu-icon las la-comment-dollar"></i>
                        <span class="menu-title">@lang('Loan') </span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('user.loan*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('user.loan.plan')}} ">
                                <a href="{{route('user.loan.plan')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Apply for loan')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('user.loan.pending')}} ">
                                <a href="{{route('user.loan.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('user.loan.active')}} ">
                                <a href="{{route('user.loan.active')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Active')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('user.loan.paid')}} ">
                                <a href="{{route('user.loan.paid')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Paid')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('user.loan.closed')}} ">
                                <a href="{{route('user.loan.closed')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Closed')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('user.loan.all')}} ">
                                <a href="{{route('user.loan.all')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('user.payment*',3)}}">
                        <i class="menu-icon las la-history"></i>
                        <span class="menu-title">@lang('Payment History') </span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('user.payment*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('user.payment.history')}} ">
                                <a href="{{route('user.payment.history')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('user.payment.loan.history')}} ">
                                <a href="{{route('user.payment.loan.history')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Loan')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('user.payment.savings.history')}} ">
                                <a href="{{route('user.payment.savings.history')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Savings')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('ticket*',3)}}">
                        <i class="menu-icon las la-envelope"></i>
                        <span class="menu-title">@lang('Support Ticket')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('ticket*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('ticket.open')}} ">
                                <a href="{{route('ticket.open')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Open Ticket')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('ticket')}} ">
                                <a href="{{route('ticket')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('My Tickets')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item {{ menuActive('user.profile.setting') }}">
                    <a href="{{ route('user.profile.setting') }}" class="nav-link ">
                        <i class="menu-icon las la-user"></i>
                        <span class="menu-title">@lang('Profile')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('user.twofactor') }}">
                    <a href="{{ route('user.twofactor') }}" class="nav-link ">
                        <i class="menu-icon las la-shield-alt"></i>
                        <span class="menu-title">@lang('2FA Security')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('user.change.password') }}">
                    <a href="{{ route('user.change.password') }}" class="nav-link ">
                        <i class="menu-icon las la-lock"></i>
                        <span class="menu-title">@lang('Change Password')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ menuActive('user.logout') }}">
                    <a href="{{ route('user.logout') }}" class="nav-link ">
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
        <input type="search" name="navbar-search__field" id="navbar-search__field"
               placeholder="Search...">
        <button type="button" class="navbar-search__close"><i class="las la-times"></i></button>

        <div id="navbar_search_result_area">
            <ul class="navbar_search_result"></ul>
        </div>
    </form>

    <div class="navbar__right">
        
        <a href="{{ route('user.profile.setting') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2 border-0">
            
            <i class="dropdown-menu__icon fas fa-wallet"></i> <br>
            <span class="dropdown-menu__caption mr-3">@lang('Wallet Balance') ({{ number_format((Auth::user()->savings_balance), 2) }})</span>            
            
            <i class="dropdown-menu__icon fas fa-coins"></i>
            <span class="dropdown-menu__caption mr-3">@lang('Savings Balance') ({{ number_format((Auth::user()->wallet_balance), 2) }})</span>
            
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
