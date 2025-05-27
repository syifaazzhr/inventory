<?php

namespace App\Controllers\Users;

use CodeIgniter\Events\Events;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Controllers\RegisterController as RegisterCtrl;
use CodeIgniter\Shield\Exceptions\ValidationException;

class RegisterController extends RegisterCtrl
{
    public function index()
    {
        if (!setting('Auth.allowRegistration')) {
            return redirect()->back()->withInput()
                ->with('error', lang('Auth.registerDisabled'));
        }

        $authenticator = auth('session')->getAuthenticator();

        if ($authenticator->hasAction()) {
            return redirect()->route('auth-action-show');
        }

        // Ambil role dari auth_groups
        $db = \Config\Database::connect();
        $roles = $db->table('auth_groups')->get()->getResultArray();

        return view(setting('Auth.views')['register'], ['roles' => $roles]);
    }

    public function registerAction(): RedirectResponse
    {
        if (!setting('Auth.allowRegistration')) {
            return redirect()->back()->withInput()
                ->with('error', lang('Auth.registerDisabled'));
        }

        $users = $this->getUserProvider();
        $rules = $this->getValidationRules();

        if (!$this->validateData($this->request->getPost(), $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $allowedPostFields = array_keys($rules);
        $user = new \CodeIgniter\Shield\Entities\User();
        $user->username = $this->request->getPost('username');
        $user->password = $this->request->getPost('password');

        if ($user->username === null) {
            $user->username = null;
        }

        try {
            $users->save($user);
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->with('errors', $users->errors());
        }

        $user = $users->findById($users->getInsertID());

        $role = $this->request->getPost('role');
        if (empty($role)) {
            return redirect()->back()->withInput()->with('errors', ['role' => 'Pilih role terlebih dahulu.']);
        }

        // Load Helper Authorization
        helper('authorization');
        // Simpan role ke auth_groups_users
        $db = \Config\Database::connect();
        $db->table('auth_groups_users')->insert([
            'user_id'    => $user->id,
            'group'      => $role,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        // dd($user->id, $role); 

        Events::trigger('register', $user);

        $authenticator = auth('session')->getAuthenticator();

        if ($authenticator->startUpAction('register', $user)) {
            return redirect()->to('auth/a/show');
        }

        $user->activate();

        session()->setFlashdata(['msg' => 'Pengguna baru berhasil ditambahkan!']);
        return redirect()->to('admin/users');
    }
}