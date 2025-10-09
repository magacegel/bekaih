<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Input;
use App\Rules\ReCaptcha;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function index()
    {
        if ($user = Auth::user()) {
            if ($user->hasRole('superadmin')) {
                return redirect()->intended('dashboard');
            } elseif ($user->hasRole('administrator')) {
                return redirect()->intended('administrator');
            } elseif ($user->hasRole('user')) {
                return redirect()->intended('user');
            } elseif ($user->hasRole('inspektor')) {
                return redirect()->intended('inspektor');
            } elseif ($user->hasRole('supervisor')) {
                return redirect()->intended('supervisor');
            }
        }
        return view('login');
    }

    public function proses_login(Request $request)
    {
        // Skip reCAPTCHA validation in non-production environments
        if (config('app.env') === 'production') {
            $validate = Validator::make($request->all(), [
                'token' => ['required', new ReCaptcha]
            ]);

            if ($validate->fails()) {
                return redirect()->back()->withErrors($validate)->withInput();
            }
        }

        // dd($validate);

        $kredensil = [
            'username'  => $request['username'],
            'password'  => $request['password'],
        ];

        $kredensilmail = [
            'email'     => $request['username'],
            'password'  => $request['password'],
        ];

        $kredensilphone = [
            'phone'     => $request['username'],
            'password'  => $request['password'],
        ];

        $request->session()->put('cabang', $request['cabang']);
        $request->session()->put('tahun', Carbon::now()->format('Y'));
        $request->session()->put('bulan', Carbon::now()->format('m'));
        //dd($kredensilphone);

        if (Auth::attempt($kredensil)) {
            $user = Auth::user();
            //dd($user->toArray());
            if ($user->hasRole('superadmin')) {
                return redirect()->intended('superadmin');
            } elseif ($user->hasRole('administrator')) {
                return redirect()->intended('administrator');
            } elseif ($user->hasRole('manajemenproyek')) {
                return redirect()->intended('manajemenproyek');
            } elseif ($user->hasRole('user')) {
                return redirect()->intended('user');
            }
            $request->session()->put('name', $user->name);
            return redirect()->intended('/');
        } elseif (Auth::attempt($kredensilmail)) {
            $user = Auth::user();
            ///dd($user->toArray());
            if ($user->hasRole('superadmin')) {
                return redirect()->intended('superadmin');
            } elseif ($user->hasRole('administrator')) {
                return redirect()->intended('administrator');
            } elseif ($user->hasRole('inspektor')) {
                return redirect()->intended('manajemenproyek');
            } elseif ($user->hasRole('user')) {
                return redirect()->intended('user');
            }
            $request->session()->put('name', $user->name);
            return redirect()->intended('/');
        } elseif (Auth::attempt($kredensilphone)) {
            $user = Auth::user();
            //dd($user->toArray());
            if ($user->hasRole('superadmin')) {
                return redirect()->intended('superadmin');
            } elseif ($user->hasRole('administrator')) {
                return redirect()->intended('administrator');
            } elseif ($user->hasRole('manajemenproyek')) {
                return redirect()->intended('manajemenproyek');
            } elseif ($user->hasRole('user')) {
                return redirect()->intended('user');
            }
            $request->session()->put('name', $user->name);
            return redirect()->intended('/');
        }
        return redirect('login')
            ->withInput()
            ->withErrors(['login_gagal' => 'Username atau password yang Anda masukkan salah.']);
    }

    public function logout(Request $request)
    {
       $request->session()->flush();
       Auth::logout();
       return Redirect('login');
    }
}
