@extends("admin.layouts.main")

@section("title","平台首页")

@section("content")

    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>150</h3>

                    <p>New Orders</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>53<sup style="font-size: 20px">%</sup></h3>

                    <p>Bounce Rate</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>44</h3>

                    <p>User Registrations</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>65</h3>

                    <p>Unique Visitors</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->

        {{--管理员列表--}}

        <div class="rows">
            <a href="{{route("cate.add")}}" class="btn btn-info">添加</a>
            <table class="table table-bordered">
                <tr>
                    <td>id</td>
                    <td>管理员姓名</td>
                    <td>邮箱</td>
                    <td>职位</td>
                    <td>操作</td>
                </tr>
                @foreach($admins as $admin)
                    <tr>
                        <td>{{$admin->id}}</td>
                        <td>{{$admin->name}}</td>
                        <td>{{$admin->email}}</td>
                        <td>{{json_encode($roles = $admin->getRoleNames(),JSON_UNESCAPED_UNICODE)}}</td>

                        <td>

                            @if($admin->id !=1)
                                <a href="{{route("admin.update",[$admin->id])}}" class="btn btn-success">编辑</a>
                            <a href="{{route("admin.del",[$admin->id])}}" class="btn btn-danger">删除</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table></div>
    </div>



@endsection