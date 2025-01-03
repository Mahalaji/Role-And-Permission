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
        width: 50vw;
        padding: 30px;
        border-radius: 10px;
        height: fit-content;
        margin-left: 200px;
    }

    form {
        margin-left: 20px;
    }

    .rows {
        text-align: center;
    }

    .sidebar {
        height: fit-content; 
        min-height: 120vh; 
    }

    .hidden {
        display: none;
    }

    .row {
        max-height: 70vh; 
        overflow-y: auto; 
        padding: 10px; 
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

    <div style="margin-bottom: 20px;">
        <input type="text" id="searchInput" class="form-control" placeholder="Search for modules or permissions..." style="display: inline-block; width: 80%;">
        <button id="searchButton" class="btn btn-primary" style="margin-top: -5px;display: inline-block;width: 13%;">Search</button>
    </div>

    <form method="POST" action="{{ route('roles.updateAccess', ['roleId' => $roleId]) }}">
        @csrf
        <ul>
            <div class="row">
                @foreach($modules as $module)
                <div class="col-lg-12 module" id="module-{{ $module['module_name'] }}">
                    <div class="form-group mb-3">
                        <div style="margin-top: 5px;">
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
                                    <div class="permission">
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
                                        <div class="module">
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
                                                    <div class="permission">
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
    document.getElementById('searchButton').addEventListener('click', function() {
        var searchQuery = document.getElementById('searchInput').value.toLowerCase(); 
        var modules = document.querySelectorAll('.module'); 

        modules.forEach(function(module) {
            var moduleText = module.textContent.toLowerCase(); // Get module text
            var childModules = module.querySelectorAll('.module'); // Get child modules
            var permissions = module.querySelectorAll('.permission'); // Get permissions in this module

            // Check if the module itself or any of its child modules/permissions matches the search query
            var isMatch = moduleText.includes(searchQuery);

            // Loop through child modules and check if they match the search query
            childModules.forEach(function(childModule) {
                var childModuleText = childModule.textContent.toLowerCase(); // Get child module text
                if (childModuleText.includes(searchQuery)) {
                    childModule.style.display = 'block'; // Show the child module if it matches
                    isMatch = true; // Mark as match if a child module matches
                } else {
                    childModule.style.display = 'none'; // Hide the child module if it doesn't match
                }
            });

            // Loop through permissions within the module and hide/show them based on search query
            permissions.forEach(function(permission) {
                var permissionText = permission.textContent.toLowerCase(); // Get permission text
                if (permissionText.includes(searchQuery)) {
                    permission.style.display = 'block'; // Show permission if it matches
                    isMatch = true; // Mark as match if a permission matches
                } else {
                    permission.style.display = 'none'; // Hide permission if it doesn't match
                }
            });

            if (isMatch) {
                module.style.display = 'block'; 
            } else {
                module.style.display = 'none'; 
            }
        });
    });

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
<script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.min.js"></script>
@endsection
