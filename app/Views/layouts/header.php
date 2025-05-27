<!--  Header Start -->
<style>
  @media only screen and (max-width: 768px) {
    #navBtn {
      display: none;
    }
  }
</style>
<header class="app-header">
  <nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item d-block d-xl-none">
        <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>
    </ul>
    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
      <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end gap-2" id="headerCollapse"> 
        <li class="nav-item dropdown">
          <a class="nav-link d-flex align-items-center gap-2" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="position-relative">
              <img alt="" width="35" height="35" class="rounded-circle border border-primary" style="background-color: white;">
              <i class="ti ti-user position-absolute top-50 start-50 translate-middle text-primary"></i>
            </div>
            <span class="text-primary fw-normal small">
              <?= strtoupper(esc(auth()->user()->username)); ?>
            </span>
          </a>
        </li>
      </ul>
    </div>
  </nav>
</header>
<!--  Header End -->