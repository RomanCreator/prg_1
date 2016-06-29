<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
        @can('add', $controllerEntity)
        <button type="submit" class="btn btn-primary">Сохранить</button>
        @endcan

        <a href="{{ isset($controllerPathList) ? $controllerPathList : url('/home/') }}" class="btn btn-default">Отмена</a>
    </div>
</div>