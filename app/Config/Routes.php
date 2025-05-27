<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

$routes = Services::routes();
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->get('/', function() {
    session()->destroy(); 
    return redirect()->to('/login');
});
$routes->get('/book', 'Home::book');

service('auth')->routes($routes);

$routes->get('login', 'LoginController::index');
$routes->post('login', 'LoginController::loginAction'); // kalau ada

$routes->group('admin', ['filter' => 'session'], static function (RouteCollection $routes) {
    $routes->get('/', 'Dashboard\DashboardController');
    $routes->get('dashboard', 'Dashboard\DashboardController::dashboard');
    $routes->get('dashboard/status_po', 'Dashboard\DashboardController::statusPOPage');
    $routes->get('dashboard/empty_alert', 'Dashboard\DashboardController::barangHampirHabisPage');

    // $routes->resource('members', ['controller' => 'Members\MembersController']);
    // $routes->resource('books', ['controller' => 'Books\BooksController']);
    // $routes->resource('categories', ['controller' => 'Books\CategoriesController']);
    // $routes->resource('racks', ['controller' => 'Books\RacksController']);

    // $routes->get('loans/new/members/search', 'Loans\LoansController::searchMember');
    // $routes->get('loans/new/books/search', 'Loans\LoansController::searchBook');
    // $routes->post('loans/new', 'Loans\LoansController::new');
    // $routes->resource('loans', ['controller' => 'Loans\LoansController']);

    // $routes->get('returns/new/search', 'Loans\ReturnsController::searchLoan');
    // $routes->resource('returns', ['controller' => 'Loans\ReturnsController']);

    // $routes->get('fines/returns/search', 'Loans\FinesController::searchReturn');
    // $routes->get('fines/pay/(:any)', 'Loans\FinesController::pay/$1');
    // $routes->resource('fines/settings', ['controller' => 'Loans\FineSettingsController', 'filter' => 'group:admin']);
    // $routes->resource('fines', ['controller' => 'Loans\FinesController']);

    $routes->get('profile', 'Users\UsersController::profile', ['filter' => 'group:user']);

    $routes->group('users', ['filter' => 'group: admin, user'], static function (RouteCollection $routes) {
        $routes->get('(:num)/edit', 'Users\UsersController::edit/$1');
        $routes->put('(:num)', 'Users\UsersController::update/$1');
    });

    $routes->group('users', ['filter' => 'group:admin'], static function (RouteCollection $routes) {
        $routes->get('new', 'Users\RegisterController::index');
        $routes->post('', 'Users\RegisterController::registerAction');
    });
    $routes->resource('users', ['controller' => 'Users\UsersController', 'filter' => 'group:admin']);

    $routes->get('/admin/dashboard', 'AdminController::dashboard', ['filter' => 'auth']);

    $routes->get('supplier', 'Supplier\SupplierController::index');
    $routes->get('supplier/create', 'Supplier\SupplierController::create');
    $routes->post('supplier/store', 'Supplier\SupplierController::store');
    $routes->get('supplier/(:num)/edit', 'Supplier\SupplierController::edit/$1');
    $routes->post('supplier/(:num)/update', 'Supplier\SupplierController::update/$1');
    $routes->delete('supplier/(:num)', 'Supplier\SupplierController::delete/$1');
    $routes->get('supplier/search', 'Supplier\SupplierController::search');

    $routes->get('ketSupplier', 'Supplier\KetSupplierController::index');
    $routes->get('ketSupplier/create', 'Supplier\KetSupplierController::create');
    $routes->post('ketSupplier/store', 'Supplier\KetSupplierController::store');
    $routes->delete('ketSupplier/(:num)', 'Supplier\KetSupplierController::delete/$1');
    $routes->get('ketSupplier/(:num)/edit', 'Supplier\KetSupplierController::edit/$1');
    $routes->post('ketSupplier/(:num)/update', 'Supplier\KetSupplierController::update/$1');
 
    $routes->get('grup', 'KategoriKode\GrupController::index');
    $routes->get('grup/create', 'KategoriKode\GrupController::create');
    $routes->post('grup/store', 'KategoriKode\GrupController::store');
    $routes->delete('grup/(:num)', 'KategoriKode\GrupController::delete/$1');
    $routes->get('grup/(:num)/edit', 'KategoriKode\GrupController::edit/$1');
    $routes->post('grup/(:num)/update', 'KategoriKode\GrupController::update/$1');

    // Rute untuk data warna
    $routes->get('warna', 'KategoriKode\WarnaController::index'); // Menampilkan daftar warna
    $routes->get('warna/create', 'KategoriKode\WarnaController::create'); // Menampilkan form untuk menambah warna
    $routes->post('warna/store', 'KategoriKode\WarnaController::store'); // Menyimpan warna baru
    $routes->get('warna/(:num)/edit', 'KategoriKode\WarnaController::edit/$1'); // Menampilkan form untuk mengedit warna berdasarkan ID
    $routes->post('warna/(:num)/update', 'KategoriKode\WarnaController::update/$1'); // Menyimpan perubahan warna berdasarkan ID
    $routes->delete('warna/(:num)', 'KategoriKode\WarnaController::delete/$1'); // Menghapus warna berdasarkan ID

    // Rute untuk data bahan
    $routes->get('bahan', 'KategoriKode\BahanController::index');
    $routes->get('bahan/create', 'KategoriKode\BahanController::create'); // Menampilkan form untuk menambah warna
    $routes->post('bahan/store', 'KategoriKode\BahanController::store'); // Menyimpan warna baru
    $routes->get('bahan/(:num)/edit', 'KategoriKode\BahanController::edit/$1');
    $routes->post('bahan/(:num)/update', 'KategoriKode\BahanController::update/$1');
    $routes->delete('bahan/(:num)', 'KategoriKode\BahanController::delete/$1'); // Menghapus warna berdasarkan ID

    // Rute untuk data satuan
    $routes->get('satuan', 'KategoriKode\SatuanController::index');
    $routes->get('satuan/create', 'KategoriKode\SatuanController::create'); // Menampilkan form untuk menambah warna
    $routes->post('satuan/store', 'KategoriKode\SatuanController::store'); // Menyimpan warna baru
    $routes->get('satuan/(:num)/edit', 'KategoriKode\SatuanController::edit/$1');
    $routes->post('satuan/(:num)/update', 'KategoriKode\SatuanController::update/$1');
    $routes->delete('satuan/(:num)', 'KategoriKode\SatuanController::delete/$1'); // Menghapus warna berdasarkan ID

    $routes->get('stok_bahan', 'StokBahan\StokBahanController::index');
    $routes->get('stok_bahan/create', 'StokBahan\StokBahanController::create');
    $routes->post('stok_bahan/store', 'StokBahan\StokBahanController::store');
    $routes->get('stok_bahan/(:num)/edit', 'StokBahan\StokBahanController::edit/$1');
    $routes->post('stok_bahan/(:num)/update', 'StokBahan\StokBahanController::update/$1');
    $routes->delete('stok_bahan/(:num)', 'StokBahan\StokBahanController::delete/$1');
    $routes->get('stok_bahan/download', 'StokBahan\StokBahanController::downloadExcel');
    $routes->get('stok_bahan/download_detail_excel', 'StokBahan\StokBahanController::downloadDetailExcel');
    $routes->get('stok_bahan/show', 'StokBahan\StokBahanController::show');
    $routes->get('stok_bahan/searchGrup', 'StokBahan\StokBahanController::searchGrup');
    $routes->get('stok_bahan/searchBahan', 'StokBahan\StokBahanController::searchBahan');
    $routes->get('stok_bahan/searchWarna', 'StokBahan\StokBahanController::searchWarna');
    $routes->get('stok_bahan/searchSatuan', 'StokBahan\StokBahanController::searchSatuan');
    $routes->get('stok_bahan/searchSupplier', 'StokBahan\StokBahanController::searchSupplier');

    $routes->get('purchase_order', 'PurchaseOrder\PurchaseOrderController::index');
    $routes->get('purchase_order/(:num)', 'PurchaseOrder\PurchaseOrderController::show/$1');
    $routes->get('purchase_order/create', 'PurchaseOrder\PurchaseOrderController::create');
    $routes->post('purchase_order/store', 'PurchaseOrder\PurchaseOrderController::store');
    $routes->get('purchase_order/(:num)/edit', 'PurchaseOrder\PurchaseOrderController::edit/$1');
    $routes->post('purchase_order/(:num)/update', 'PurchaseOrder\PurchaseOrderController::update/$1');
    $routes->delete('purchase_order/(:num)/delete', 'PurchaseOrder\PurchaseOrderController::delete/$1');
    $routes->get('purchase_order/download', 'PurchaseOrder\PurchaseOrderController::downloadExcel');
    $routes->get('purchase_order/print', 'PurchaseOrder\PurchaseOrderController::printAll');
    $routes->get('purchase_order/(:num)/print', 'PurchaseOrder\PurchaseOrderController::printOne/$1');
    $routes->get('purchase_order/stok_by_supplier/(:num)', 'PurchaseOrder\PurchaseOrderController::stokBySupplier/$1');
    $routes->get('purchase_order/search_supplier', 'PurchaseOrder\PurchaseOrderController::search_supplier');
    $routes->get('purchase_order/search_stok', 'PurchaseOrder\PurchaseOrderController::search_stok');

    $routes->get('penerimaan_pembelian', 'PenerimaanPembelian\PenerimaanController::index');
    $routes->get('penerimaan_pembelian/(:num)', 'PenerimaanPembelian\PenerimaanController::show/$1');
    $routes->get('penerimaan_pembelian/create', 'PenerimaanPembelian\PenerimaanController::create');
    $routes->post('penerimaan_pembelian/store', 'PenerimaanPembelian\PenerimaanController::store');
    $routes->get('penerimaan_pembelian/(:num)/edit', 'PenerimaanPembelian\PenerimaanController::edit/$1');
    $routes->post('penerimaan_pembelian/(:num)/update', 'PenerimaanPembelian\PenerimaanController::update/$1');
    $routes->delete('penerimaan_pembelian/(:num)/delete', 'PenerimaanPembelian\PenerimaanController::delete/$1');
    $routes->get('penerimaan_pembelian/download', 'PenerimaanPembelian\PenerimaanController::downloadExcel');
    $routes->get('penerimaan_pembelian/print', 'PenerimaanPembelian\PenerimaanController::printAll');
    $routes->get('penerimaan_pembelian/(:num)/print', 'PenerimaanPembelian\PenerimaanController::printOne/$1');
    $routes->get('penerimaan_pembelian/stok_by_po/(:num)', 'PenerimaanPembelian\PenerimaanController::stokByPO/$1');
    $routes->get('penerimaan_pembelian/search_supplier', 'PenerimaanPembelian\PenerimaanController::search_supplier');
    $routes->get('penerimaan_pembelian/po_by_supplier/(:num)', 'PenerimaanPembelian\PenerimaanController::poBySupplier/$1');
    $routes->get('penerimaan_pembelian/search_stok', 'PenerimaanPembelian\PenerimaanController::search_stok');

    $routes->get('penerimaan_pembelian/lot_rol', 'PenerimaanPembelian\LotRolPenerimaanController::index');
    $routes->get('penerimaan_pembelian/lot_rol/(:num)/(:num)', 'PenerimaanPembelian\LotRolPenerimaanController::show/$1/$2');
    $routes->get('penerimaan_pembelian/lot_rol/create', 'PenerimaanPembelian\LotRolPenerimaanController::create');
    $routes->post('penerimaan_pembelian/lot_rol/store', 'PenerimaanPembelian\LotRolPenerimaanController::store');
    $routes->get('penerimaan_pembelian/lot_rol/(:num)/edit', 'PenerimaanPembelian\LotRolPenerimaanController::edit/$1');
    $routes->post('penerimaan_pembelian/lot_rol/(:num)/update', 'PenerimaanPembelian\LotRolPenerimaanController::update/$1');

    $routes->get('retur', 'Retur\ReturController::index');
    $routes->get('retur/(:num)', 'Retur\ReturController::show/$1');
    $routes->get('retur/create', 'Retur\ReturController::create');
    $routes->post('retur/store', 'Retur\ReturController::store');
    $routes->get('retur/(:num)/edit', 'Retur\ReturController::edit/$1');
    $routes->post('retur/(:num)/update', 'Retur\ReturController::update/$1');
    $routes->delete('retur/(:num)/delete', 'Retur\ReturController::delete/$1');
    $routes->get('retur/print', 'Retur\ReturController::printAll');
    $routes->get('retur/(:num)/print', 'Retur\ReturController::printOne/$1');
    $routes->get('retur/download', 'Retur\ReturController::downloadExcel');
    $routes->get('retur/search_supplier', 'Retur\ReturController::search_supplier');
    $routes->get('retur/search_stok', 'Retur\ReturController::search_stok');

    $routes->get('retur/lot_rol', 'Retur\LotRolReturController::index');
    $routes->get('retur/lot_rol/(:num)/(:num)', 'Retur\LotRolReturController::show/$1/$2');
    $routes->get('retur/lot_rol/create', 'Retur\LotRolReturController::create');
    $routes->post('retur/lot_rol/store', 'Retur\LotRolReturController::store');
    $routes->get('retur/lot_rol/(:num)/edit', 'Retur\LotRolReturController::edit/$1');
    $routes->post('retur/lot_rol/(:num)/update', 'Retur\LotRolReturController::update/$1');
    $routes->get('retur/lot_rol/edit_by_retur/(:num)/(:num)', 'Retur\LotRolReturController::edit_by_retur/$1/$2');

    $routes->get('pemakaian', 'Pemakaian\PemakaianController::index');
    $routes->get('pemakaian/(:num)', 'Pemakaian\PemakaianController::show/$1');
    $routes->get('pemakaian/create', 'Pemakaian\PemakaianController::create');
    $routes->post('pemakaian/store', 'Pemakaian\PemakaianController::store');
    $routes->get('pemakaian/(:num)/edit', 'Pemakaian\PemakaianController::edit/$1');
    $routes->post('pemakaian/(:num)/update', 'Pemakaian\PemakaianController::update/$1');
    $routes->delete('pemakaian/(:num)/delete', 'Pemakaian\PemakaianController::delete/$1');
    $routes->get('pemakaian/download', 'Pemakaian\PemakaianController::downloadExcel');
    $routes->get('pemakaian/search_supplier', 'Pemakaian\PemakaianController::search_supplier');
    $routes->get('pemakaian/search_stok', 'Pemakaian\PemakaianController::search_stok');

    $routes->get('pemakaian/lot_rol', 'Pemakaian\LotRolPemakaianController::index');
    $routes->get('pemakaian/lot_rol/(:num)/(:num)', 'Pemakaian\LotRolPemakaianController::show/$1/$2');
    $routes->get('pemakaian/lot_rol/create', 'Pemakaian\LotRolPemakaianController::create');
    $routes->post('pemakaian/lot_rol/store', 'Pemakaian\LotRolPemakaianController::store');
    $routes->get('pemakaian/lot_rol/(:num)/edit', 'Pemakaian\LotRolPemakaianController::edit/$1');
    $routes->post('pemakaian/lot_rol/(:num)/update', 'Pemakaian\LotRolPemakaianController::update/$1');
    $routes->get('pemakaian/print', 'Pemakaian\PemakaianController::printAll');
    $routes->get('pemakaian/(:num)/print', 'Pemakaian\PemakaianController::printOne/$1');
    $routes->get('pemakaian/lot_rol/edit_by_pemakaian/(:num)/(:num)', 'Pemakaian\LotRolPemakaianController::edit_by_pemakaian/$1/$2');
    
});

$routes->get('users/profile', 'Users\UsersController::profile', ['filter' => 'group:admin,user']);

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
