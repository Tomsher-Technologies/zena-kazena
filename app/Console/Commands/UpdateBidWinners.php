<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Bid;
use Carbon\Carbon;

class UpdateBidWinners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bids:update-winners';

    // The console command description.
    protected $description = 'Update winner status for products with ended bids';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the current time
        $currentTime = Carbon::now();

        // Get all products where the bid end time has passed and a winner has not been assigned
        $products = Product::where('auction_end_date', '<', $currentTime)
            ->where('type','auction') // Ensure that the product doesn't already have a winner
            ->get();

        foreach ($products as $product) {
            // Find the highest bid for the product
            $highestBid = Bid::where('product_id', $product->id)
                ->orderBy('amount', 'desc') // Order bids by amount in descending order
                ->first();

            if ($highestBid) {
                // Update the winner_id on the product to the user who placed the highest bid
                $highestBid->winner = 1;
                $highestBid->save();
            }
        }

        $this->info('Bid winners updated successfully.');
    }
}
