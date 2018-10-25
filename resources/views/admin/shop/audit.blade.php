
@extends("admin.layouts.main")
@section("title","处理")
@section("content")
<div class="rows">
    <form class="form-horizontal" method="post">
        {{csrf_field()}}
        <label  class="col-sm-2 control-label">审核处理</label>
        <label class="radio-inline">
            <input type="radio" name="status"  value="1"> 审核通过
        </label>
        <label class="radio-inline">
            <input type="radio" name="status"  value="0">等待中
        </label>
        <br/><br/>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
                <button type="submit" class="btn btn-default">提交</button>
            </div>
        </div>

    </form>
</div>


@endsection
