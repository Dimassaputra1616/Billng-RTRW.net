<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\View\View;

class Login extends BaseLogin
{
    protected string $view = 'filament.pages.auth.login';

    public function mount(): void
    {
        parent::mount();

        // Anda bisa menambahkan logic custom di sini jika perlu
    }
}
