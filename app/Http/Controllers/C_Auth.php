<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class C_Auth extends Controller
{
    public $messages = [
        'username.required' => 'Username harus diisi.',
        'username.max' => 'Username tidak boleh lebih dari :max karakter.',
        'username.unique' => 'Username sudah digunakan.',
        'email.required' => 'Email harus diisi.',
        'email.email' => 'Email harus berupa alamat email yang valid.',
        'email.max' => 'Email tidak boleh lebih dari :max karakter.',
        'email.unique' => 'Email sudah digunakan.',
        'password.required' => 'Kata sandi harus diisi.',
        'password.min' => 'Kata sandi harus memiliki setidaknya :min karakter.',
        'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        'password.regex' => 'Kata sandi harus mengandung minimal satu huruf kecil dan satu huruf besar.',
    ];

    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($credentials)) {
            $user = Auth::user();
            session()->put('user', $user);
            return redirect()->route('dashboard');
        }
        return redirect()->back()->withErrors(['error' => 'Akun Tidak Terdaftar']);
    }

    public function logout()
    {
        Auth::logout();
        session()->forget('user');
        return redirect()->route('login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:50|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z]).+$/',
        ], $this->messages);

        if ($validator->fails()) {
            session()->flash('error', $validator->errors()->first());
            return redirect()->back()->withInput();
        }

        $credentials = $validator->validated();

        $user = User::create([
            'username' => $credentials['username'],
            'email' => $credentials['email'],
            'password' => bcrypt($credentials['password']),
            'role' => 'mentor'
        ]);

        Mentor::create([
            'id_user' => $user->id,
        ]);

        session()->flash('success', 'Akun berhasil dibuat.');
        return redirect()->back();
    }

    public function forgotPass()
    {
        return view('auth.forgot_password');
    }

    public function resetPass(Request $request)
    {
        $credentials = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z]).+$/',
        ], $this->messages);

        if ($credentials->fails()) {
            session()->flash('error', $credentials->errors()->first());
            return redirect()->back()->withInput();
        }

        $user = User::where('email', $request['email'])->first();

        if (!$user) {
            session()->flash('error', 'Email tidak ditemukan.');
            return redirect()->back()->withErrors(['error' => 'Email tidak ditemukan.'])->withInput();
        }

        User::where('email', $request['email'])->update([
            'password' => bcrypt($request['password'])
        ]);

        session()->flash('success', 'Password berhasil direset.');
        return redirect()->back();
    }
}
