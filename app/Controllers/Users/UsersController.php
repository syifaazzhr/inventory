<?php

namespace App\Controllers\Users;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Authentication\Passwords;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\HTTP\Exceptions\HTTPException;

class UsersController extends ResourceController
{
    protected UserModel $userModel;

    public function __construct()
    {
        helper('auth');
        $this->userModel = new UserModel;
    }

    public function index()
    {
        $itemPerPage = 20;
 
        $users = $this->userModel->withIdentities()->paginate($itemPerPage, 'users');

        $data = [
            'users'             => $users,
            'pager'             => $this->userModel->pager,
            'currentPage'       => $this->request->getVar('page_users') ?? 1,
            'itemPerPage'       => $itemPerPage,
        ];

        return view('users/index', $data);
    }

    public function profile()
    {
        $userId = auth()->id();
        $user   = $this->userModel->find($userId);

        if (empty($user)) {
            throw new PageNotFoundException('User tidak ditemukan!');
        }

        return view('users/profile', [
            'user' => $user,
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function edit($id = null)
    {
        // $user = $this->userModel->withIdentities()->find($id);
        $currentUser = auth()->user();
        $user = $this->userModel->withIdentities()->find($id);

        if (empty($user)) {
            throw new PageNotFoundException('User tidak ditemukan!');
        }

        if (!$currentUser->inGroup('admin') && $currentUser->id !== (int)$id) {
    return redirect()->to('admin/users/edit')->with('error', 'Kamu tidak punya izin mengakses halaman ini.');
}


        $data = [
            'user'           => $user,
            'validation'     => \Config\Services::validation(),
        ];

        return view('users/edit', $data);
    }

    public function update($id = null)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            throw new PageNotFoundException('User tidak ditemukan!');
        }

        $usernameLama = $user->username;
        $usernameBaru = $this->request->getVar('username');
        $password     = $this->request->getVar('password');
        $usernameChanged = $usernameLama !== $usernameBaru;

        // Validasi input
        if (!$this->validate([
            'username' => $usernameChanged ? 'required|string|is_unique[users.username]' : 'required|string',
            'password' => [
                'label'  => 'Auth.password',
                'rules'  => 'permit_empty|' . Passwords::getMaxLengthRule() . '|strong_password',
                'errors' => [
                    'max_byte' => 'Auth.errorPasswordTooLongBytes',
                ],
            ],
            'password_confirm' => [
                'label' => 'Auth.passwordConfirm',
                'rules' => 'permit_empty|matches[password]',
            ],
        ])) {
            return view('users/edit', [
                'user'       => $user,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ]);
        }

        if (!empty($password)) {
            $password = strtoupper($password); // Konversi ke uppercase
            $hashedPassword = service('passwords')->hash($password);
            $userData['password'] = $hashedPassword;
        }

        // Simpan user (tabel `users`)
        $userData = [
            'id'       => $id,
            'username' => $usernameBaru,
        ];

        $plainPassword = '';
        $hashedPassword = null;

        if (!empty($password)) {
            $plainPassword = $password;
            $hashedPassword = service('passwords')->hash($password);
            $userData['password'] = $hashedPassword;
        }

        $this->userModel->save($userData);

        // Update atau Insert ke `auth_identities` type = email_password
        $identityModel = model(\CodeIgniter\Shield\Models\UserIdentityModel::class);
        $identity = $identityModel->where('user_id', $id)->where('type', 'email_password')->first();

        if ($identity) {
            $updateData = [];
            if ($usernameChanged) {
                $updateData['secret'] = $usernameBaru;
            }
            if (!empty($hashedPassword)) {
                $updateData['secret2'] = $hashedPassword;
            }
            if (!empty($updateData)) {
                $updateData['updated_at'] = date('Y-m-d H:i:s');
                $identityModel->update($identity->id, $updateData);
            }
        } else {
            // Insert kalau belum ada
            $identityModel->insert([
                'user_id'    => $id,
                'type'       => 'email_password',
                'secret'     => $usernameBaru,
                'secret2'    => $hashedPassword,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $msg = "Data berhasil diperbarui!";
        // $msg .= "Username: <strong>{$usernameBaru}</strong><br>";
        // if (!empty($plainPassword)) {
        //     $msg .= "Password Baru: <strong>{$plainPassword}</strong><br>";
        // }

        session()->setFlashdata(['msg' => $msg]);

        $currentUser = auth()->user();
        if ($currentUser->inGroup('user')) {
            return redirect()->to('users/profile');
        }

        return redirect()->to('admin/users');
    }

    public function delete($id = null)
    {
        $user = $this->userModel->where('id', $id)->first();

        if (empty($user)) {
            throw new PageNotFoundException('User tidak ditemukan!');
        }

        if (!$this->userModel->delete($id)) {
            session()->setFlashdata(['msg' => 'Gagal menghapus data!', 'error' => true]);
            return redirect()->back();
        }

        session()->setFlashdata(['msg' => 'Data berhasil dihapus!']);
        return redirect()->to('admin/users');
    }
}
