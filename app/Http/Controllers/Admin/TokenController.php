<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Token;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function index()
    {
        $tokens = Token::latest()->paginate(20);
        return view('admin.tokens.index', compact('tokens'));
    }

    public function create()
    {
        return view('admin.tokens.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $token = Token::create([
            'name' => $request->name,
            'token' => Token::generateToken(),
            'max_usage' => $request->max_uses,
            'expires_at' => $request->expires_at,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.tokens.index')
            ->with('success', 'Token berhasil dibuat');
    }

    public function edit(Token $token)
    {
        return view('admin.tokens.edit', compact('token'));
    }

    public function update(Request $request, Token $token)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        $token->update([
            'name' => $request->name,
            'max_usage' => $request->max_uses,
            'expires_at' => $request->expires_at,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.tokens.index')
            ->with('success', 'Token berhasil diupdate');
    }

    public function destroy(Token $token)
    {
        $token->delete();

        return redirect()->route('admin.tokens.index')
            ->with('success', 'Token berhasil dihapus');
    }

    public function toggle(Token $token)
    {
        $token->update(['is_active' => !$token->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $token->is_active,
        ]);
    }

    public function regenerate(Token $token)
    {
        $token->update([
            'token' => Token::generateToken(),
            'usage_count' => 0,
        ]);

        return redirect()->route('admin.tokens.index')
            ->with('success', 'Token berhasil di-regenerate');
    }
}
