<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveDuplicateAddresses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:removeDuplicateAddresses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::statement('WITH cte AS (SELECT *, ROW_NUMBER() OVER (PARTITION BY city, state, county, zip, street, house_number) row_num FROM addresses) DELETE FROM addresses USING addresses JOIN cte ON addresses.id = cte.id WHERE cte.row_num > 1;');
        return 0;
    }
}
