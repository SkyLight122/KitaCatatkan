<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;
use Carbon\Carbon;

class Notification extends Component
{
    public $unreadCount = 0;
    public $showNotificationPanel = false;

    protected $listeners = [
        'statusUpdated' => 'loadNotifications', // Listen for status updates
    ];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        // Calculate unread count but don't store in reactive property
        $now = Carbon::now()->startOfDay();
        $twoDaysLater = $now->copy()->addDays(2)->endOfDay();

        // Count personal assignments
        $personalCount = Assignment::where('user_id', Auth::id())
            ->whereIn('status_id', [1, 2])
            ->whereBetween('due_date', [$now, $twoDaysLater])
            ->count();
        
        // Count group assignments - ONLY count if not completed (status_id != 3)
        $userGroupIds = Auth::user()->groups()->pluck('groups.id')->toArray();
        $groupCount = 0;
        if ($userGroupIds) {
            $groupCount = \App\Models\GroupAssignments::whereIn('group_id', $userGroupIds)
                ->whereBetween('due_date', [$now, $twoDaysLater])
                ->whereHas('users', fn($q) => 
                    $q->where('user_id', Auth::id())
                      ->whereIn('group_assignment_users.status_id', [1, 2]) // Only count if not completed
                )
                ->count();
        }
        
        $this->unreadCount = $personalCount + $groupCount;
    }

    public function toggleNotificationPanel()
    {
        $this->showNotificationPanel = !$this->showNotificationPanel;
    }

    public function markAsViewed()
    {
        $this->showNotificationPanel = false;
    }

    public function getNotifications()
    {
        $now = Carbon::now()->startOfDay();
        $twoDaysLater = $now->copy()->addDays(2)->endOfDay();

        // Personal assignments
        $personalNotifs = Assignment::where('user_id', Auth::id())
            ->whereIn('status_id', [1, 2])
            ->whereBetween('due_date', [$now, $twoDaysLater])
            ->with(['category', 'typeAssignment'])
            ->get();
        
        // Group assignments - ONLY show if not completed (status_id != 3)
        $userGroupIds = Auth::user()->groups()->pluck('groups.id')->toArray();
        $groupNotifs = collect();
        if ($userGroupIds) {
            $groupNotifs = \App\Models\GroupAssignments::with(['category', 'typeAssignment'])
                ->whereIn('group_id', $userGroupIds)
                ->whereBetween('due_date', [$now, $twoDaysLater])
                ->whereHas('users', fn($q) => 
                    $q->where('user_id', Auth::id())
                      ->whereIn('group_assignment_users.status_id', [1, 2]) // Only show if not completed
                )
                ->get();
        }
        
        return $personalNotifs->concat($groupNotifs)->sortBy('due_date')->values();
    }

    public function render()
    {
        return view('livewire.notification', [
            'notifications' => $this->getNotifications(),
        ]);
    }
}
