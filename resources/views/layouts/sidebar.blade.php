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

</head>

<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="sidebar-header">
            </div>
            <nav class="sidebar-nav">
                <div id="img"><img src="https://www.absglobaltravel.com/public/images/footer-abs-logo.webp" height="50" style="padding-left: 53px;">
                </div>
                <ul>
                   
                    <li>
                    @auth
                    @if(auth()->user()->hasRole(['Admin','Blog_Team']))
                        <button class="dropdown-btn"><i class="fas fa-blog"></i>Blogs
                            <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="dropdown-container">
                            <a href="/blog"><i class="fas fa-blog"></i> Blog List</a>
                            <a href="/blogcategory"><i class="fa fa-list"></i> Category</a>
                        </div>
                    </li>
                    @endif
                    @endauth
                    @auth
                    @if(auth()->user()->hasRole(['Admin','News_Team']))
                    <li>
                        <button class="dropdown-btn"><i class="fas fa-newspaper"></i>News
                            <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="dropdown-container">
                            <a href="/newss"><i class="fas fa-newspaper"></i> News List</a>
                            <a href="/newscategory"><i class="fa fa-list"></i> Category</a>
                        </div>
                    </li>
                    @endif
                    @endauth
                    @auth
                    @if(auth()->user()->hasRole(['Admin','Page_Team']))
                    <li><a href="/pages"><i class="fa fa-copy"></i> Pages</a></li>
                    @endif
                    @endauth
                    @auth
                    @if(auth()->user()->hasRole('Admin'))
                    <li>
                        <button class="dropdown-btn"><i class="fa-solid fa-gear"></i>Setting
                            <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="dropdown-container">
                            <a href="/module"><i class="fa-brands fa-pinterest"></i> Module</a>
                            <a href="{{ route('roles.index') }}"><i class="fa-brands fa-critical-role"></i> Manage Role</a>
                            <a href="/company"><i class="far fa-address-book"></i> Company Profile</a>
                            <a href="/menu"><i class="fa-solid fa-bars"></i> Menu</a>

                        </div>
                    </li>
                    <li><a href="{{ route('users.index') }}"><i class="fas fa-users"></i>Manage User</a></li>
                    <li><a href="{{ route('products.index') }}"><i class="fa-solid fa-list"></i>Manage Product</a></li>
                    @endif
                    @endauth

                </ul>
            </nav>
        </aside>

        <script>
        var dropdown = document.getElementsByClassName("dropdown-btn");
        var i;

        for (i = 0; i < dropdown.length; i++) {
            dropdown[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var dropdownContent = this.nextElementSibling;
                if (dropdownContent.style.display === "block") {
                    dropdownContent.style.display = "none";
                } else {
                    dropdownContent.style.display = "block";
                }
            });
        }
        </script>