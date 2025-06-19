<?php
namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Knp\Snappy\Pdf;

class Utils extends BaseController
{
    
    public function downloadPDF(){
        
        $html = $this->request->getGet('html');
        $code = $this->request->getGet('code');

        $snappy = new Pdf(ROOTPATH . '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
        // $snappy = new Pdf("'C:\PROGRA~1\wkhtmltopdf\bin\wkhtmltopdf.exe'");
        $snappy->setOptions(array(
            'enable-javascript' => false,
            'encoding' => 'UTF-8',
            "lowquality" => null,
            "dpi" => 300,
            'page-size' => "Letter",
            "title" => $code.".pdf",
            "footer-center" => "Página [page] de [topage]",
            "margin-bottom" => "20mm",
        ));
        
        $this->response->setHeader('Content-Type', 'text/plain');
        $this->response->setBody($snappy->getOutputFromHtml($html));
        return $this->response;
    }

    // public function viewPDF(){
    //     $data = $this->request->getJSON();

    //     return view('dte/pdf', $data);
    // }
    
    public function sendDte2() {

        $data = $this->request->getJSON();

        if (isset($data->secret__) && $data->secret__ == "96EB06FA-A697-4A44-96C7-5D02758CD06B") {
            $json = $data->dte;
            if (!isset($data) || $data == null) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'DTE no encontrado',
                ]);
            }

            try {
                $to_oo = "luisrivera4540@gmail.com";

                $a = $this->send_mail($json, $to_oo, "Facturación Electrónica Riveras Group", "Facturación Electrónica Riveras Group", null, "admin@riverasgroup.com");




                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => "mensaje enviado",
                    'error' => $a,
                ]);


            }
            catch (\Exception $e) {

                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Falta el Token de Comunicacion',
        ]);
    }


    private function send_mail($json, $to, $message, $subject, $AltBody = null, $cc = null, $bcc = null) {
        try {

            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            // $mail->Host = "p3plzcpnl505881.prod.phx3.secureserver.net";
            // $mail->SMTPAuth = true;
            // $mail->Username = "facturacion@riverasgroup.com";
            // $mail->isHTML(true);
            // $mail->Password = "&f?{V7,_kdWd";
            // $mail->SMTPSecure = 'ssl';
            // $mail->Port = 465;



            $mail->Host = "smtpout.secureserver.net";
            $mail->SMTPAuth = true;
            $mail->Username = "facturcion@riverasgroup.com";
            $mail->isHTML(true);
            $mail->Password = "Clave123!";
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('facturacion@riverasgroup.com', 'Facturación Electrónica Riveras Group');
            if (is_array($to)) {
                foreach ($to as $item) {
                    if ($item !== "") {
                        $mail->addAddress($item);
                    }
                }
            }
            else {
                $mail->addAddress($to);
            }

            if ($cc) {
                if (is_array($cc)) {
                    foreach ($cc as $item) {
                        if ($item !== "") {
                            $mail->addCC($item);
                        }
                    }
                }
                else {
                    $mail->addCC($cc);
                }
            }

            if ($bcc) {
                if (is_array($bcc)) {
                    foreach ($bcc as $item) {
                        if ($item !== "") {
                            $mail->addCC($item);
                        }
                    }
                }
                else {
                    $mail->addCC($bcc);
                }
            }

            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;

            // $json = json_encode($data);
            // // $email->attach($buffer, 'attachment', $pdf_name, 'application/pdf');
            // $email->attach($json, 'attachment', $json_name, 'application/json');
            // $email->send();

            $json_formateado = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);


            $mail->addStringAttachment($json_formateado, "dte_example.json", '8bit', 'application/json', 'attachment');
            $mail->addAttachment(WRITEPATH . "a.pdf");
            $mail->Body = $message;
            if ($AltBody) {
                $mail->AltBody = $AltBody;
            }
            $mail->send();
            return $mail->ErrorInfo;
        }
        catch (\Exception $e) {
            var_dump($e);
            return $mail->ErrorInfo;
        }
    }

}