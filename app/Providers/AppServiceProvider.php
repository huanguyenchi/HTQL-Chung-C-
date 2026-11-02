<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        Paginator::useBootstrapFive();

        // @vnd($soTien)  ->  1.250.000 đ
        Blade::directive('vnd', function ($expression) {
            return "<?php echo number_format((int) ($expression), 0, ',', '.') . ' đ'; ?>";
        });
    }
}
