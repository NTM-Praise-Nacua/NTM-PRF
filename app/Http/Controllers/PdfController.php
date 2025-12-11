<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Log;

class PdfController extends Controller
{
    public function index()
    {
        // Path to your template PDF
        return view('pdf_upload');
    }

    // public function savePdf(Request $request)
    // {
    //     $data = $request->input('fields'); // array of {text, x, y}
    //     $template = $request->input('template', '3cb107d7-77c7-4b04-a56a-d9e32c2259b8_NTM Credentials 2025.pdf');

    //     $pdf = new FPDI();
    //     $pdf->AddPage();
    //     $pdf->setSourceFile(storage_path("app/templates/{$template}"));
    //     $tpl = $pdf->importPage(1);
    //     $pdf->useImportedPage($tpl);

    //     $pdf->SetFont('Helvetica');
    //     $pdf->SetTextColor(0, 0, 0);

    //     foreach ($data as $field) {
    //         $x = $field['x'] * 0.75; // adjust scale factor (PDF.js viewport 1.5)
    //         $y = ($pdf->GetPageHeight() - $field['y'] * 0.75);
    //         $pdf->SetXY($x, $y);
    //         $pdf->Write(0, $field['text']);
    //     }

    //     $filename = "Crop_Passport_" . time() . ".pdf";
    //     $path = storage_path("app/public/{$filename}");
    //     $pdf->Output('F', $path);

    //     return response()->json(['success' => true, 'file' => asset("storage/{$filename}")]);
    // }

    public function modifyPdf(Request $request)
    {
        $request->validate([
            'link' => 'required',
            'positions' => 'required',
        ]);

        $originalFile = storage_path('app/public/pdfs/' . $request->link);
        $editedFile = storage_path('app/public/pdfs/edited_' . $request->link);

        try {
            $pdf = new Fpdi('P', 'pt');
            $pdf->setSourceFile($originalFile);

            $tplId = $pdf->importPage(1);
            $size = $pdf->getTemplateSize($tplId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useImportedPage($tplId);

            
            foreach ($request->positions as $pos) {
                $fontSize = $pos['fontSize'];
                $fsPts = $fontSize * (72/96);
                $pdf->SetFont('Helvetica', '', $fsPts);
                $pdf->SetTextColor(0,0,0);

                $x = (float) $pos['posX'] - 3;
                $y = (float) $pos['posY'] - ($fsPts / 4);

                $pdf->SetXY($x, $y);
                $pdf->Write(8, $pos['text']);
            }

            // $pdf->SetXY((int) $request->x - 3, (int) $request->y - ($fontSize / 4));
            // $pdf->Write(8, $request->text);

            $pdf->Output('F', $editedFile);

            unlink($originalFile);
            rename($editedFile, $originalFile);

            return response()->json([
                'status' => 'success',
                'message' => 'PDF updated',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }

        // try {
        //     $pdf = new Fpdi();
        //     $pdf->AddPage();
        //     $pdf->setSourceFile($originalFile);
        //     $tplId = $pdf->importPage(1);
    
        //     $pdf->useImportedPage($tplId);
    
        //     // important
        //     $pdf->SetFont('Helvetica');
        //     $pdf->SetTextColor(0, 0, 0);

        //     $size = $pdf->getTemplateSize($tplId);
        //     $pdf->setXY($size['width']/2,$size['height']/2);
        //     $pdf->Write(0,'Testing By Praise');
    
        //     $pdf->Output('F', $editedFile);

        //     // Delete

        //     if (file_exists($originalFile)) {
        //         unlink($originalFile);
        //     }

        //     rename($editedFile, $originalFile);
            
        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'PDF File Edited',
        //         'download' => $editedFile
        //     ]);
        // } catch (\Exception $e) {
        //     Log::error("PDF editing failed: " . $e->getMessage());
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'PDF editing failed: ' . $e->getMessage(),
        //     ]);
        // }

        // return json_encode([
        //     'status' => 'success',
        //     'link' => $request->link
        // ]);
        // $request->validate([
        //     'pdf_file' => 'required|mimes:pdf|max:10000',
        // ]);

        // // Store uploaded PDF
        // $pdfPath = $request->file('pdf_file')->store('public/pdfs');

        // $fullPath = storage_path('app/' . $pdfPath);

        // $pdf = new Fpdi();

        // // Load existing PDF
        // $pageCount = $pdf->setSourceFile($fullPath);

        // for ($i = 1; $i <= $pageCount; $i++) {
        //     $templateId = $pdf->importPage($i);
        //     $size = $pdf->getTemplateSize($templateId);

        //     $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        //     $pdf->useTemplate($templateId);

        //     // Add watermark
        //     $pdf->SetFont('Helvetica', 'B', 50);
        //     $pdf->SetTextColor(200, 200, 200); // light gray
        //     $pdf->SetXY($size['width']/4, $size['height']/2);
        //     $pdf->Write(0, 'CONFIDENTIAL');

        //     // Add page number
        //     $pdf->SetFont('Helvetica', '', 12);
        //     $pdf->SetTextColor(0, 0, 0);
        //     $pdf->SetXY($size['width'] - 20, $size['height'] - 10);
        //     $pdf->Write(0, "Page $i of $pageCount");
        // }

        // // Save modified PDF
        // $modifiedPath = storage_path('app/public/pdfs/modified.pdf');
        // $pdf->Output($modifiedPath, 'F');

        // // Return link to modified PDF
        // return redirect()->back()->with('success', 'PDF modified successfully! You can <a href="' . asset('storage/pdfs/modified.pdf') . '" target="_blank">view it here</a>.');
    }
}
