


<header class="main-header">
    <!-- Logo -->
    <a href="/shop/index2.html" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
    {{--<span class="logo-mini"><b>A</b>LT</span>--}}
    <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b></b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">平台</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="{{route("admin.index")}}">平台首页 <span class="sr-only">(current)</span></a></li>

                    @foreach(\App\Models\Nav::where("pid",0)->get() as $k1=>$v1)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{$v1->name}} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @foreach(\App\Models\Nav::where("pid",$v1->id)->get() as $k2=>$v2)
                            <li><a href="{{route($v2->url)}}">{{$v2->name}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach




                    {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">店户管理 <span class="caret"></span></a>--}}
                        {{--<ul class="dropdown-menu">--}}
                            {{--<li ><a href="{{route("shop.list")}}">店铺申请处理</a></li>--}}
                            {{--<li><a href="{{route("user.list")}}">商户列表</a></li>--}}


                        {{--</ul>--}}
                    {{--</li>--}}
                    {{----}}
                    {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">活动管理 <span class="caret"></span></a>--}}
                        {{--<ul class="dropdown-menu">--}}
                            {{--<li><a href="{{route("activity.index")}}">活动列表</a></li>--}}
                            {{--<li><a href="{{route("activity.add")}}">添加活动</a></li>--}}


                        {{--</ul>--}}
                    {{--</li>--}}
                    {{----}}
                    {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">订单管理 <span class="caret"></span></a>--}}
                        {{--<ul class="dropdown-menu">--}}
                            {{--<li><a href="{{route("admin.order.index")}}">订单列表</a></li>--}}
                            {{--<li><a href="{{route("admin.order.day")}}">每日统计</a></li>--}}
                            {{--<li><a href="{{route("admin.order.month")}}">每月统计</a></li>--}}


                        {{--</ul>--}}
                    {{--</li>--}}
                    {{----}}
                    {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">会员管理 <span class="caret"></span></a>--}}
                        {{--<ul class="dropdown-menu">--}}

                            {{--<li><a href="{{route('admin.member.index')}}">会员列表</a></li>--}}



                        {{--</ul>--}}
                    {{--</li>--}}

                    {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">权限管理 <span class="caret"></span></a>--}}
                        {{--<ul class="dropdown-menu">--}}

                            {{--<li><a href="{{route('per.index')}}">权限列表</a></li>--}}
                            {{--<li><a href="{{route('per.add')}}">权限添加</a></li>--}}



                        {{--</ul>--}}
                    {{--</li>--}}
                    {{----}}
                    {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">角色管理 <span class="caret"></span></a>--}}
                        {{--<ul class="dropdown-menu">--}}

                            {{--<li><a href="{{route('role.index')}}">角色列表</a></li>--}}
                            {{--<li><a href="{{route('role.add')}}">角色添加</a></li>--}}



                        {{--</ul>--}}
                    {{--</li>--}}


                </ul>
                {{--<form class="navbar-form navbar-left">--}}
                {{--<div class="form-group">--}}
                {{--<input type="text" class="form-control" placeholder="Search">--}}
                {{--</div>--}}
                {{--<button type="submit" class="btn btn-default">Submit</button>--}}
                {{--</form>--}}


                <ul class="nav navbar-nav navbar-right">
                    @auth("admin")

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> {{\Illuminate\Support\Facades\Auth::guard("admin")->user()->name}} <span class="caret"></span></a>

                        <ul class="dropdown-menu">
                            <?php
                            $id=\Illuminate\Support\Facades\Auth::guard("admin")->user()->id;
                            ?>
                            <li><a href="{{route("admin.edit",$id)}}">修改密码</a></li>
                            <li><a href="{{route("admin.logout")}}"> 退出登录</a></li>
                        </ul>
                    </li>
                    @endauth
                    {{--游客状态--}}
                    @guest("admin")
                    <li><a href="{{route("admin.login")}}">考虑登录呗</a></li>

                    @endguest
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->

    </nav>
</header>