<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Masuk Akun</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container d-flex justify-content-center p-5">
  <div class="card col-12 col-md-5 shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-5 text-center">Masuk Akun</h5>

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

      <?php if (session('message') !== null) : ?>
        <div class="alert alert-success" role="alert"><?= session('message') ?></div>
      <?php endif ?>

      <form action="<?= url_to('login') ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-2">
          <input type="text" class="form-control" name="username" inputmode="username" autocomplete="username" placeholder="Nama Pengguna" value="<?= old('username') ?>" oninput="this.value = this.value.toUpperCase();" style="text-transform:none;" required />
        </div>
        <div class="mb-2">
          <input type="password" class="form-control" name="password" inputmode="text" autocomplete="current-password" placeholder="Kata Sandi" oninput="this.value = this.value.toUpperCase();" style="text-transform:none;" required />
        </div>

        <div class="d-grid col-12 mx-auto m-3">
          <button type="submit" class="btn btn-primary btn-block">Masuk</button>
        </div>

        <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
          <p class="text-center"><?= lang('Auth.forgotPassword') ?> <a href="<?= url_to('magic-link') ?>"><?= lang('Auth.useMagicLink') ?></a></p>
        <?php endif ?>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>