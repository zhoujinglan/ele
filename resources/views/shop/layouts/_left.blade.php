<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/{{\Illuminate\Support\Facades\Auth::guard()->user()->img}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">控制模块</li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>菜品分类</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route("menu_cate.index")}}"><i class="fa fa-circle-o"></i> 菜品分类列表</a></li>
                    <li><a href="{{route("menu_cate.add")}}"><i class="fa fa-circle-o"></i> 菜品分类添加 </a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-qrcode"></i> <span>菜品</span>
                    <span class="pull-right-container">
                   <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route("menu.index")}}"><i class="fa fa-circle-o"></i> 菜品列表</a></li>
                    <li><a href="{{route("menu.add")}}"><i class="fa fa-circle-o"></i> 菜品添加 </a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-tasks" ></i> <span>活动</span>
                    <span class="pull-right-container">
                   <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route("user.activity.index")}}"><i class="fa fa-circle-o"></i> 查看活动</a></li>

                </ul>

            </li>


            <li class="treeview">
                <a href="#">
                    <i class="fa fa-tasks" ></i> <span>订单管理</span>
                    <span class="pull-right-container">
                   <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route("shop.order.index")}}"><i class="fa fa-circle-o"></i> 查看订单</a></li>
                    <li><a href="{{route("shop.order.day")}}"><i class="fa fa-circle-o"></i> 按日统计</a></li>
                    <li><a href="{{route("shop.order.month")}}"><i class="fa fa-circle-o"></i> 按月统计</a></li>

                </ul>

            </li>


            <li class="treeview">
                <a href="#">
                    <i class="fa fa-tasks" ></i> <span>菜品统计管理</span>
                    <span class="pull-right-container">
                   <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">

                    <li><a href="{{route("shop.order.menu")}}"><i class="fa fa-circle-o"></i> 菜品总统计</a></li>
                    <li> <a href="{{route("shop.order.menu_day")}}"><i class="fa fa-circle-o"></i> 菜品天统计量</a></li>
                    <li> <a href="{{route("shop.order.menu_month")}}"><i class="fa fa-circle-o"></i> 菜品月统计量</a></li>




                </ul>

            </li>


            <li class="header">LABELS</li>
            <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
            <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
            <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>