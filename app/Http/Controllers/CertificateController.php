<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Member;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with('member')->paginate(10);
        $totalCertificates = Certificate::count();
        $verifiedCertificates = Certificate::where('status', 'Active')->count();
        return view('certificates.index', compact('certificates', 'totalCertificates', 'verifiedCertificates'));
    }

    public function create()
    {
        $members = Member::all();
        return view('certificates.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'certificate_type' => 'required|string',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date',
        ]);

        $validated['issued_by'] = auth()->id();
        $validated['status'] = 'Active';
        $validated['certificate_type'] = (string) $validated['certificate_type']; // Explicitly cast to string

        Certificate::create($validated);

        return redirect()->route('certificates.index')->with('success', 'Certificate generated successfully');
    }

    public function verify()
    {
        return view('certificates.verify');
    }
}
