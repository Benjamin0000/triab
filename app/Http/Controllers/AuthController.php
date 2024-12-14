<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;
use App\Models\Token\EmailToken;
use App\Models\Token\PasswordToken;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AuthController extends Controller  implements HasMiddleware
{

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest', except: ['verify_email_address']),
        ];
    }
 
    // Authentication Views
    /**
     * Show login form
     */
    public function login_form()
    {
        return view('auth.login'); 
    }

    /**
     * Show register form
     */
    public function register_form()
    {
        return view('auth.register'); 
    }

    /**
     * Show email form
     */
    public function show_email_form()
    {
        return view('auth.email'); 
    }

    /**
     * Show change password form
     */
    public function show_change_password_form(string $token, string $email)
    {
        return view('auth.update', compact('email', 'token'));  
    }

    // Authentication Logic
    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]); 

        $email = $request->input('email'); 
        $password = $request->input('password'); 
        $user = User::where('email', $email)->first(); 

        if ($user && password_verify($password, $user->password)) {
            Auth::login($user); 
            return redirect(route('dashboard.index')); 
        }

        return back()->with('error', 'Invalid email or password'); 
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'mobile_number' => [
                'required',
                'string',
                'max:100',
                'unique:users,mobile_number',
                'regex:/^0[1-9][01]\d{8}$/',
            ],
            'password' => ['required', 'string', 'max:100'],
            'sponsor' => ['nullable', 'string', 'max:100', 'exists:users,gnumber'],
            'place_under' => ['nullable', 'string', 'max:100', 'exists:users,gnumber'],
        ], [
            'mobile_number.regex' => 'Invalid mobile number',
            'email.unique' => 'The email address is already in use.',
            'mobile_number.unique' => 'The mobile number is already in use.',
            'sponsor.exists' => 'The sponsor does not exist.'
        ]);

        $user = new User; 
        $user->name = $request->input('name'); 
        $user->email = $request->input('email'); 
        $user->mobile_number = $request->input('mobile_number');
        $user->gnumber = genGnumber(); 
        $user->password = bcrypt($request->input('password')); 

        $sponsor = $request->input('sponsor') ? findByGnumber($request->input('sponsor')) : default_user();
        $user->ref_by = $sponsor->id;
    
        $sponsor->total_referrals += 1; 
        $sponsor->save(); 
        
        if(!$sponsor->origin)
            $sponsor->update_gsteam_wheel_referrals();

        $user->save();
        $token = EmailToken::create(['email' => $user->email]);
        // Send token as email to user.

        return back()->with('success', 'Account created'); 
    }

    /**
     * Handle password reset email sending
     */
    public function send_reset_email(Request $request)
    {
        $request->validate([
            'email' => ['required']
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $token = PasswordToken::where('email', $email)
                ->where('created_at', '>=', now()->subHour())
                ->first();

            if (!$token) {
                $token = PasswordToken::create(['email' => $email]);
                // Send email 
            }
        }

        return back()->with('success', 'Email sent'); 
    }

    /**
     * Handle password change
     */
    public function change_password(Request $request)
    {
        $request->validate([
            'password' => ['required']
        ]); 

        $email = $request->input('email'); 
        $token = $request->input('token'); 
        $password = $request->input('password'); 

        $token = PasswordToken::where([
            ['id', $token], 
            ['email', $email]
        ])->first();

        if ($token) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->password = bcrypt($password);
                $user->last_password_change = now(); 
                $user->save(); 
                $token->delete(); 
                return redirect(route('login'))->with('success', 'Password changed');  
            }
        }

        return redirect(route('login'))->with('error', 'Expired Link'); 
    }

    /**
     * Verify email address
     */
    public function verify_email_address(string $token, string $email)
    {
        $Token = EmailToken::where([
            ['id', $token], 
            ['email', $email]
        ])->first();

        if ($Token) {
            $user = User::where('email', $email)->first(); 
            if ($user) {
                $user->email_verified_at = now();
                $user->save();
                $Token->delete();

                return Auth::check()
                    ? redirect(route('dashboard'))->with('success', 'Email verified')
                    : redirect(route('login'))->with('success', 'Email verified');
            }
        }

        return redirect(route('login'))->with('error', 'Link Expired'); 
    }
}