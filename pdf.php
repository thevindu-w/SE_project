<?php
require_once('utils/auth.php');
checkAuth();

require_once "vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['text']) && $_POST['text']) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator('Multi-Grammar');
        $pdf->SetAuthor('Multi-Grammar');
        $pdf->SetTitle('Multi-Grammar PDF');
        $pdf->SetSubject('Multi-Grammar PDF');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        $pdf->SetFont('dejavusans', '', 14, '', true);

        // Add a page
        $pdf->AddPage();

        // Set some content to print
        $text = $_POST['text'];
        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $text, 0, 1, 0, true, '', true);

        $pdfFilePath = $_SERVER['DOCUMENT_ROOT'] . "/tmp/text.pdf";
        while (file_exists($pdfFilePath)) {
            $i = rand(0,PHP_INT_MAX);
            $pdfFilePath = $_SERVER['DOCUMENT_ROOT'] . "/tmp/text$i.pdf";
        }
        $pdf->Output($pdfFilePath, 'F');
        header('Content-Type: application/pdf');
        header("Content-Disposition: attachment;");
        header('Content-Length: ' . filesize($pdfFilePath));
        ob_clean();
        flush();
        readfile($pdfFilePath);
        unlink($pdfFilePath);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
    }
} else {
    header('Location: /grammar.php');
}
