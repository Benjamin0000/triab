<div class="nk-sidebar nk-sidebar-fixed is-dark" data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-menu-trigger">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
        </div>
        <div class="nk-sidebar-brand">
            <a href="#" class="logo-link nk-sidebar-logo">
                <img class="logo-light logo-img" src="/assets/frontpage/img/logo.png" srcset="/assets/frontpage/img/logo.png" alt="logo" />
                <img class="logo-dark logo-img" src="/assets/frontpage/img/logo.png" srcset="/assets/frontpage/img/logo.png" alt="logo-dark" />
            </a>
        </div>
    </div>
    <div class="nk-sidebar-element nk-sidebar-body">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">

                    
                    <li class="nk-menu-item">
                        <a href="{{route('dashboard.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-dashboard-fill"></em></span><span class="nk-menu-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="nk-menu-item">
                        <a href="{{route('dashboard.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-money"></em></span><span class="nk-menu-text">Funds</span>
                        </a>
                    </li>

                    <li class="nk-menu-item">
                        <a href="{{route('dashboard.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-money"></em></span><span class="nk-menu-text">Bills Payment</span>
                        </a>
                    </li>

                    <li class="nk-menu-item">
                        <a href="{{route('dashboard.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon fa fa-store-alt"></em></span><span class="nk-menu-text">Triab Market</span>
                        </a>
                    </li>

                    <li class="nk-menu-item">
                        <a href="{{route('community.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon fa fa-users"></em></span><span class="nk-menu-text">Triab Community</span>
                        </a>
                    </li>

                    <li class="nk-menu-item">
                        <a href="{{route('dashboard.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon fa-solid fa-gears"></em></span><span class="nk-menu-text">Settings</span>
                        </a>
                    </li>


                    {{-- <li class="nk-menu-item">
                        <a href="{{route('admin.branches.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-layers-fill"></em></span><span class="nk-menu-text">Branches</span>
                        </a>
                    </li> --}}
                    
                    {{-- <li class="nk-menu-item">
                        <a href="{{route('admin.managers.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span><span class="nk-menu-text">Managers</span>
                        </a>
                    </li> --}}
 
{{--                     
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-cart-fill"></em></span><span class="nk-menu-text">Items</span>
                        </a>
                        <ul class="nk-menu-sub">
                            @foreach(get_branches() as $key=>$name)
                                <li class="nk-menu-item">
                                    <a href="{{route('admin.items.index', $key)}}" class="nk-menu-link"><span class="nk-menu-text">{{$name}}</span></a>
                                </li>
                           @endforeach
                        </ul>
                    </li> --}}
{{-- 
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-cart-fill"></em></span><span class="nk-menu-text">Stocks</span>
                        </a>
                        <ul class="nk-menu-sub">
                            @foreach(get_branches() as $key=>$name)
                                <li class="nk-menu-item">
                                    <a href="{{route('admin.stock.index', $key)}}" class="nk-menu-link"><span class="nk-menu-text">{{$name}}</span></a>
                                </li>
                           @endforeach
                        </ul>
                    </li> --}}





                    
                    <li class="text-center" style="color:white;">
                        <hr>
                    </li>

                    
                    <li class="nk-menu-item">
                        <a href="#" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon fa fa-store"></em></span><span class="nk-menu-text">E-Shop</span>
                        </a>
                    </li>

                      
                    <li class="nk-menu-item">
                        <a href="#" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon fa fa-hotel"></em></span><span class="nk-menu-text">Hotels</span>
                        </a>
                    </li>

                    <li class="nk-menu-item">
                        <a href="#" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon fa fa-utensils"></em></span><span class="nk-menu-text">Restaurants</span>
                        </a>
                    </li>

                    <li class="nk-menu-item">
                        <a href="#" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon fa fa-motorcycle"></em></span><span class="nk-menu-text">Logistics</span>
                        </a>
                    </li>

                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><i class="fa-solid fa-gears"></i></span><span class="nk-menu-text">Settings</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="{{route('admin.packages.index')}}" class="nk-menu-link"><span class="nk-menu-text">Packages</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{route('admin.reward.settings')}}" class="nk-menu-link"><span class="nk-menu-text">Rewards</span></a>
                            </li>
                        </ul>
                    </li> 

                </ul>
            </div>
        </div>
    </div>
</div>