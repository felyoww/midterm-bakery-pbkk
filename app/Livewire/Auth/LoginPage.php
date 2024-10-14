<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('login')]
class LoginPage extends Component
{
    public $email;
    public $password;

    public function save()
    {
        // Validate the email and password inputs
        $this->validate([
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required|min:6|max:255',
        ]);

        // Attempt to log in the user with provided credentials
        if (!auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            // If login fails, flash an error message to the session
            session()->flash('error', 'Invalid credentials');
            return;
        }

        // Regenerate the session upon successful login for security
        session()->regenerate();

        // Redirect the user to the intended page or a default page
        return redirect()->intended('/'); // You can change the route to your preference
    }

    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
