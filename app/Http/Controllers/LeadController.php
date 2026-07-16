<?php
namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\FollowUp;
use Illuminate\Http\Request;

class LeadController extends Controller {
    public function index(Request $request) {
        return Lead::where('user_id', $request->user()->id)->orderBy('last_contact')->get();
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'company' => 'nullable|string',
            'value' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);
        $data['user_id'] = $request->user()->id;
        $data['last_contact'] = now();
        return Lead::create($data);
    }

    public function show(Lead $lead) {
        return $lead->load('followUps');
    }

    public function destroy(Lead $lead) {
        $lead->delete();
        return response()->json(['ok' => true]);
    }

    public function markContacted(Lead $lead) {
        $lead->update(['last_contact' => now()]);
        return $lead;
    }

    public function dashboard(Request $request) {
        $userId = $request->user()->id;
        $leads = Lead::where('user_id', $userId)->get();
        $stale = Lead::where('user_id', $userId)
            ->where('last_contact', '<', now()->subDays(5))
            ->whereNotIn('status', ['closed', 'won'])
            ->count();
        return [
            'total' => $leads->count(),
            'new' => $leads->where('status', 'new')->count(),
            'stale' => $stale,
            'total_value' => $leads->sum('value'),
        ];
    }
}
