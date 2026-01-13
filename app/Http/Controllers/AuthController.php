<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\AiAgents\SupportAgent;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Clear chat history on login (new session, fresh start)
            $this->clearChatHistory();

            return redirect()->intended('/')->with('success', 'Welcome back!');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        // Clear chat history before logging out
        $this->clearChatHistory();
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out.');
    }
    
    /**
     * Clear chat history from session
     */
    protected function clearChatHistory()
    {
        try {
            $agent = new SupportAgent();
            $chatHistory = $agent->chatHistory();
            $chatHistory->clear();
        } catch (\Throwable $th) {
            // Silently fail if chat history can't be cleared
            // This might happen if session is already invalidated
            \Log::debug('Could not clear chat history: ' . $th->getMessage());
        }
    }
}

