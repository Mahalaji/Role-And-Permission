@extends('layouts.app')
@section('content')
<style>
.content {
    display: flex;
    justify-content: center;
    align-content: center;
}

.design {
    background-color: #c2c2c2;
    width: 40vw;
    padding: 30px;
    border-radius: 10px;
    height: fit-content;
    margin: 20px;
}

form {
    margin-left: 20px;
}

.rows {
    text-align: center;
}
.sidebar{
    height: 430vh
}
</style>
<div class="design">
    <div class="rows">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Manage Access</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm mb-2" href="{{ route('roles.index') }}"><i
                        class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('roles.updateAccess', ['roleId' => $roleId]) }}">
        @csrf
        <ul>
            <div class="row">
                @foreach($modules as $module)
                <div class="col-lg-12">
                    <div class="form-group mb-3">
                        <div style="padding-left:5px;">
                            <label>
                                <input type="checkbox" class="category-checkbox"
                                    data-category="{{ $module['module_name'] }}">
                                <strong>{{ $module['module_name'] }}</strong>
                            </label>

                            <div class="ml-4" style="padding-left:10px;border-left:1px solid gray;margin-left:8px;">
                                <div>
                                    <input type="checkbox" class="menu-checkbox"
                                        data-category="{{ $module['module_name'] }}" value="show menu"> Show permission
                                </div>
                                <div class="form-group p-2" style="margin-left:8px;border-left:1px solid gray;">
                                    @foreach($module['permission'] as $permission)
                                    <div>
                                        <label>
                                            <input type="checkbox" name="permissions[]" class="permission-checkbox"
                                                data-category="{{ $module['module_name'] }}"
                                                value="{{ $permission['id'] }}"
                                                {{ in_array($permission['id'], $rolePermissions) ? 'checked' : '' }}>
                                            {{ $permission['name'] }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="ml-4" style="padding-left:10px;border-left:1px solid gray;margin-left:8px;">
                                    <div>
                                        <input type="checkbox" class="menu-checkbox"
                                            data-category="{{ $module['module_name'] }}" value="show menu"> Show
                                        Submodule
                                    </div>
                                    @if(isset($module['childmodule']) && is_array($module['childmodule']))
                                    <div class="form-group p-2" style="margin-left:8px;border-left:1px solid gray;">
                                        @foreach($module['childmodule'] as $childmod)
                                        <div>
                                            <label>
                                                <input type="checkbox" class="category-checkbox"
                                                    data-category="{{ $childmod['module_name'] }}">
                                                <strong>{{ $childmod['module_name'] }}</strong>
                                            </label>
                                            <div>
                                        <input type="checkbox" class="menu-checkbox"
                                            data-category="{{ $childmod['module_name'] }}" value="show menu"> Show
                                        permission
                                    </div>
                                            <div class="form-group p-2"
                                                style="margin-left:8px;border-left:1px solid gray;">
                                                @if(is_array($childmod['permission']) || is_object($childmod['permission']))
                                                    @foreach($childmod['permission'] as $permission)
                                                    <div>
                                                        <label>
                                                            <input type="checkbox" name="permissions[]"
                                                                class="permission-checkbox"
                                                                data-category="{{ $childmod['module_name'] }}"
                                                                value="{{ $permission['id'] }}"
                                                                {{ in_array($permission['id'], $rolePermissions) ? 'checked' : '' }}>
                                                            {{ $permission['name'] }}
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                @else
                                                    <p>No permissions available for this submodule.</p>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
        </ul>

        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i>
                Submit</button>
        </div>
    </form>

</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.category-checkbox').forEach(categoryCheckbox => {
        categoryCheckbox.addEventListener('change', function() {
            const category = this.getAttribute('data-category');
            const isChecked = this.checked;

            document.querySelectorAll(
                `.menu-checkbox[data-category="${category}"], .permission-checkbox[data-category="${category}"]`
            ).forEach(checkbox => {
                checkbox.checked = isChecked;
            });
        });
    });

    document.querySelectorAll('.menu-checkbox').forEach(menuCheckbox => {
        menuCheckbox.addEventListener('change', function() {
            const category = this.getAttribute('data-category');
            const isChecked = this.checked;

            document.querySelectorAll(`.permission-checkbox[data-category="${category}"]`)
                .forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
        });
    });
});
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection
