<main class="main-content">
            <header class="top-bar">
                <div class="search-bar">
                    <input type="text" placeholder="Search...">
                    <button><i class="fas fa-search"style="padding-right: 20px;"></i></button>
                </div>
                
                <div class="dropdown">
                    <button class="dropbtn">{{ Auth::user()->name }} <i class=' fas fa-angle-down'></i></button>
                    <div class="dropdown-content">
                        <a href="/profileupdate"><i class='fas fa-user'></i>Profile</a>
                        <a href="/changeadminpass"><i class="fas fa-lock"></i> Change Password</a>
                        <a href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a>

                       
                    </div>
                </div>

            </header>