<style>
    .sidebar .slimScrollDiv .slimScrollBar {
    background-color: #fff !important;
    width: 6px !important;
    opacity: 1 !important;
}

.dropdown-menu .slimScrollDiv .slimScrollBar {
    background-color: #fff !important;
    width: 6px !important;
    opacity: 1 !important;
}

.activity-list .slimScrollBar {
    background-color: #fff !important;
    width: 6px !important;
    opacity: 1 !important;
}
</style>

<div class="sidebar {{ sidebarVariation()['selector'] }} {{ sidebarVariation()['sidebar'] }} {{ @sidebarVariation()['overlay'] }} {{ @sidebarVariation()['opacity'] }}"
    data-background="{{ getImage('assets/admin/images/sidebar/2.jpg','400x800') }}">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{ route('home') }}" class="sidebar__main-logo"><img
                    src="{{ getImage(imagePath()['logoIcon']['path'] .'/logo.png') }}"
                    alt="@lang('image')"></a>
            <a href="{{ route('home') }}" class="sidebar__logo-shape"><img
                    src="{{ getImage(imagePath()['logoIcon']['path'] .'/favicon.png') }}"
                    alt="@lang('image')"></a>
            <button type="button" class="navbar__expand"></button>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
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
                        <span class="menu-title">@lang('Loan Request Mgt') </span>
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
                
                <?php
                /*
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
                */
                ?>

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
                    <a href="javascript:void(0)" class="{{menuActive('fsp.loan*',3)}}">
                        <i class="menu-icon las la-comment-dollar"></i>
                        <span class="menu-title">@lang('Manage Funds') </span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('fsp.loan*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.closed')}} ">
                                <a href="#" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Deposit Funds')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.closed')}} ">
                                <a href="#" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Withdraw Funds')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.pending')}} ">
                                <a href="#" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Assets To Withdraw')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.active')}} ">
                                <a href="#" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Withdraw To Assets')</span>
                                </a>
                            </li>
                            
                            <li class="sidebar-menu-item {{menuActive('fsp.loan.closed')}} ">
                                <a href="#" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Transfer Funds')</span>
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
</div>
<!-- sidebar end -->
