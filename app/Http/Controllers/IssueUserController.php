<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issue;
use App\Models\User;
class IssueUserController extends Controller
{
   
    public function toggle(Request $request, Issue $issue)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // syncWithoutDetaching is an alternative..
        $result = $issue->users()->toggle($request->user_id);
        
        $attached = count($result['attached']) > 0;

        return response()->json([
            'success' => true,
            'attached' => $attached,
            'message' => $attached ? 'User assigned successfully.' : 'User unassigned successfully.',
            'users' => $issue->users()->get() // refresh UI partials 
        ]);
    }
}
    
