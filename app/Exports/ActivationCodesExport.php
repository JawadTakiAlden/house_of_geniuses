<?php

namespace App\Exports;

use App\Models\ActivationCode;
use Maatwebsite\Excel\Concerns\FromCollection;

class ActivationCodesExport implements FromCollection
{
    protected $activationCodes;

    public function __construct(array $activationCodes)
    {
        $this->activationCodes = $activationCodes;
    }

    public function collection()
    {
        return $this->activationCodes;
    }
}
