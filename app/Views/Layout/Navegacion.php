    <style>
        .navbar ul li:last-child:hover::before {
            width: 100%;
        }
    </style>

    <!-- Navegacion -->
    <header>
        <nav class="navbar navbar-dashboard" id="navbar" style="background: #f8f9fa;">
            <a href="<?= site_url('dashboard'); ?>">
                <img src="<?= base_url('public/assets/img/logoletranegra.png'); ?>" class="logo-green-thinking" alt="" id="">
            </a>
            <ul>
                <?php if (isset($session_data['id_rol']) && $session_data['id_rol'] == 1): ?>
                    <li>
                        <a href="<?= site_url('usuario'); ?>" style="color: #000;">
                            <i class="fas fa-users"></i> Usuarios
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="<?= site_url('galeria'); ?>" style="color: #000;">
                        <i class="fas fa-images"></i> Galería
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('productos'); ?>" style="color: #000;">
                        <i class="fas fa-money-bill-wave"></i> Productos
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('solicitudes'); ?>" style="color: #000;">
                        <i class="fas fa-id-card-alt"></i> Solicitudes
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('login/logout'); ?>" style="color: #000;">
                        <i class="fas fa-sign-out-alt"></i> Log Out
                    </a>
                </li>
            </ul>

            <!-- Icono Menu Mobile -->
            <div class="menu-mobile" id="menu-mobile">
                <span class="hamburger" style="background-color: #000;"></span>
                <span class="hamburger" style="background-color: #000;"></span>
                <span class="hamburger" style="background-color: #000;"></span>
            </div>
        </nav>

        <!-- Info Usuario -->
        <div class="user-info">
            <img src="<?= base_url('public/assets/img/user.png'); ?>">
            <p><?= $session_data['usuario']; ?></p>
        </div>

        <!-- SideBar Mobile -->
        <div class="sidebar" id="sidebar">
            <ul>
                <hr>
                <li style="height: 100px;">

                </li>
                <?php if (isset($session_data['id_rol']) && $session_data['id_rol'] == 1): ?>
                    <hr>
                    <li>
                        <a href="<?= site_url('usuario'); ?>" style="color: #000;">
                            <i class="fas fa-users"></i> Usuarios
                        </a>
                    </li>
                <?php endif; ?>
                <hr>
                <li>
                    <a href="<?= site_url('galeria'); ?>">
                        <i class="fas fa-images"></i> Galería
                    </a>
                </li>
                <hr>
                <li>
                    <a href="<?= site_url('productos'); ?>" style="color: #000;">
                        <i class="fas fa-money-bill-wave"></i> Productos
                    </a>
                </li>
                <hr>
                <li>
                    <a href="<?= site_url('solicitudes'); ?>" style="color: #000;">
                        <i class="fas fa-id-card-alt"></i> Solicitudes
                    </a>
                </li>
                <hr>
                <li>
                    <a href="<?= site_url('login/logout'); ?>">
                        <i class="fas fa-sign-out-alt"></i> Log Out
                    </a>
                </li>
                <hr>
            </ul>
        </div>
        
        <div class="bg-sidebar" id="bg-sidebar">
        </div>
    </header>

    <!-- Seccion Admin -->
    <section class="admin-section" id="admin">