
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="/dashboard" class="logo">
                        {{-- <img src="{{ asset('') }}assets/img/kaiadmin/logo_light.svg" alt="navbar brand"class="navbar-brand" height="20" /> --}}
                        <h4> Era Traders</h4>
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                            <a data-bs-toggle="collapse" href="/dashboard">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>
                                </span>
                            </a>   
                        </li>
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Components</h4>
                        </li>
                        
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#product-setting">
                                <i class="fas fa-box-open"></i>
                                <p>Product Setting</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="product-setting">
                                <ul class="nav nav-collapse">
                                    <li class="nav-item {{ request()->is('category-create') ? 'active' : '' }}">
                                        <a href="/category-create">
                                            <span class="sub-item">Categories</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ request()->is('product-create') ? 'active' : '' }}">
                                        <a href="/product-create">
                                            <span class="sub-item">Products</span>
                                        </a>
                                    </li>
                                   
                                    
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#sale">
                                <i class="fas fa-box"></i>
                                <p>Sale </p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="sale">
                                <ul class="nav nav-collapse">
                                    <li class="nav-item {{ request()->is('sales') ? 'active' : '' }}">
                                        <a href="/sales">
                                            <span class="sub-item">Sales</span>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#stock">
                                <i class="fas fa-box"></i>
                                <p>Stock </p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="stock">
                                <ul class="nav nav-collapse">
                                    <li class="nav-item {{ request()->is('product-in') ? 'active' : '' }}">
                                        <a href="/product-in">
                                            <span class="sub-item">Product In</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ request()->is('product-in-return') ? 'active' : '' }}">
                                        <a href="/product-in-return">
                                            <span class="sub-item">Product In Return</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ request()->is('inventory-report') ? 'active' : '' }}">
                                        <a href="/inventory-report">
                                            <span class="sub-item">Inventory</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#reports">
                                <i class="fas fa-chart-line"></i>
                                <p>Reports</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="reports">
                                <ul class="nav nav-collapse">
                                    <li class="nav-item {{ request()->is('product-in-report') ? 'active' : '' }}">
                                        <a href="/product-in-report">
                                            <span class="sub-item">Product In Report</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ request()->is('product-in-return-report') ? 'active' : '' }}">
                                        <a href="/product-in-return-report">
                                            <span class="sub-item">Product In Return Report</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ request()->is('sales-report') ? 'active' : '' }}">
                                        <a href="/sales-report">
                                            <span class="sub-item">Sales Report</span>
                                        </a>
                                    </li>
                                   
                                    
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#user-management">
                                <i class="fas fa-users"></i>
                                <p>User Management</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="user-management">
                                <ul class="nav nav-collapse">
                                    <li class="nav-item {{ request()->is('users-list') ? 'active' : '' }}">
                                        <a href="/users-list">
                                            <span class="sub-item">User</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ request()->is('role-list') ? 'active' : '' }}">
                                        <a href="/role-list">
                                            <span class="sub-item">Roles</span>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </div>
                        </li>
                     
                       
                      
                       
                        
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->