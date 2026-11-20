<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Chitgroup;
use App\Models\Chit;

class NewGroupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newgroupcommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new chit group if conditions are met';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Debug indicator

        // Fetch all chits with their details and groups
        $chits = Chit::with('chitdetail', 'chitgroup.bid')->get();

        // Process each chit
        foreach ($chits as $chitData) {
            // Process chit details
            foreach ($chitData['chitdetail'] as $detail) {
                // Here you would process chit details
                // Implement processing logic
            }

            // Process chit groups
            foreach ($chitData['chitgroup'] as $group) {
                $encounteredUserIds = [];

                // Process chit group bids
                foreach ($group['bid'] as $bid) {
                    // Check if the bid's chit_group_id matches the current group's id
                    if (!in_array($bid->user_id, $encounteredUserIds) && $bid->chit_group_id == $group->id) {
                        // Store the user ID in the encountered array
                        $encounteredUserIds[] = $bid->user_id;
                    }
                }

                // Count unique user IDs
                $uniqueUserCount = count($encounteredUserIds);

                // Debug output

                // Check conditions for creating a new group
                if ($uniqueUserCount % $chitData['chitdetail'][0]->duration == 0 && $group->status != 0) {
                    // Update current group's status to 0
                    $oldgroup = Chitgroup::find($group->id);
                    $oldgroup->status = 0;
                    $oldgroup->save();

                    // Generate new group name (increment letter by 1)
                    $asciiValueOfold = ord($oldgroup->group_name);
                    $asciiValueOfnew = $asciiValueOfold + 1;
                    $newLetter = chr($asciiValueOfnew);

                    // Create and save new chit group
                    $todayDate = date("Y-m-d");
                    $newgroup = new Chitgroup;
                    $newgroup->chit_id = $oldgroup->chit_id;
                    $newgroup->group_name = $newLetter;
                    $newgroup->date = $todayDate;
                    $newgroup->save();
                }
            }
        }
    }
}
