<style>

    .topnav {
        display: none;
    }
    
    /*@media(max-width: 992px){*/
    /*    .topnav {*/
    /*        display: block;*/
    /*    }*/
        
    /*    .topnav a {*/
    /*      text-decoration: none;*/
    /*      display: block;*/
    /*    }*/
    
    /*    #myLinks a{*/
    /*        margin-bottom: 20px;*/
    /*    }*/
        
    /*    #icon-menu-close {*/
    /*        display: none;*/
    /*    }*/
    /*}*/

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
            <li class="sidebar-menu-item {{menuActive('admin.dashboard')}}">
                <a href="{{route('admin.dashboard')}}" class="nav-link ">
                    <i class="menu-icon las la-home"></i>
                    <span class="menu-title">@lang('Dashboard')</span>
                </a>
            </li>


            <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('admin.users*',3)}}">
                    <i class="menu-icon las la-users"></i>
                    <span class="menu-title">@lang('Manage Users')</span>
                    
                    <?php
                    /*
                    @if($banned_users_count > 0 || $email_unverified_users_count > 0 || $sms_unverified_users_count > 0)
                        <span class="menu-badge pill bg--primary ml-auto">
                            <i class="fa fa-exclamation"></i>
                        </span>
                    @endif
                    */
                    ?>
                </a>
                <div class="sidebar-submenu {{menuActive('admin.users*',2)}} ">
                    <ul>
                        <li class="sidebar-menu-item {{menuActive('admin.users.all')}} ">
                            <a href="{{route('admin.users.all')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('All Users')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{menuActive('admin.users.active')}} ">
                            <a href="{{route('admin.users.active')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Active Users')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.users.banned')}} ">
                            <a href="{{route('admin.users.banned')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Banned Users')</span>
                                 <?php
                                /*
                                @if($banned_users_count)
                                    <span class="menu-badge pill bg--primary ml-auto">{{$banned_users_count}}</span>
                                @endif
                                */
                                ?>
                            </a>
                        </li>

                        <li class="sidebar-menu-item  {{menuActive('admin.users.email.unverified')}}">
                            <a href="{{route('admin.users.email.unverified')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Email Unverified')</span>
                                
                                 <?php
                    /*
                                @if($email_unverified_users_count)
                                    <span
                                        class="menu-badge pill bg--primary ml-auto">{{$email_unverified_users_count}}</span>
                                @endif
                                */
                                ?>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{menuActive('admin.users.sms.unverified')}}">
                            <a href="{{route('admin.users.sms.unverified')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('SMS Unverified')</span>
                                 <?php
                    /*
                                @if($sms_unverified_users_count)
                                    <span
                                        class="menu-badge pill bg--primary ml-auto">{{$sms_unverified_users_count}}</span>
                                @endif
                                */
                                ?>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{menuActive('admin.users.email.all')}}">
                            <a href="{{route('admin.users.email.all')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Email to All')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('admin.staffs*',3)}}">
                    <i class="menu-icon las la-user-friends"></i>
                    <span class="menu-title">@lang('Manage Staffs')</span>

 <?php
                    /*
                    @if($banned_staffs_count > 0 || $email_unverified_staffs_count > 0 || $sms_unverified_staffs_count > 0)
                        <span class="menu-badge pill bg--primary ml-auto">
                            <i class="fa fa-exclamation"></i>
                        </span>
                    @endif
                    
                    */
                    ?>
                </a>
                <div class="sidebar-submenu {{menuActive('admin.staffs*',2)}} ">
                    <ul>
                        <li class="sidebar-menu-item {{menuActive('admin.staffs.all')}} ">
                            <a href="{{route('admin.staffs.all')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('All Staffs')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{menuActive('admin.staffs.active')}} ">
                            <a href="{{route('admin.staffs.active')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Active Staffs')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.staffs.banned')}} ">
                            <a href="{{route('admin.staffs.banned')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Banned Staffs')</span>
                                 <?php
                    /*
                                @if($banned_staffs_count)
                                    <span class="menu-badge pill bg--primary ml-auto">{{$banned_staffs_count}}</span>
                                @endif
                                */
                                ?>
                            </a>
                        </li>

                        <li class="sidebar-menu-item  {{menuActive('admin.staffs.email.unverified')}}">
                            <a href="{{route('admin.staffs.email.unverified')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Email Unverified')</span>

 <?php
                    /*
                                @if($email_unverified_staffs_count)
                                    <span
                                        class="menu-badge pill bg--primary ml-auto">{{$email_unverified_staffs_count}}</span>
                                @endif
                                */
                                ?>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{menuActive('admin.staffs.sms.unverified')}}">
                            <a href="{{route('admin.staffs.sms.unverified')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('SMS Unverified')</span>
                                 <?php
                    /*
                                @if($sms_unverified_staffs_count)
                                    <span
                                        class="menu-badge pill bg--primary ml-auto">{{$sms_unverified_staffs_count}}</span>
                                @endif
                                */
                                ?>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{menuActive('admin.staffs.email.all')}}">
                            <a href="{{route('admin.staffs.email.all')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Email to All')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-item {{menuActive('admin.time.intervals')}}">
                <a href="{{route('admin.time.intervals')}}" class="nav-link ">
                    <i class="menu-icon las la-clock"></i>
                    <span class="menu-title">@lang('Time Interval')</span>
                </a>
            </li>

            <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('admin.plan*',3)}}">
                    <i class="menu-icon  las la-money-bill-wave-alt"></i>
                    <span class="menu-title">@lang('Plans')</span>
                </a>
                <div class="sidebar-submenu {{menuActive('admin.plan*',2)}} ">
                    <ul>
                        <li class="sidebar-menu-item {{menuActive(['admin.plan.loan.index', 'admin.plan.loan.create', 'admin.plan.loan.edit'])}}">
                            <a href="{{route('admin.plan.loan.index')}}" class="nav-link ">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Loan ')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive(['admin.plan.savings.index', 'admin.plan.savings.create', 'admin.plan.savings.edit'])}}">
                            <a href="{{route('admin.plan.savings.index')}}" class="nav-link ">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Savings')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('admin.loan*',3)}}">
                    <i class="menu-icon las la-comment-dollar"></i>
                    <span class="menu-title">@lang('Loan')</span>
                     <?php
                    /*
                    @if($pending_loan_count > 0)
                        <span class="menu-badge pill bg--primary ml-auto">
                            <i class="fa fa-exclamation"></i>
                        </span>
                    @endif
                    */
                    ?>
                </a>
                <div class="sidebar-submenu {{menuActive('admin.loan*',2)}} ">
                    <ul>
                        <li class="sidebar-menu-item {{menuActive('admin.loan.pending')}}">
                            <a href="{{ route('admin.loan.pending') }}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Pending')</span>
                                 <?php
                    /*
                                @if($pending_loan_count)
                                    <span class="menu-badge pill bg--primary ml-auto">{{$pending_loan_count}}</span>
                                @endif
                                */
                                ?>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ menuActive('admin.loan.active') }}">
                            <a href="{{ route('admin.loan.active') }}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Active')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.loan.paid')}}">
                            <a href="{{ route('admin.loan.paid') }}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Paid')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.loan.all')}}">
                            <a href="{{ route('admin.loan.all') }}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('All')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.loan.save')}}">
                            <a href="{{ route('admin.loan.save') }}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Register Loan')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('admin.savings*',3)}}">
                    <i class="menu-icon las la-coins"></i>
                    <span class="menu-title">@lang('Savings')</span>
                     <?php
                    /*
                    @if($pending_savings_count > 0 || $pending_matured_savings_count > 0)
                        <span class="menu-badge pill bg--primary ml-auto">
                            <i class="fa fa-exclamation"></i>
                        </span>
                    @endif
                    */
                    ?>
                </a>
                <div class="sidebar-submenu {{menuActive('admin.savings*',2)}} ">
                    <ul>
                        <li class="sidebar-menu-item {{menuActive('admin.savings.pending')}}">
                            <a href="{{route('admin.savings.pending')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Pending')</span>
                                 <?php
                    /*
                                @if($pending_savings_count)
                                    <span class="menu-badge pill bg--primary ml-auto">{{$pending_savings_count}}</span>
                                @endif
                                */
                                ?>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.savings.active')}}">
                            <a href="{{route('admin.savings.active')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Active')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.savings.matured.pending')}}">
                            <a href="{{route('admin.savings.matured.pending')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Pending Matured')</span>
                                 <?php
                    /*
                                @if($pending_matured_savings_count)
                                    <span class="menu-badge pill bg--primary ml-auto">{{$pending_matured_savings_count}}</span>
                                @endif
                                */
                                ?>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.savings.matured.paid')}}">
                            <a href="{{route('admin.savings.matured.paid')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Paid Matured')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.savings.all')}}">
                            <a href="{{route('admin.savings.all')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('All')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.savings.save')}}">
                            <a href="{{route('admin.savings.save')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Register Savings')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('admin.collection*',3)}}">
                    <i class="menu-icon las la-calculator"></i>
                    <span class="menu-title">@lang('Collection')</span>
                </a>
                <div class="sidebar-submenu {{menuActive('admin.collection*',2)}} ">
                    <ul>
                        <li class="sidebar-menu-item {{menuActive('admin.collection.all')}} ">
                            <a href="{{route('admin.collection.all')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('All')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.collection.loan')}} ">
                            <a href="{{route('admin.collection.loan')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Loan')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.collection.savings')}} ">
                            <a href="{{route('admin.collection.savings')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Savings')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('admin.staff.collection*',3)}}">
                    <i class="menu-icon las la-tasks"></i>
                    <span class="menu-title">@lang('Staff Collection')</span>
                </a>
                <div class="sidebar-submenu {{menuActive('admin.staff.collection*',2)}} ">
                    <ul>
                        <li class="sidebar-menu-item {{menuActive('admin.staff.collection.loan.pending')}} ">
                            <a href="{{route('admin.staff.collection.loan.pending')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Pending Loan')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.staff.collection.savings.pending')}} ">
                            <a href="{{route('admin.staff.collection.savings.pending')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Pending Savings')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.staff.collection.loan.paid')}} ">
                            <a href="{{route('admin.staff.collection.loan.paid')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Paid Loan')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.staff.collection.savings.paid')}} ">
                            <a href="{{route('admin.staff.collection.savings.paid')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Paid Savings')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('admin.gateway*',3)}}">
                    <i class="menu-icon las la-credit-card"></i>
                    <span class="menu-title">@lang('Payment Gateways')</span>

                </a>
                <div class="sidebar-submenu {{menuActive('admin.gateway*',2)}} ">
                    <ul>
                        <li class="sidebar-menu-item {{menuActive('admin.gateway.automatic.index')}} ">
                            <a href="{{route('admin.gateway.automatic.index')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Automatic Gateways')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.gateway.manual.index')}} ">
                            <a href="{{route('admin.gateway.manual.index')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Manual Gateways')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('admin.deposit*',3)}}">
                    <i class="menu-icon las la-credit-card"></i>
                    <span class="menu-title">@lang('Deposits')</span>
                     <?php
                    /*
                    @if(0 < $pending_deposits_count)
                        <span class="menu-badge pill bg--primary ml-auto">
                            <i class="fa fa-exclamation"></i>
                        </span>
                    @endif
                    */
                    ?>
                </a>
                <div class="sidebar-submenu {{menuActive('admin.deposit*',2)}} ">
                    <ul>

                        <li class="sidebar-menu-item {{menuActive('admin.deposit.pending')}} ">
                            <a href="{{route('admin.deposit.pending')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Pending Deposits')</span>
                                 <?php
                    /*
                                @if($pending_deposits_count)
                                    <span class="menu-badge pill bg--primary ml-auto">{{$pending_deposits_count}}</span>
                                @endif
                                */
                                ?>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{menuActive('admin.deposit.approved')}} ">
                            <a href="{{route('admin.deposit.approved')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Approved Deposits')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{menuActive('admin.deposit.successful')}} ">
                            <a href="{{route('admin.deposit.successful')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Successful Deposits')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{menuActive('admin.deposit.rejected')}} ">
                            <a href="{{route('admin.deposit.rejected')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Rejected Deposits')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{menuActive('admin.deposit.list')}} ">
                            <a href="{{route('admin.deposit.list')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('All Deposits')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('admin.user.ticket*',3)}}">
                    <i class="menu-icon la la-ticket"></i>
                    <span class="menu-title">@lang('User Support') </span>
                     <?php
                    /*
                    @if(0 < $pending_user_ticket_count)
                        <span class="menu-badge pill bg--primary ml-auto">
                            <i class="fa fa-exclamation"></i>
                        </span>
                    @endif
                    */
                    ?>
                </a>
                <div class="sidebar-submenu {{menuActive('admin.user.ticket*',2)}} ">
                    <ul>

                        <li class="sidebar-menu-item {{menuActive('admin.user.ticket')}} ">
                            <a href="{{route('admin.user.ticket')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('All Ticket')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.user.ticket.pending')}} ">
                            <a href="{{route('admin.user.ticket.pending')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Pending Ticket')</span>
                                 <?php
                    /*
                                @if($pending_user_ticket_count)
                                    <span
                                        class="menu-badge pill bg--primary ml-auto">{{$pending_user_ticket_count}}</span>
                                @endif
                                */
                                ?>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.user.ticket.closed')}} ">
                            <a href="{{route('admin.user.ticket.closed')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Closed Ticket')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.user.ticket.answered')}} ">
                            <a href="{{route('admin.user.ticket.answered')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Answered Ticket')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('admin.staff.ticket*',3)}}">
                    <i class="menu-icon la la-envelope"></i>
                    <span class="menu-title">@lang('Staff Support') </span>
                     <?php
                    /*
                    @if(0 < $pending_staff_ticket_count)
                        <span class="menu-badge pill bg--primary ml-auto">
                            <i class="fa fa-exclamation"></i>
                        </span>
                    @endif
                    */
                    
                    ?>
                </a>
                <div class="sidebar-submenu {{menuActive('admin.staff.ticket*',2)}} ">
                    <ul>
                        <li class="sidebar-menu-item {{menuActive('admin.staff.ticket')}} ">
                            <a href="{{route('admin.staff.ticket')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('All Ticket')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.staff.ticket.pending')}} ">
                            <a href="{{route('admin.staff.ticket.pending')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Pending Ticket')</span>
                                 <?php
                    /*
                                @if($pending_staff_ticket_count)
                                    <span
                                        class="menu-badge pill bg--primary ml-auto">{{$pending_staff_ticket_count}}</span>
                                @endif
                                */
                                ?>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.staff.ticket.closed')}} ">
                            <a href="{{route('admin.staff.ticket.closed')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Closed Ticket')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.staff.ticket.answered')}} ">
                            <a href="{{route('admin.staff.ticket.answered')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Answered Ticket')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('admin.report*',3)}}">
                    <i class="menu-icon la la-list"></i>
                    <span class="menu-title">@lang('Report') </span>
                </a>
                <div class="sidebar-submenu {{menuActive('admin.report*',2)}} ">
                    <ul>
                        <li class="sidebar-menu-item {{menuActive(['admin.report.user.login.history','admin.report.user.login.ipHistory'])}}">
                            <a href="{{route('admin.report.user.login.history')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('User Login')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive(['admin.report.staff.login.history','admin.report.staff.login.ipHistory'])}}">
                            <a href="{{route('admin.report.staff.login.history')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Staff Login')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.report.user.email.history')}}">
                            <a href="{{route('admin.report.user.email.history')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('User Email')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.report.staff.email.history')}}">
                            <a href="{{route('admin.report.staff.email.history')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Staff Email')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar__menu-header">@lang('Settings')</li>

            <li class="sidebar-menu-item {{menuActive('admin.setting.index')}}">
                <a href="{{route('admin.setting.index')}}" class="nav-link">
                    <i class="menu-icon las la-life-ring"></i>
                    <span class="menu-title">@lang('General Setting')</span>
                </a>
            </li>

            <li class="sidebar-menu-item {{menuActive('admin.setting.logo.icon')}}">
                <a href="{{route('admin.setting.logo.icon')}}" class="nav-link">
                    <i class="menu-icon las la-images"></i>
                    <span class="menu-title">@lang('Logo & Favicon')</span>
                </a>
            </li>

            <li class="sidebar-menu-item {{menuActive('admin.extensions.index')}}">
                <a href="{{route('admin.extensions.index')}}" class="nav-link">
                    <i class="menu-icon las la-cogs"></i>
                    <span class="menu-title">@lang('Extensions')</span>
                </a>
            </li>

            <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('admin.email.template*',3)}}">
                    <i class="menu-icon la la-envelope-o"></i>
                    <span class="menu-title">@lang('Email Manager')</span>
                </a>
                <div class="sidebar-submenu {{menuActive('admin.email.template*',2)}} ">
                    <ul>

                        <li class="sidebar-menu-item {{menuActive('admin.email.template.global')}} ">
                            <a href="{{route('admin.email.template.global')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Global Template')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive(['admin.email.template.index','admin.email.template.edit'])}} ">
                            <a href="{{ route('admin.email.template.index') }}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Email Templates')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{menuActive('admin.email.template.setting')}} ">
                            <a href="{{route('admin.email.template.setting')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Email Configure')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('admin.sms.template*',3)}}">
                    <i class="menu-icon la la-mobile"></i>
                    <span class="menu-title">@lang('SMS Manager')</span>
                </a>
                <div class="sidebar-submenu {{menuActive('admin.sms.template*',2)}} ">
                    <ul>
                        <li class="sidebar-menu-item {{menuActive('admin.sms.template.global')}} ">
                            <a href="{{route('admin.sms.template.global')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Global Setting')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive('admin.sms.templates.setting')}} ">
                            <a href="{{route('admin.sms.templates.setting')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('SMS Gateways')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{menuActive(['admin.sms.template.index','admin.sms.template.edit'])}} ">
                            <a href="{{ route('admin.sms.template.index') }}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('SMS Templates')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            @php
                $lastSegment =  collect(request()->segments())->last();
            @endphp
            @foreach(getPageSections(true) as $k => $secs)
                @if($secs['builder'])
                    <li class="sidebar-menu-item  @if($lastSegment == $k) active @endif ">
                        <a href="{{ route('admin.frontend.sections',$k) }}" class="nav-link">
                            <i class="menu-icon las la-poll-h"></i>
                            <span class="menu-title">{{__($secs['name'])}}</span>
                        </a>
                    </li>
                @endif
            @endforeach

            <li class="sidebar__menu-header">@lang('Extra')</li>

            <li class="sidebar-menu-item  {{menuActive('admin.system.info')}}">
                <a href="{{route('admin.system.info')}}" class="nav-link"
                   data-default-url="{{ route('admin.system.info') }}">
                    <i class="menu-icon las la-server"></i>
                    <span class="menu-title">@lang('System Information') </span>
                </a>
            </li>

            <li class="sidebar-menu-item {{menuActive('admin.setting.custom.css')}}">
                <a href="{{route('admin.setting.custom.css')}}" class="nav-link">
                    <i class="menu-icon lab la-css3-alt"></i>
                    <span class="menu-title">@lang('Custom CSS')</span>
                </a>
            </li>

            <li class="sidebar-menu-item {{menuActive('admin.setting.optimize')}}">
                <a href="{{route('admin.setting.optimize')}}" class="nav-link">
                    <i class="menu-icon las la-broom"></i>
                    <span class="menu-title">@lang('Clear Cache')</span>
                </a>
            </li>

            <li class="sidebar-menu-item  {{menuActive('admin.request.report')}}">
                <a href="{{route('admin.request.report')}}" class="nav-link"
                   data-default-url="{{ route('admin.request.report') }}">
                    <i class="menu-icon las la-bug"></i>
                    <span class="menu-title">@lang('Report & Request') </span>
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

    <div class="navbar__left">
        <button class="res-sidebar-open-btn"><i class="las la-bars"></i></button>
        <button type="button" class="fullscreen-btn">
            <i class="fullscreen-open las la-compress" onclick="openFullscreen();"></i>
            <i class="fullscreen-close las la-compress-arrows-alt" onclick="closeFullscreen();"></i>
        </button>
    </div>

    <div class="navbar__right">
        <ul class="navbar__action-list">
            <li>
                <button type="button" class="navbar-search__btn-open">
                    <i class="las la-search"></i>
                </button>
            </li>


            <li class="dropdown">
                <button type="button" class="primary--layer" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                  <i class="las la-bell text--primary"></i>
                  @if($adminNotifications->count() > 0)
                    <span class="pulse--primary"></span>
                  @endif
                </button>
                <div class="dropdown-menu dropdown-menu--md p-0 border-0 box--shadow1 dropdown-menu-right">
                  <div class="dropdown-menu__header">
                    <span class="caption">@lang('Notification')</span>
                    @if($adminNotifications->count() > 0)
                        <p>@lang('You have') {{ $adminNotifications->count() }} @lang('unread notification')</p>
                    @else
                        <p>@lang('No unread notification found')</p>
                    @endif
                  </div>
                  <div class="dropdown-menu__body">
                    @foreach($adminNotifications as $notification)
                    <a href="{{ route('admin.notification.read',$notification->id) }}" class="dropdown-menu__item">
                      <div class="navbar-notifi">
                        <div class="navbar-notifi__left bg--green b-radius--rounded"><img src="{{ getImage(imagePath()['profile']['user']['path'].'/'.@$notification->user->image,imagePath()['profile']['user']['size'])}}" alt="@lang('Profile Image')"></div>
                        <div class="navbar-notifi__right">
                          <h6 class="notifi__title">{{ __($notification->title) }}</h6>
                          <span class="time"><i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                      </div><!-- navbar-notifi end -->
                    </a>
                    @endforeach
                  </div>
                  <div class="dropdown-menu__footer">
                    <a href="{{ route('admin.notifications') }}" class="view-all-message">@lang('View all notification')</a>
                  </div>
                </div>
            </li>


            <li class="dropdown">
                <button type="button" class="" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                  <span class="navbar-user">
                    <span class="navbar-user__thumb"><img src="{{ getImage('assets/admin/images/profile/'. auth()->guard('admin')->user()->image) }}" alt="image"></span>
                    <span class="navbar-user__info">
                      <span class="navbar-user__name">{{auth()->guard('admin')->user()->username}}</span>
                    </span>
                    <span class="icon"><i class="las la-chevron-circle-down"></i></span>
                  </span>
                </button>
                <div class="dropdown-menu dropdown-menu--sm p-0 border-0 box--shadow1 dropdown-menu-right">
                    <a href="{{ route('admin.profile') }}"
                       class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                        <i class="dropdown-menu__icon las la-user-circle"></i>
                        <span class="dropdown-menu__caption">@lang('Profile')</span>
                    </a>

                    <a href="{{route('admin.password')}}"
                       class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                        <i class="dropdown-menu__icon las la-key"></i>
                        <span class="dropdown-menu__caption">@lang('Password')</span>
                    </a>

                    <a href="{{ route('admin.logout') }}"
                       class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                        <i class="dropdown-menu__icon las la-sign-out-alt"></i>
                        <span class="dropdown-menu__caption">@lang('Logout')</span>
                    </a>
                </div>
            </li>
        </ul>
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
