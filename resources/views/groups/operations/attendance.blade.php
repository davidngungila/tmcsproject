@extends('layouts.app')

@section('title', 'Group Attendance - ' . $group->name)
@section('page-title', 'Record Meeting Attendance: ' . $group->name)
@section('breadcrumb', 'Home / Group / Operations / Attendance')

@section('content')
<div class="animate-in">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- ATTENDANCE ENTRY OPTIONS -->
        <div class="lg:col-span-5">
            <div class="card shadow-lg border-green-100">
                <div class="card-header border-b bg-green-50/30 py-4 flex items-center justify-between">
                    <h3 class="card-title text-green-800 uppercase font-black text-xs tracking-widest">Attendance Entry</h3>
                    <div class="flex bg-gray-100 p-1 rounded-lg">
                        <button type="button" onclick="toggleEntry('bulk')" id="btnBulk" class="px-4 py-1 text-[10px] font-black uppercase rounded-md bg-white shadow-sm text-green-600 transition-all">Bulk</button>
                        <button type="button" onclick="toggleEntry('individual')" id="btnIndividual" class="px-4 py-1 text-[10px] font-black uppercase rounded-md text-gray-400 hover:text-gray-600 transition-all">Individual</button>
                    </div>
                </div>
                <div class="card-body p-6">
                    <form action="{{ route('groups.operations.attendance.store', $group->id) }}" method="POST" id="attendanceForm">
                        @csrf
                        <input type="hidden" name="entry_type" id="entryType" value="bulk">
                        
                        <div class="form-group mb-6">
                            <label class="form-label text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 block">Meeting Date</label>
                            <input type="date" name="meeting_date" class="form-control rounded-xl border-gray-200 p-4 font-bold" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- BULK ENTRY FIELDS -->
                        <div id="bulkSection">
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="form-group">
                                    <label class="form-label text-[10px] font-black uppercase text-green-600 tracking-widest mb-2 block">Present (Members)</label>
                                    <input type="number" name="present_count" class="form-control rounded-xl border-gray-200 p-4 font-black text-center text-lg" value="{{ $group->members->count() }}" min="0">
                                </div>
                                <div class="form-group">
                                    <label class="form-label text-[10px] font-black uppercase text-red-600 tracking-widest mb-2 block">Absent</label>
                                    <input type="number" name="absent_count" class="form-control rounded-xl border-gray-200 p-4 font-black text-center text-lg" value="0" min="0">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="form-group">
                                    <label class="form-label text-[10px] font-black uppercase text-blue-600 tracking-widest mb-2 block">Apologies</label>
                                    <input type="number" name="apology_count" class="form-control rounded-xl border-gray-200 p-4 font-black text-center text-lg" value="0" min="0">
                                </div>
                                <div class="form-group">
                                    <label class="form-label text-[10px] font-black uppercase text-amber-600 tracking-widest mb-2 block">Guests (Non-Members)</label>
                                    <input type="number" name="guest_count" class="form-control rounded-xl border-gray-200 p-4 font-black text-center text-lg" value="0" min="0">
                                </div>
                            </div>
                        </div>

                        <!-- INDIVIDUAL ENTRY FIELDS -->
                        <div id="individualSection" class="hidden">
                            <div class="mb-6 max-h-[400px] overflow-y-auto border border-gray-100 rounded-2xl">
                                <table class="w-full">
                                    <thead class="sticky top-0 bg-white border-b">
                                        <tr>
                                            <th class="p-3 text-[9px] font-black uppercase text-gray-400 text-left">Member</th>
                                            <th class="p-3 text-[9px] font-black uppercase text-gray-400 text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach($group->members as $member)
                                        <tr>
                                            <td class="p-3">
                                                <div class="text-xs font-bold">{{ $member->full_name }}</div>
                                                <div class="text-[9px] text-muted">{{ $member->registration_number }}</div>
                                            </td>
                                            <td class="p-3">
                                                <select name="attendance[{{ $member->id }}]" class="text-[10px] font-bold border-none bg-gray-50 rounded-lg p-1 w-full focus:ring-0">
                                                    <option value="present">Present</option>
                                                    <option value="absent">Absent</option>
                                                    <option value="apology">Apology</option>
                                                </select>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group mb-6">
                                <label class="form-label text-[10px] font-black uppercase text-amber-600 tracking-widest mb-2 block">Guests (Non-Members)</label>
                                <input type="number" name="guest_count" class="form-control rounded-xl border-gray-200 p-4 font-black text-center text-lg" value="0" min="0">
                            </div>
                        </div>

                        <div class="form-group mb-6">
                            <label class="form-label text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 block">Meeting Notes</label>
                            <textarea name="notes" class="form-control rounded-xl border-gray-200 p-4 text-sm" rows="3" placeholder="e.g. Special guest speaker attended..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-full py-4 font-black uppercase tracking-[0.2em] text-xs shadow-green-100 shadow-lg">
                            Save Attendance Record
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- HISTORY -->
        <div class="lg:col-span-7">
            <div class="card border-none shadow-sm">
                <div class="card-header border-b p-6 bg-gray-50/50">
                    <h3 class="card-title text-xs font-black uppercase tracking-widest text-gray-400">Attendance History</h3>
                </div>
                <div class="table-wrap">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Present</th>
                                <th>Guests</th>
                                <th>Total Headcount</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($meetings as $meeting)
                            <tr>
                                <td class="font-bold text-sm">{{ $meeting->meeting_date->format('M d, Y') }}</td>
                                <td><span class="badge green font-black text-[10px]">{{ $meeting->present_count }} Members</span></td>
                                <td><span class="badge amber font-black text-[10px]">{{ $meeting->guest_count }} Guests</span></td>
                                <td class="font-black text-gray-800">{{ $meeting->present_count + $meeting->guest_count }}</td>
                                <td class="text-right">
                                    <a href="{{ route('groups.operations.meeting.show', [$group->id, $meeting->id]) }}" class="btn btn-ghost btn-sm text-green-600 font-bold uppercase text-[10px] tracking-widest">View Details</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-16 text-muted italic">No attendance records found for this community.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer border-t bg-gray-50/30">
                    {{ $meetings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEntry(type) {
    const bulkSection = document.getElementById('bulkSection');
    const individualSection = document.getElementById('individualSection');
    const entryType = document.getElementById('entryType');
    const btnBulk = document.getElementById('btnBulk');
    const btnIndividual = document.getElementById('btnIndividual');

    if (type === 'bulk') {
        bulkSection.classList.remove('hidden');
        individualSection.classList.add('hidden');
        entryType.value = 'bulk';
        
        btnBulk.classList.add('bg-white', 'shadow-sm', 'text-green-600');
        btnBulk.classList.remove('text-gray-400');
        
        btnIndividual.classList.remove('bg-white', 'shadow-sm', 'text-green-600');
        btnIndividual.classList.add('text-gray-400');
    } else {
        bulkSection.classList.add('hidden');
        individualSection.classList.remove('hidden');
        entryType.value = 'individual';
        
        btnIndividual.classList.add('bg-white', 'shadow-sm', 'text-green-600');
        btnIndividual.classList.remove('text-gray-400');
        
        btnBulk.classList.remove('bg-white', 'shadow-sm', 'text-green-600');
        btnBulk.classList.add('text-gray-400');
    }
}
</script>
@endsection
