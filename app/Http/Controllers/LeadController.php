<?php
namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\FollowUp;
use Illuminate\Http\Request;

/**
 * Controller for managing sales leads.
 */
class LeadController extends Controller {
    /**
     * Retrieve a list of leads for the authenticated user, ordered by last contact date.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request) {
        return Lead::where('user_id', $request->user()->id)->orderBy('last_contact')->get();
    }

    /**
     * Store a newly created lead in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\Lead
     */
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

    /**
     * Display the specified lead along with their follow-ups.
     *
     * @param \App\Models\Lead $lead
     * @return \App\Models\Lead
     */
    public function show(Lead $lead) {
        return $lead->load('followUps');
    }

    /**
     * Remove the specified lead from the database.
     *
     * @param \App\Models\Lead $lead
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Lead $lead) {
        $lead->delete();
        return response()->json(['ok' => true]);
    }

    /**
     * Mark a lead as contacted, updating their last_contact timestamp to now.
     *
     * @param \App\Models\Lead $lead
     * @return \App\Models\Lead
     */
    public function markContacted(Lead $lead) {
        $lead->update(['last_contact' => now()]);
        return $lead;
    }

    /**
     * Retrieve dashboard statistics for the authenticated user's leads.
     * Includes total leads, new leads, stale leads (uncontacted > 5 days), and total value.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
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
