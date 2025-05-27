<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Daftar Akun</title>

<style>
  input::placeholder {
    text-transform: none;
  }
</style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container d-flex justify-content-center p-5">
  <div class="card col-12 col-md-5 shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-5 text-center">Daftar Akun</h5>

      <?php if (session('error') !== null) : ?>
        <div class="alert alert-danger" role="alert"><?= session('error') ?></div>
      <?php elseif (session('errors') !== null) : ?>
        <div class="alert alert-danger" role="alert">
          <?php if (is_array(session('errors'))) : ?>
            <?php foreach (session('errors') as $error) : ?>
              <?= $error ?>
              <br>
            <?php endforeach ?>
          <?php else : ?>
            <?= session('errors') ?>
          <?php endif ?>
        </div>
      <?php endif ?> 

      <form action="<?= base_url('admin/users') ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-2">
          <input type="text" class="form-control" name="username" placeholder="Nama Pengguna" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" required />
        </div>
        <div class="form-floating mb-2">
    <select class="form-select" id="floatingRoleSelect" name="role" required>
        <option value="" selected disabled>-- Pilih Role --</option>
        <?php foreach ($roles as $role): ?>
            <option value="<?= esc($role['name']) ?>" <?= old('role') === $role['name'] ? 'selected' : '' ?>>
                <?= esc(ucwords($role['name'])) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <label for="floatingRoleSelect">Pilih Role</label>
</div>
        <div class="mb-2">
          <input type="password" class="form-control" name="password" placeholder="Kata Sandi" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();"  required />
        </div>
        <div class="mb-5">
          <input type="password" class="form-control" name="password_confirm" placeholder="Konfirmasi Kata Sandi" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();"  required />
        </div>

        <div class="d-grid col-12 mx-auto m-3">
          <button type="submit" class="btn btn-primary btn-block">Daftar</button>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
  document.querySelectorAll('input').forEach(input => {
    const originalPlaceholder = input.placeholder;
    input.addEventListener('focus', () => input.placeholder = '');
    input.addEventListener('blur', () => input.placeholder = originalPlaceholder);
  });
</script>

<?= $this->endSection() ?>