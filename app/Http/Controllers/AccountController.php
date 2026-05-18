<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'Asset');
        return view('finance.accounts.create', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:accounts,code',
            'type' => 'required|string',
            'balance' => 'required|numeric',
            'is_active' => 'boolean',
        ]);

        try {
            Account::create($validated);
            return redirect()->route('finance.settings')->with('success', 'Account created successfully.');
        } catch (\Exception $e) {
            Log::error('Account Store Error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while creating the account: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        return view('finance.accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:accounts,code,' . $account->id,
            'type' => 'required|string',
            'balance' => 'required|numeric',
            'is_active' => 'boolean',
        ]);

        try {
            $account->update($validated);
            return redirect()->route('finance.settings')->with('success', 'Account updated successfully.');
        } catch (\Exception $e) {
            Log::error('Account Update Error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the account: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        try {
            // Check if account has ledger entries
            if ($account->ledgerEntries()->count() > 0) {
                return back()->with('error', 'Cannot delete account with existing transactions.');
            }
            
            $account->delete();
            return redirect()->route('finance.settings')->with('success', 'Account deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Account Delete Error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting the account.');
        }
    }
}
