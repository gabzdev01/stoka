<?php
namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Stancl\Tenancy\Facades\Tenancy;

class BackfillSlugs extends Command
{
    protected $signature   = 'products:backfill-slugs {--tenant= : Specific tenant ID}';
    protected $description = 'Generate slugs for products that have none';

    public function handle(): int
    {
        $tenantIds = $this->option('tenant')
            ? [$this->option('tenant')]
            : \Stancl\Tenancy\Database\Models\Tenant::pluck('id')->toArray();

        foreach ($tenantIds as $tenantId) {
            Tenancy::initialize($tenantId);

            $missing = Product::whereNull('slug')->orWhere('slug', '')->get();

            if ($missing->isEmpty()) {
                $this->line("  {$tenantId}: all slugs present");
                Tenancy::end();
                continue;
            }

            $this->info("  {$tenantId}: backfilling {$missing->count()} slugs");

            foreach ($missing as $product) {
                $product->slug = Product::uniqueSlug($product->name, $product->id);
                $product->saveQuietly();
            }

            Tenancy::end();
        }

        $this->info('Done.');
        return 0;
    }
}
