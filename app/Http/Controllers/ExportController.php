<?php

namespace App\Http\Controllers;

use App\Exports\AnketaExport;
use Maatwebsite\Excel\Excel;

class ExportController
{
    private $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }
    
    public function exportViaConstructorInjection()
    {
        return $this->excel->download(new AnketaExport, 'users.xlsx');
    }
    
    public function exportViaMethodInjection(Excel $excel)
    {
        return $excel->download(new AnketaExport, 'users.xlsx');
    }
}