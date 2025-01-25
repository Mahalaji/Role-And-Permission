    @extends('Backend.layouts.app')
    <link rel="stylesheet" href="{{ asset('css/Backend/blog.css') }}">
    @section('content')
<h1>Test List</h1>
  <div class="left" >
        <a href="{{ asset('/Test/create') }}">Add-Test</a>
    </div>
<table id="Table" class="table table-bordered">
    <thead>
        <tr>
            <th>Id</th><th>Title</th><th>Created_at</th><th>Updated_at</th>
        <th>Edit</th>
        <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <tr><td>1</td><td>Health</td><td>2025-01-20 09:40:57</td><td>2025-01-20 09:40:57</td><td>
                <a href='/Test/edit/1' class='btn btn-primary btn-sm' style='background: azure; border-radius: 7px; border: 1px solid grey; color: black;'>
                    <i class='fas fa-edit'></i> Edit
                </a>
            </td><td>
                <form action='/Test/delete/1' method='POST' style='display:inline;'>
                    <button type='submit' class='btn btn-sm' style='background: azure; border-radius: 7px; border: 1px solid grey; color: black;'>
                        <i class='fas fa-trash'></i> Delete
                    </button>
                </form>
            </td></tr><tr><td>2</td><td>Education</td><td>2025-01-20 09:41:15</td><td>2025-01-20 09:41:15</td><td>
                <a href='/Test/edit/2' class='btn btn-primary btn-sm' style='background: azure; border-radius: 7px; border: 1px solid grey; color: black;'>
                    <i class='fas fa-edit'></i> Edit
                </a>
            </td><td>
                <form action='/Test/delete/2' method='POST' style='display:inline;'>
                    <button type='submit' class='btn btn-sm' style='background: azure; border-radius: 7px; border: 1px solid grey; color: black;'>
                        <i class='fas fa-trash'></i> Delete
                    </button>
                </form>
            </td></tr><tr><td>3</td><td>Photograph</td><td>2025-01-20 09:41:24</td><td>2025-01-20 09:41:52</td><td>
                <a href='/Test/edit/3' class='btn btn-primary btn-sm' style='background: azure; border-radius: 7px; border: 1px solid grey; color: black;'>
                    <i class='fas fa-edit'></i> Edit
                </a>
            </td><td>
                <form action='/Test/delete/3' method='POST' style='display:inline;'>
                    <button type='submit' class='btn btn-sm' style='background: azure; border-radius: 7px; border: 1px solid grey; color: black;'>
                        <i class='fas fa-trash'></i> Delete
                    </button>
                </form>
            </td></tr><tr><td>4</td><td>Travel</td><td>2025-01-20 09:42:05</td><td>2025-01-20 09:42:05</td><td>
                <a href='/Test/edit/4' class='btn btn-primary btn-sm' style='background: azure; border-radius: 7px; border: 1px solid grey; color: black;'>
                    <i class='fas fa-edit'></i> Edit
                </a>
            </td><td>
                <form action='/Test/delete/4' method='POST' style='display:inline;'>
                    <button type='submit' class='btn btn-sm' style='background: azure; border-radius: 7px; border: 1px solid grey; color: black;'>
                        <i class='fas fa-trash'></i> Delete
                    </button>
                </form>
            </td></tr>
    </tbody>
</table>
@endsection