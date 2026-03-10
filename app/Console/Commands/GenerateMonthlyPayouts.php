<?php

namespace App\Console\Commands;

use App\Services\CommissionService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class GenerateMonthlyPayouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payouts:generate 
                            {--year= : Year for payout generation (default: current year)}
                            {--month= : Month for payout generation (default: last month)}
                            {--all-months : Generate payouts for all months in the year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly payout reports for all hotels';

    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        parent::__construct();
        $this->commissionService = $commissionService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->option('year') ?? Carbon::now()->year;
        $month = $this->option('month') ?? Carbon::now()->subMonth()->month;
        $allMonths = $this->option('all-months');

        $this->info("Generating monthly payouts...");

        if ($allMonths) {
            $this->info("Processing all months for year {$year}");
            
            for ($m = 1; $m <= 12; $m++) {
                $this->processMonth($year, $m);
            }
        } else {
            $this->info("Processing: " . Carbon::create($year, $month, 1)->format('F Y'));
            $this->processMonth($year, $month);
        }

        $this->info("Payout generation completed!");
        
        return 0;
    }

    protected function processMonth($year, $month)
    {
        $this->line("\nGenerating payouts for " . Carbon::create($year, $month, 1)->format('F Y') . "...");

        try {
            $payouts = $this->commissionService->generateAllHotelPayouts($year, $month);

            if (empty($payouts)) {
                $this->warn("  No bookings found for this period.");
                return;
            }

            $this->info("  Generated payouts for " . count($payouts) . " hotels");

            // Display summary
            $totalCommission = 0;
            $totalPayout = 0;

            foreach ($payouts as $payout) {
                $totalCommission += $payout->total_commission;
                $totalPayout += $payout->hotel_payout_amount;
            }

            $this->table(
                ['Metric', 'Amount'],
                [
                    ['Total Platform Commission', number_format($totalCommission, 2)],
                    ['Total Hotel Payouts', number_format($totalPayout, 2)],
                    ['Total Guest Payments', number_format($totalCommission + $totalPayout, 2)],
                ]
            );

        } catch (\Exception $e) {
            $this->error("  Error: {$e->getMessage()}");
        }
    }
}
