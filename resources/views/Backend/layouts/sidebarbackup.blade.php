<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <style>
    .menu-sub {
        display: none;
        list-style: none;
        padding-left: 20px;
    }

    .menu-item.open>.menu-sub {
        display: block;
    }

    .menu-item.active>.menu-link {
        font-weight: bold;
        color: gray;
    }
    a:hover{
        color: gray !important;
    }
    </style>
</head>

<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="sidebar-header">
            </div>
            <nav class="sidebar-nav">
                <div id="img"><img src="https://www.absglobaltravel.com/public/images/footer-abs-logo.webp" height="50"
                        style="padding-left: 53px;">
                </div>
                <ul class="menu">
                    @foreach($menu->json_output as $item)
                    <li
                        class="menu-item {{ request()->routeIs($item['href']) || (isset($item['children']) && collect($item['children'])->pluck('href')->contains(request()->route()->getName())) ? 'open active' : '' }}">
                        <a href="{{ $item['href'] ? route($item['href']) : 'javascript:void(0);' }}"
                            class="menu-link menu-toggle">
                            <i class="menu-icon {{ $item['icon'] ?? 'fas fa-circle' }}"></i>
                            <div data-i18n="{{ $item['title'] ?? '' }}">{{ $item['text'] }}</div>
                            @if(!empty($item['children']))
                            <i class="menu-toggle-icon fas fa-caret-down" style="padding-left: 100px;"></i> <!-- Dropdown Icon -->
                            @endif
                        </a>
                        @if(!empty($item['children']))
                        <ul class="menu-sub"
                            style="display: {{ request()->routeIs($item['href']) || (isset($item['children']) && collect($item['children'])->pluck('href')->contains(request()->route()->getName())) ? 'block' : 'none' }};">
                            @foreach($item['children'] as $child)
                            <li class="menu-item {{ request()->routeIs($child['href']) ? 'active' : '' }}">
                                <a href="{{ $child['href'] ? route($child['href']) : 'javascript:void(0);' }}"
                                    class="menu-link">
                                    <i class="menu-icon {{ $child['icon'] ?? 'fas fa-circle' }}"></i>
                                    <div data-i18n="{{ $child['title'] ?? '' }}">{{ $child['text'] }}</div>
                                </a>
                                @if(!empty($child['children']))
                                <ul class="menu-sub">
                                    @foreach($child['children'] as $subChild)
                                    <li class="menu-item {{ request()->routeIs($subChild['href']) ? 'active' : '' }}">
                                        <a href="{{ $subChild['href'] ? route($subChild['href']) : 'javascript:void(0);' }}"
                                            class="menu-link">
                                            <i class="menu-icon {{ $subChild['icon'] ?? 'fas fa-circle' }}"></i>
                                            <div data-i18n="{{ $subChild['title'] ?? '' }}">{{ $subChild['text'] }}
                                            </div>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                    @endforeach
                </ul>


            </nav>
        </aside>

        <script>
        // Toggle the dropdown visibility when clicking on a parent menu item
        document.querySelectorAll('.menu-toggle').forEach(function(button) {
            button.addEventListener('click', function(e) {
                const parentMenuItem = this.closest('.menu-item');
                const submenu = parentMenuItem.querySelector('.menu-sub');

                if (submenu) {
                    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                    parentMenuItem.classList.toggle('open');
                }
            });
        });
        </script>