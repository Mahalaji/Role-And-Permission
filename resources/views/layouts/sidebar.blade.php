<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Sidebar Menu with Internal Toggle</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --bg-color: white;
            --text-color: #333;
            --sidebar-bg: #ffffff;
            --sidebar-hover: #e6f0ff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--text-color);
        }

        .sidebar {
            height: 100vh;
            /* Set height to viewport height */
            width: 300px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            overflow-y: auto;
            /* Enable scrolling for overflowing content */
            transition: 0.3s;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            white-space: nowrap;
        }

        /* Hide scrollbar for modern browsers */
        .sidebar::-webkit-scrollbar {
            width: 0;
            /* Remove scrollbar width */
            height: 0;
            /* Remove scrollbar height for horizontal scroll */
        }

        .sidebar {
            scrollbar-width: none;
            /* Firefox: Hide scrollbar */
            -ms-overflow-style: none;
            /* IE 10+ */
        }




        .sidebar-header {
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e0e0e0;
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 1.2em;
            color: var(--primary-color);
        }

        .toggle-btn {
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 20px;
            cursor: pointer;
            transition: 0.2s;
        }

        .toggle-btn:hover {
            color: var(--primary-color);
        }

        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 16px;
            color: var(--text-color);
            display: flex;
            align-items: center;
            transition: 0.2s;
        }

        .sidebar a:hover {
            background-color: var(--sidebar-hover);
            color: var(--primary-color);
        }

        .sidebar a i {
            min-width: 30px;
            font-size: 20px;
        }

        #main {
            transition: margin-left .3s;
            padding: 20px;
            margin-left: 250px;
        }

        .sidebar.closed {
            width: 65px;
        }

        .sidebar.closed .sidebar-header h3 {
            display: none;
        }

        .sidebar.closed a span {
            display: none;
        }

        .sidebar.closed~#main {
            margin-left: 70px;
        }

        .menu,
        .menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-toggle-icon {
            margin-left: auto;
            font-size: 16px;
        }

        @media screen and (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar .sidebar-header h3 {
                display: none;
            }

            .sidebar a span {
                display: none;
            }

            #main {
                margin-left: 70px;
            }

            .sidebar.open {
                width: 250px;
            }

            .sidebar.open .sidebar-header h3 {
                display: block;
            }

            .sidebar.open a span {
                display: inline;
            }

            .sidebar.open~#main {
                margin-left: 250px;
            }
        }
    </style>
</head>

<body>
    <div id="mySidebar" class="sidebar">
        <div class="sidebar-header">
            <h3>Menu</h3>
            <button class="toggle-btn" onclick="toggleNav()">
                <i class="fas fa-bars"></i>
            </button>
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
                            <i class="menu-toggle-icon fas fa-caret-down" style="padding-left: 50%;"></i>
                            <!-- Dropdown Icon -->
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
    </div>
    <script>
        function toggleNav() {
            const sidebar = document.getElementById("mySidebar");
            const main = document.getElementById("main");
            sidebar.classList.toggle("closed");
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle("open");
            }
        }
    </script>
    <script>
        // Toggle the dropdown visibility when clicking on a parent menu item
        document.querySelectorAll('.menu-toggle').forEach(function (button) {
            button.addEventListener('click', function (e) {
                const parentMenuItem = this.closest('.menu-item');
                const submenu = parentMenuItem.querySelector('.menu-sub');

                if (submenu) {
                    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                    parentMenuItem.classList.toggle('open');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            let sidebarwidth = $('#mySidebar').css('width');

            if (sidebarwidth == '300px') {
                console.log('width300');
            }
            $('.toggle-btn').click(function () {
                let width = $('#mySidebar').css('width');
                if (width == '300px') {

                    $('.menu').css('padding-left', '0');
                    $('.menu-toggle').css('padding', '15px 10px');
                    $('.menu-toggle-icon').prev('div').css('display', 'none');
                    $('.menu-item:last').find('div').css('display', 'none')
                    $('.active').find('.menu-sub').css('display', 'none')
                    $('#app').css('margin-left', '-208px');
                    $('.menu-toggle-icon').css('display', 'none');

                } else if (width == '65px') {
                    $('.menu').css('padding-left', '0rem');
                    $('.menu-toggle').css('padding', '15px 25px');
                    $('.menu-toggle-icon').prev('div').css('display', 'block');
                    $('.menu-item:last').find('div').css('display', 'block');
                    $('.active').find('.menu-sub').css('display', 'block')
                    $('#app').css('margin-left', '24px');
                    $('.menu-toggle-icon').css('display', 'block');

                }

            });

        });
    </script>