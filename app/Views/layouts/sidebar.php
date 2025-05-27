<?php
$currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$sidebarNavs =
  [
    'Home',
    [
      'name' => 'Dashboard',
      'link' => '/admin/dashboard',
      'icon' => 'ti ti-layout-dashboard'
    ],
    'Transaksi',
    [
      'name' => 'Purchase Order',
      'link' => '/admin/purchase_order',
      'icon' => 'ti ti-arrows-exchange'
    ],
    [
      'name' => 'Penerimaan Pembelian',
      'link' => '/admin/penerimaan_pembelian',
      'icon' => 'ti ti-check'
    ],
    [
      'name' => 'Retur Pembelian',
      'link' => '/admin/retur',
      'icon' => 'ti ti-truck-return'
    ],
    [
      'name' => 'Pemakaian Bahan',
      'link' => '/admin/pemakaian',
      'icon' => 'ti ti-packages'
    ],
    'Master',
    [
      'name' => 'Stok Bahan',
      'link' => '/admin/stok_bahan',
      'icon' => 'ti ti-table'
    ],
    ... (auth()->user()->inGroup('admin') ? [
      [
        'name' => 'Supplier',
        'link' => '/admin/supplier',
        'icon' => 'ti ti-list'
      ],
      [
        'name' => 'Kategori Kode',
        'icon' => 'ti ti-category-2',
        'link' => '#',
        'submenu' => [
          [
            'name' => 'Grup',
            'icon' => 'ti ti-stack-2',
            'link' => '/admin/grup'
          ],
          [
            'name' => 'Satuan',
            'icon' => 'ti ti-package',
            'link' => '/admin/satuan'
          ],
          [
            'name' => 'Warna',
            'icon' => 'ti ti-palette',
            'link' => '/admin/warna'
          ],
          [
            'name' => 'Bahan',
            'icon' => 'ti ti-brand-polymer',
            'link' => '/admin/bahan'
          ],
          // [
          //   'name' => 'Keterangan Supplier',
          //   'icon' => 'ti ti-file-info',
          //   'link' => '/admin/ketSupplier'
          // ]
        ]
      ]
    ] : [])
  ];

if (auth()->user()->inGroup('admin') ?? false) {
  $sidebarNavs = array_merge(
    $sidebarNavs,
    [
      'Kelola Akun',
      [
        'name' => 'Kelola Pengguna',
        'link' => '/admin/users',
        'icon' => 'ti ti-user-cog'
      ],
      [
        'name' => 'Keluar',
        'link' => '#',
        'icon' => 'ti ti-logout-2',
        'onclick' => 'return confirmLogout()'
      ]      
    ]
  );
}
else if (auth()->user()->inGroup('user') ?? false) {
  $sidebarNavs = array_merge(
    $sidebarNavs,
    [
      'Kelola Akun',
      [
        'name' => 'Profil',
        'link' => '/admin/profile',
        'icon' => 'ti ti-user-circle'
      ],
      [
        'name' => 'Keluar',
        'link' => '#',
        'icon' => 'ti ti-logout-2',
        'onclick' => 'return confirmLogout()'
      ]
    ]
  );
}
?>

<!-- Sidebar Start -->
<aside class="left-sidebar">
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <div class="pt-4 mx-auto">
        <a href="<?= base_url(); ?>">
          <img src="<?= base_url('assets/images/akusara-removebg.png'); ?>" alt="Logo Akusara" class="img-fluid" style="max-height: 100px; width: auto;">
        </a>
      </div>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-8"></i>
      </div>
    </div>

    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav">
        <?php foreach ($sidebarNavs as $nav) : ?>
          <?php if (is_string($nav)) : ?>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu"><?= $nav; ?></span>
            </li>
          <?php else : ?>
            <?php
              $isActive = isset($nav['link']) && $nav['link'] !== '#' && rtrim($currentUrl, '/') === rtrim($nav['link'], '/');
              $hasActiveSub = false;
              if (isset($nav['submenu'])) {
                foreach ($nav['submenu'] as $sub) {
                  if (str_starts_with($currentUrl, rtrim($sub['link'], '/'))) {
                    $hasActiveSub = true;
                    break;
                  }
                }
              }
            ?>
            <li class="sidebar-item <?= $isActive || $hasActiveSub ? 'active' : '' ?> <?= isset($nav['submenu']) ? 'has-submenu' : '' ?>">
              <a class="sidebar-link <?= isset($nav['submenu']) ? 'dropdown-toggle' : '' ?>" 
                href="<?= isset($nav['link']) ? base_url($nav['link']) : '#' ?>" 
                <?= isset($nav['onclick']) ? 'onclick="' . $nav['onclick'] . '"' : '' ?>
                <?= isset($nav['submenu']) ? 'data-bs-toggle="collapse" data-bs-target="#submenu-' . strtolower(str_replace(' ', '-', $nav['name'])) . '"' : '' ?>>
                <span>
                  <i class="<?= $nav['icon']; ?>"></i>
                </span>
                <span class="hide-menu"><?= $nav['name']; ?></span>
              </a>
              <?php if (isset($nav['submenu'])) : ?>
                <ul class="collapse sidebar-submenu <?= $hasActiveSub ? 'show' : '' ?>" id="submenu-<?= strtolower(str_replace(' ', '-', $nav['name'])) ?>">
                  <?php foreach ($nav['submenu'] as $sub) : ?>
                    <?php $isSubActive = isset($sub['link']) && rtrim($currentUrl, '/') === rtrim($sub['link'], '/'); ?>
                    <li class="sidebar-item <?= $isSubActive ? 'active' : '' ?>">
                      <a class="sidebar-link" href="<?= base_url($sub['link']) ?>">
                        <span>
                          <i class="<?= $sub['icon']; ?>"></i>
                        </span>
                        <span class="hide-menu"><?= $sub['name']; ?></span>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            </li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>
    </nav>
    <!-- End Sidebar navigation -->
  </div>
</aside>
<!-- Sidebar End -->

<!-- Modal Konfirmasi Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Konfirmasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        Yakin ingin keluar dari akun ini?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
        <a href="<?= base_url('logout'); ?>" class="btn btn-primary">Ya, Keluar</a>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript untuk trigger modal -->
<script>
  function confirmLogout() {
    const modal = new bootstrap.Modal(document.getElementById('logoutModal'));
    modal.show();
    return false; // cegah href berjalan
  }
</script>