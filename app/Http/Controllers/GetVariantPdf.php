<?php

namespace App\Http\Controllers;

use App\Models\MCQ\Variant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetVariantPdf extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function generate(Request $request, Variant $variant, $fontSize)
    {
        $pdf = PDF::loadView('pdf-templates.variant', [
            'variant' => $variant,
            'fontSize' => $fontSize
        ])->setPaper('a4');

        return $pdf->download($variant->class_topic->discipline->name.'-занятие №'.$variant->class_topic->sort_order.'-вариант-'.$variant->id.'.pdf');
    }
}
