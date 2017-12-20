<form class="" action="{{route('statuses.store')}}" method="post">
    @include('shared._errors')
    {{ csrf_field() }}
    <textarea class="form-control" name="content" rows="3" placeholder="聊聊新鲜事儿......">{{ old('content') }}</textarea>
    <button type="submit" class="bnt bnt-primary pull-right">发布</button>
</form>