<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\MemberCategory;
use App\Models\Group;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role;

class CommunityAssignmentDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('=== Community Assignment Demo Seeder ===');

        // 1. Ensure basic member categories exist (student categories)
        $studentCategory = MemberCategory::firstOrCreate(
            ['name' => 'Undergraduate'],
            ['display_name' => 'Undergraduate Student', 'is_active' => true]
        );
        
        MemberCategory::firstOrCreate(
            ['name' => 'Postgraduate'],
            ['display_name' => 'Postgraduate Student', 'is_active' => true]
        );

        // 2. Ensure programs are seeded (from ProgramSeeder, but just in case)
        $this->call(ProgramSeeder::class);

        // 3. Create Communities (Small Christian Communities) for each program
        $this->createProgramCommunities();

        // 4. Create a Sample Student Member to demonstrate auto-assignment
        $this->createSampleStudentMember();

        $this->command->info('✅ Community Assignment Demo Setup Complete!');
    }

    /**
     * Create Small Christian Communities (SCC) for each program.
     */
    protected function createProgramCommunities(): void
    {
        $this->command->info('Creating Small Christian Communities for each program...');

        $programs = Program::where('is_active', true)->get();
        $count = 0;

        foreach ($programs as $program) {
            $communityName = 'SCC ' . $program->code;
            
            $community = Group::updateOrCreate(
                ['name' => $communityName],
                [
                    'description' => "Small Christian Community for students in {$program->name} ({$program->level})",
                    'type' => 'Community',
                    'meeting_day' => 'Sunday',
                    'regular_contribution_amount' => 1000,
                    'is_active' => true,
                    'formation_date' => now(),
                    'created_by' => 1,
                    'criteria' => [
                        'category_id' => $this->getCategoryIdForProgramLevel($program->level),
                        'program_ids' => [$program->id]
                    ]
                ]
            );

            $this->command->line("  - {$communityName} for {$program->name}");
            $count++;
        }

        $this->command->info("Created {$count} program-based Communities!");
    }

    /**
     * Get appropriate category ID based on program level.
     */
    protected function getCategoryIdForProgramLevel(string $level): ?int
    {
        $categoryName = match($level) {
            'Postgraduate' => 'Postgraduate',
            default => 'Undergraduate'
        };
        
        $category = MemberCategory::where('name', $categoryName)->first();
        return $category?->id;
    }

    /**
     * Create a sample student member to demonstrate auto-assignment.
     */
    protected function createSampleStudentMember(): void
    {
        $this->command->info('Creating sample student member...');

        $program = Program::first(); // Get first program
        $category = MemberCategory::where('name', 'Undergraduate')->first();

        if (!$program || !$category) {
            $this->command->error('  ⚠️  Missing program or category, skipping sample student.');
            return;
        }

        // Create Member
        $member = Member::updateOrCreate(
            ['email' => 'jane.student@tmcssmart.com'],
            [
                'full_name' => 'Jane Student',
                'phone' => '0712345678',
                'category_id' => $category->id,
                'member_type' => $category->name,
                'program_id' => $program->id,
                'date_of_birth' => '2002-05-15',
                'address' => 'Moshi Co-operative University, Moshi',
                'gender' => 'Female',
                'parish' => 'St. Joseph Chaplaincy',
                'diocese' => 'Moshi',
                'region' => 'Kilimanjaro',
                'registration_date' => now(),
                'registration_number' => 'STU-' . date('Y') . '-001',
                'qr_code' => 'QR-' . strtoupper(Str::random(10)),
                'is_active' => true,
                'created_by' => 1,
            ]
        );

        // Create corresponding User account
        $password = 'STUDENT';
        $user = User::updateOrCreate(
            ['email' => 'jane.student@tmcssmart.com'],
            [
                'name' => 'Jane Student',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        // Link user and member
        $member->update(['user_id' => $user->id]);

        // Assign 'member' role
        $memberRole = Role::where('name', 'member')->first();
        if ($memberRole) {
            $user->roles()->syncWithoutDetaching([$memberRole->id]);
        }

        // TRIGGER AUTO-ASSIGNMENT TO COMMUNITIES!
        $groupService = app(\App\Services\GroupService::class);
        $groupService->autoAssignMemberToCommunities($member);
        $member->refresh();

        $this->command->info("✅ Created Student Member:");
        $this->command->line("  - Name: {$member->full_name}");
        $this->command->line("  - Program: {$program->name} ({$program->code})");
        $this->command->line("  - Login Email: {$user->email}");
        $this->command->line("  - Login Password: {$password}");
        
        // Get assigned communities
        $assignedCommunities = $member->groups()->where('type', 'Community')->pluck('name')->join(', ');
        $this->command->line("  - 🎉 Auto-Assigned Communities: {$assignedCommunities}");
    }
}
