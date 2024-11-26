<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\User; 

class PlaceUserInMatrix implements ShouldQueue
{
    use Queueable;

    protected string $userId;
    protected string $referrerId;


    /**
     * Create a new job instance.
     */
    public function __construct(string $userId, string $referrerId)
    {
        $this->userId = $userId;
        $this->referrerId = $referrerId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::find($this->userId);
        $referrer = User::find($this->referrerId);

        if (!$user || !$referrer) {
            return; // Exit if user or referrer doesn't exist
        }

        // Find placement within the referrer's tree
        $placement = $this->findPlacementUser($referrer);

        if ($placement) {
            // Update the user's placed_under column
            $user->placed_under = $placement->id;
            $user->save();
        } else {
            // Handle edge case: no valid placement found
            // (Optional: assign to admin or root user, or log the error)
        }
    }
    /**
     * Find the user to place under within the referrer's tree.
     *
     * @param User $referrer
     * @return User|null
     */
    protected function findPlacementUser($referrer)
    {
        // Start with the referrer
        $queue = [$referrer];
        
        while (!empty($queue)) {
            $current = array_shift($queue);

            // Check if the current user has fewer than 3 children
            $childCount = User::where('placed_under', $current->id)->count();

            if ($childCount < 3) {
                return $current; // Found a valid placement
            }

            // Add current user's children to the queue for further checks
            $children = User::where('placed_under', $current->id)->get();
            foreach ($children as $child) {
                $queue[] = $child;
            }
        }

        return null; // No valid placement found
    }

}
