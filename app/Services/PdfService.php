<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;

class PdfService
{
    

    public function generateFromHtml(string $html)
    {
        return Browsershot::html($html)
            ->format('A4')
            ->margins(20, 20, 20, 20)
            ->showBackground()
            // BỎ LỆNH waitUntilNetworkIdle() ĐỂ TRÁNH TREO
            ->delay(1500) // Nghỉ 1.5 giây để KaTeX vẽ công thức xong
            ->pdf();
    }
}
