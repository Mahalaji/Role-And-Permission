<link rel="stylesheet" href="{{ asset('css/blogsite/header.css') }}">
<div class="header">
    <nav>
        <ul>
            <li>
                <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">Home</a>
            </li>
            <li>
                <a href="/blogs" class="{{ request()->is('blogs') ? 'active' : '' }}">Blogs</a>
            </li>
            <li>
                <a href="/news" class="{{ request()->is('news') ? 'active' : '' }}">News</a>
            </li>
        </ul>
    </nav>
    <div class="sides">
        <a href="#" class="menu"></a>
    </div>
    <div class="info">
        <h4><a href="#category">Welcome</a></h4>
        <h1>Blogs share opinions, News reports facts.</h1>
        <div class="meta">
            <a href="https://twitter.com/nodws" target="_b" class="author"></a><br>
            By <a href="https://twitter.com/nodws" target="_b">Mahala ji</a> on Dec 10, 2024
        </div>
    </div>
</div>
