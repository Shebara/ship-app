<header class="navigation">
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <a class="navbar-brand" href="" title="Homepage">
            <img src="<?php echo $assets ?>images/home.svg" alt="Homepage" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavigation" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavigation">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?php if ( $page === 'index' ) echo 'active' ?>">
                    <a class="nav-link" href="">Home</a>
                </li>
                <li id="admin-link" class="nav-item <?php if ( $page === 'admin' ) echo 'active' ?><?php if ( ! $admin ) echo ' d-none' ?>">
                    <a class="nav-link" id="adminLink" href="admin">Administration</a>
                </li>
            </ul>
            <div class="my-2 my-lg-0">
                <ul class="navbar-nav mr-auto">
                    <li id="loggedOutMenu" class="nav-item dropdown<?php if ( $loggedIn ) echo ' d-none' ?>">
                        <a class="nav-link dropdown-toggle" href="#" id="loginDropdown"
                           role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Not logged in
                        </a>
                        <div class="dropdown-menu" aria-labelledby="loginDropdown">
                            <a class="dropdown-item" href="login">Log In</a>
                        </div>
                    </li>
                    <li id="loggedInMenu" class="nav-item dropdown<?php if ( ! $loggedIn ) echo ' d-none' ?>">
                        <a class="nav-link dropdown-toggle" href="#" id="logoutDropdown"
                           role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            You
                        </a>
                        <div class="dropdown-menu" aria-labelledby="logoutDropdown">
                            <a class="dropdown-item" href="profile">Your Profile</a>
                            <a class="dropdown-item" href="edit">Edit Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout">Log Out</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
