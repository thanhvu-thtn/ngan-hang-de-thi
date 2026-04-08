<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user, $ability) {
        // Nếu bạn muốn Admin luôn có mọi quyền, bạn trả về true (như cũ)
        // NHƯNG vì bạn muốn hạn chế, hãy comment hoặc xóa dòng return true đi.
        
        // Ví dụ: Chỉ cho Admin làm "Trùm" ở các quyền quản trị hệ thống, 
        // còn các quyền chuyên môn thì phải check role bình thường:
        /*
        if ($user->hasRole('admin')) {
             return true; 
        }
        */
        
        // Để trống ở đây sẽ khiến Laravel check Role/Permission 
        // chính xác theo những gì bạn viết trong web.php
    });
    }
}
