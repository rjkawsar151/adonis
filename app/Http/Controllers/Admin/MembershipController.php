<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipRequest;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        $query = MembershipRequest::orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by name, phone, or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(15);

        return view('admin.memberships.index', compact('requests'));
    }

    public function updateStatus(Request $request, MembershipRequest $membership)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,declined',
        ]);

        $membership->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Membership request status updated.');
    }

    public function destroy(MembershipRequest $membership)
    {
        $membership->delete();
        return redirect()->route('admin.memberships.index')->with('success', 'Membership request deleted.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:membership_requests,id',
            'action' => 'required|string|in:confirm,decline,delete'
        ]);

        $ids = $request->ids;
        $action = $request->action;

        if ($action === 'delete') {
            MembershipRequest::whereIn('id', $ids)->delete();
        } else {
            $status = $action === 'confirm' ? 'confirmed' : 'declined';
            MembershipRequest::whereIn('id', $ids)->update(['status' => $status]);
        }

        return redirect()->route('admin.memberships.index')->with('success', 'Bulk action executed successfully.');
    }
}
