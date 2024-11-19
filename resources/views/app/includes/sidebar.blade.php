<div class="nk-sidebar nk-sidebar-fixed is-dark" data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-menu-trigger">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
        </div>
        <div class="nk-sidebar-brand">
            <a href="#" class="logo-link nk-sidebar-logo">
                <img class="logo-light logo-img" src="/logo.png" srcset="/logo.png" alt="logo" />
                <img class="logo-dark logo-img" src="/logo.png" srcset="/logo.png" alt="logo-dark" />
            </a>
        </div>
    </div>
    <div class="nk-sidebar-element nk-sidebar-body">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <li class="nk-menu-item">
                        <a href="{{route('admin.dashboard.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-dashboard-fill"></em></span><span class="nk-menu-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('admin.branches.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-layers-fill"></em></span><span class="nk-menu-text">Branches</span>
                        </a>
                    </li>
                    
                    <li class="nk-menu-item">
                        <a href="{{route('admin.managers.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span><span class="nk-menu-text">Managers</span>
                        </a>
                    </li>
 
                    
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
                    </li>

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
                    </li>


                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><i class="fa-solid fa-gears"></i></span><span class="nk-menu-text">Settings</span>
                        </a>
                        <ul class="nk-menu-sub">
                            @foreach(get_branches() as $key=>$name)
                                <li class="nk-menu-item">
                                    <a href="{{route('admin.settings.index', $key)}}" class="nk-menu-link"><span class="nk-menu-text">{{$name}}</span></a>
                                </li>
                           @endforeach
                        </ul>
                    </li>


                    

                    {{-- <li class="nk-menu-item has-sub">
                        <a href="index.html#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-cart-fill"></em></span><span class="nk-menu-text">Sales</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="invoices.html" class="nk-menu-link"><span class="nk-menu-text">Invoices</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="payment.html" class="nk-menu-link"><span class="nk-menu-text">Payment</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="recent-sale.html" class="nk-menu-link"><span class="nk-menu-text">Recent Sale</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="estimates.html" class="nk-menu-link"><span class="nk-menu-text">Estimates</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="expenses.html" class="nk-menu-link"><span class="nk-menu-text">Expenses</span></a>
                            </li>
                        </ul>
                    </li> --}}
                    
                    


                </ul>
            </div>
        </div>
    </div>
</div>