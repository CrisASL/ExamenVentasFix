<!-- Sidebar -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" style="width: 180px;">
  <div class="app-brand demo">
    <a href="/" class="app-brand-link">
      <span class="app-brand-text demo menu-text fw-bold ms-2"></span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="ti ti-x ti-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
      <a href="{{ route('dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-home"></i>
        <div>Dashboard</div>
      </a>
    </li>

    <!-- Usuarios -->
    <li class="menu-item {{ request()->is('usuarios*') ? 'active' : '' }}">
      <a href="{{ route('usuarios.webIndex') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-users"></i>
        <div>Usuarios</div>
      </a>
    </li>

    <!-- Productos -->
    <li class="menu-item {{ request()->is('productos*') ? 'active' : '' }}">
      <a href="{{ route('productos.webIndex') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-box"></i>
        <div>Productos</div>
      </a>
    </li>

    <!-- Clientes -->
    <li class="menu-item {{ request()->is('clientes*') ? 'active' : '' }}">
      <a href="{{ route('clientes.webIndex') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-user"></i>
        <div>Clientes</div>
      </a>
    </li>
  </ul>
</aside>
<!-- /Sidebar -->

