<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Group;
use App\Models\User;
use App\Models\MemberCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use App\Services\MessagingService;
use App\Mail\WelcomeMemberMailable;
use Illuminate\Support\Facades\Mail;

class MemberController extends Controller
{
    protected $messagingService;

    public function __construct(MessagingService $messagingService)
    {
        $this->messagingService = $messagingService;
    }

    public function index()
    {
        $members = Member::with(['groups', 'category'])->paginate(10);
        return view('members.index', compact('members'));
    }

    public function create()
    {
        $groups = Group::all();
        $categories = MemberCategory::where('is_active', true)->get();
        return view('members.create', compact('groups', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:members,email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'category_id' => 'required|exists:member_categories,id',
            'registration_number' => 'nullable|string|unique:members,registration_number',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'baptismal_name' => 'nullable|string',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'parish' => 'nullable|string|max:255',
            'diocese' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'registration_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('members', 'public');
            $validated['photo'] = $path;
        }

        // Map category to member_type
        $category = MemberCategory::find($request->category_id);
        $validated['member_type'] = $category->name;

        if (empty($validated['registration_number'])) {
            $validated['registration_number'] = 'TMCS-' . date('Y') . '-' . str_pad(Member::count() + 1, 3, '0', STR_PAD_LEFT);
        }
        
        $validated['qr_code'] = 'QR-' . strtoupper(Str::random(10));
        $validated['is_active'] = true;
        $validated['created_by'] = auth()->id();

        $member = Member::create($validated);

        // Auto-create User account for Member if email exists
        if ($member->email) {
            // Get last name and capitalize it for the password
            $nameParts = explode(' ', trim($member->full_name));
            $lastName = end($nameParts);
            $password = strtoupper($lastName);

            $user = User::create([
                'name' => $member->full_name,
                'email' => $member->email,
                'password' => Hash::make($password),
                'phone' => $member->phone,
            ]);

            // Assign 'member' role if it exists
            $memberRole = \App\Models\Role::where('name', 'member')->first();
            if ($memberRole) {
                $user->roles()->attach($memberRole->id);
            }

            // Link member to user
            $member->update(['user_id' => $user->id]);
        }

        if ($request->has('groups')) {
            $member->groups()->attach($request->groups, ['join_date' => now(), 'is_active' => true]);
        }

        // Send Welcome Notifications
        $this->sendWelcomeNotifications($member, $password ?? null);

        return redirect()->route('members.index')->with('success', 'Member registered successfully. User account created (Username: ' . $member->email . ')');
    }

    /**
     * Send welcome SMS and Email to new member
     */
    protected function sendWelcomeNotifications(Member $member, $password = null)
    {
        // 1. Send SMS
        if ($member->phone) {
            try {
                $smsMessage = "Welcome to TMCS, {$member->full_name}! You have been registered successfully. ID: {$member->registration_number}. God bless you!";
                $this->messagingService->sendSms($member->phone, $smsMessage);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send welcome SMS: " . $e->getMessage());
            }
        }

        // 2. Send Welcome Email
        if ($member->email) {
            try {
                // If no password provided (e.g. member already had a user), we don't send credentials
                Mail::to($member->email)->send(new WelcomeMemberMailable($member, $password ?? '******'));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send welcome email: " . $e->getMessage());
            }
        }
    }

    public function show(Member $member)
    {
        $member->load(['financials', 'groups', 'contributions', 'category']);
        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $groups = Group::all();
        $categories = MemberCategory::where('is_active', true)->get();
        $memberGroups = $member->groups->pluck('id')->toArray();
        return view('members.edit', compact('member', 'groups', 'memberGroups', 'categories'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:members,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'category_id' => 'required|exists:member_categories,id',
            'registration_number' => 'nullable|string|unique:members,registration_number,' . $member->id,
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'baptismal_name' => 'nullable|string',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'parish' => 'nullable|string|max:255',
            'diocese' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            
            $path = $request->file('photo')->store('members', 'public');
            $validated['photo'] = $path;
        }

        // Map category to member_type
        $category = MemberCategory::find($request->category_id);
        $validated['member_type'] = $category->name;

        $member->update($validated);

        if ($request->has('groups')) {
            $syncData = [];
            foreach ($request->groups as $groupId) {
                $syncData[$groupId] = [
                    'join_date' => now(),
                    'is_active' => true
                ];
            }
            $member->groups()->sync($syncData);
        }

        return redirect()->route('members.index')->with('success', 'Member updated successfully');
    }

    /**
     * Approve a self-registered member.
     */
    public function approve(Member $member)
    {
        // 1. Activate User
        if ($member->user) {
            $member->user->update(['is_active' => true]);
        }

        // 2. Activate Member
        $member->update([
            'is_active' => true,
            'registration_number' => 'TMCS-' . date('Y') . '-' . str_pad(Member::where('is_active', true)->count() + 1, 3, '0', STR_PAD_LEFT),
        ]);

        // 3. Activate Group Memberships
        $member->groups()->updateExistingPivot($member->groups->pluck('id'), ['is_active' => true]);

        // 4. Send Notifications
        if ($member->phone) {
            try {
                $smsMessage = "Congratulations {$member->full_name}! Your TMCS account has been approved. Your ID is {$member->registration_number}. You can now login to your portal.";
                $this->messagingService->sendSms($member->phone, $smsMessage);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send approval SMS: " . $e->getMessage());
            }
        }

        if ($member->email) {
            try {
                Mail::to($member->email)->send(new WelcomeMemberMailable($member, '******'));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send welcome email on approval: " . $e->getMessage());
            }
        }

        return back()->with('success', 'Member approved successfully! Registration number assigned: ' . $member->registration_number);
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Member deleted successfully');
    }

    public function idCard(Member $member)
    {
        // Authorization check: User can only see their own ID card, unless they are admin/leader
        $user = auth()->user();
        $isOwner = $user->member && $user->member->id === $member->id;
        $isAdmin = $user->roles()->whereIn('name', ['admin', 'leader', 'finance'])->exists();

        if (!$isOwner && !$isAdmin) {
            abort(403, 'Unauthorized access to this ID card.');
        }

        $member->load(['groups', 'category']);

        if (request()->has('download')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('members.id_card_pdf', compact('member'))
                ->setPaper([0, 0, 242.65, 153.01], 'portrait'); // CR80 Size in points
            
            // Sanitize filename to remove slashes
            $safeRegNo = str_replace(['/', '\\'], '-', $member->registration_number);
            return $pdf->download("ID_Card_{$safeRegNo}.pdf");
        }

        return view('members.id_card', compact('member'));
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="members_template.csv"',
        ];

        $columns = [
            'full_name', 'email', 'phone', 'category_id', 'registration_number', 
            'date_of_birth', 'gender', 'parish', 'diocese', 'region', 
            'address', 'baptismal_name', 'registration_date'
        ];

        $categories = MemberCategory::where('is_active', true)->get();
        $categoryInfo = $categories->map(fn($c) => "{$c->id}={$c->name}")->implode(', ');

        $callback = function() use ($columns, $categoryInfo) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // 10 Sample Rows
            $samples = [
                ['David Rashid Ngungila', 'david@example.com', '0622239304', '1', 'MOCU/BBICT/1077/23', '2000-05-04', 'Male', 'Ngerengere', 'Moshi', 'Kilimanjaro', 'Ngerengere, Moshi', 'David', date('Y-m-d')],
                ['Mary Atieno', 'mary@example.com', '0712345678', '1', 'MOCU/BBICT/1078/23', '2001-08-12', 'Female', 'St. Peters', 'Dar es Salaam', 'Kinondoni', 'Posta, DSM', 'Mary', date('Y-m-d')],
                ['Joseph Kamau', 'joseph@example.com', '0722334455', '2', 'MOCU/PG/001/24', '1995-11-20', 'Male', 'Holy Family', 'Nairobi', 'Central', 'Nairobi, Kenya', 'Joseph', date('Y-m-d')],
                ['Sarah Wanjiku', 'sarah@example.com', '0733445566', '3', 'STAFF/2024/01', '1985-03-15', 'Female', 'St. Andrews', 'Nyeri', 'Mt Kenya', 'Nyeri Town', 'Sarah', date('Y-m-d')],
                ['Peter Omondi', 'peter@example.com', '0744556677', '4', 'STAFF/2024/02', '1980-07-30', 'Male', 'St. Pauls', 'Kisumu', 'Lakeside', 'Kisumu City', 'Peter', date('Y-m-d')],
                ['Grace Mutua', 'grace@example.com', '0755667788', '5', 'SS/2024/001', '2015-01-10', 'Female', 'St. Johns', 'Machakos', 'Eastern', 'Machakos Town', 'Grace', date('Y-m-d')],
                ['Emmanuel John', 'emma@example.com', '0766778899', '6', 'COMM/2024/001', '1990-09-05', 'Male', 'Epiphany', 'Dodoma', 'Central', 'Dodoma City', 'Emma', date('Y-m-d')],
                ['Tabitha Silas', 'tabitha@example.com', '0777889900', '7', 'ELD/2024/001', '1960-12-25', 'Female', 'St. James', 'Arusha', 'Northern', 'Arusha Town', 'Tabitha', date('Y-m-d')],
                ['Michael Mwangi', 'mike@example.com', '0788990011', '1', 'MOCU/BBICT/1079/23', '2002-04-18', 'Male', 'St. Marks', 'Nakuru', 'Rift Valley', 'Nakuru City', 'Mike', date('Y-m-d')],
                ['Lucy Achieng', 'lucy@example.com', '0799001122', '1', 'MOCU/BBICT/1080/23', '2003-02-28', 'Female', 'St. Lukes', 'Eldoret', 'Rift Valley', 'Eldoret Town', 'Lucy', date('Y-m-d')],
            ];

            foreach ($samples as $sample) {
                fputcsv($file, $sample);
            }
            
            // Add instructions row
            fputcsv($file, ['CATEGORY IDs (DO NOT DELETE THIS ROW):', $categoryInfo]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip header
        $header = fgetcsv($handle);
        
        $count = 0;
        $errors = [];
        
        while (($row = fgetcsv($handle)) !== false) {
            // Basic check for empty rows or category info row
            if (empty($row[0]) || str_contains($row[0], 'CATEGORY IDs')) continue;
            
            try {
                // Map columns - Ensure all fields are handled
                $data = [
                    'full_name' => trim($row[0]),
                    'email' => !empty($row[1]) ? trim($row[1]) : null,
                    'phone' => !empty($row[2]) ? trim($row[2]) : null,
                    'category_id' => !empty($row[3]) ? trim($row[3]) : null,
                    'registration_number' => !empty($row[4]) ? trim($row[4]) : null,
                    'date_of_birth' => !empty($row[5]) ? trim($row[5]) : null,
                    'gender' => !empty($row[6]) ? trim($row[6]) : 'Male',
                    'parish' => !empty($row[7]) ? trim($row[7]) : null,
                    'diocese' => !empty($row[8]) ? trim($row[8]) : null,
                    'region' => !empty($row[9]) ? trim($row[9]) : null,
                    'address' => !empty($row[10]) ? trim($row[10]) : 'N/A',
                    'baptismal_name' => !empty($row[11]) ? trim($row[11]) : null,
                    'registration_date' => !empty($row[12]) ? trim($row[12]) : now()->format('Y-m-d'),
                ];

                // Basic Validation
                if (empty($data['full_name'])) {
                    $errors[] = "Row " . ($count + 2) . ": Full Name is required.";
                    continue;
                }

                if (empty($data['category_id'])) {
                    $errors[] = "Row " . ($count + 2) . ": Category ID is required.";
                    continue;
                }

                // Check for existing email
                if ($data['email'] && Member::where('email', $data['email'])->exists()) {
                    $errors[] = "Row " . ($count + 2) . ": Email " . $data['email'] . " already exists.";
                    continue;
                }

                // Check for existing registration number
                if ($data['registration_number'] && Member::where('registration_number', $data['registration_number'])->exists()) {
                    $errors[] = "Row " . ($count + 2) . ": Reg No " . $data['registration_number'] . " already exists.";
                    continue;
                }

                // Auto-generate Reg No if missing
                if (empty($data['registration_number'])) {
                    $data['registration_number'] = 'TMCS-' . date('Y') . '-' . str_pad(Member::count() + 1, 3, '0', STR_PAD_LEFT);
                }

                $category = MemberCategory::find($data['category_id']);
                if (!$category) {
                    $errors[] = "Row " . ($count + 2) . ": Category ID " . $data['category_id'] . " not found.";
                    continue;
                }

                $data['member_type'] = $category->name;
                $data['qr_code'] = 'QR-' . strtoupper(Str::random(10));
                $data['is_active'] = true;
                $data['created_by'] = auth()->id();

                $member = Member::create($data);

                // Auto-create User account for Member if email exists
                if ($member->email) {
                    $nameParts = explode(' ', trim($member->full_name));
                    $lastName = end($nameParts);
                    $password = strtoupper($lastName);

                    $user = User::create([
                        'name' => $member->full_name,
                        'email' => $member->email,
                        'password' => Hash::make($password),
                        'phone' => $member->phone,
                    ]);

                    $memberRole = \App\Models\Role::where('name', 'member')->first();
                    if ($memberRole) {
                        $user->roles()->attach($memberRole->id);
                    }

                    $member->update(['user_id' => $user->id]);
                }

                $count++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($count + 2) . ": " . $e->getMessage();
            }
        }
        
        fclose($handle);

        $msg = "Successfully imported $count members.";
        if (!empty($errors)) {
            $msg .= " Errors found: " . count($errors);
        }

        return redirect()->route('members.index')->with(empty($errors) ? 'success' : 'warning', $msg)->with('import_errors', $errors);
    }
}
