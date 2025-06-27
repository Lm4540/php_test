<?php
namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;
use Knp\Snappy\Pdf;
use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

class Home extends BaseController
{
    private string $notFoundPath = ROOTPATH . 'public_html' . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . 'not-found.jpg';
    private string $binary = '"C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe"';
    // private string $binary =  ROOTPATH . '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64';
    private function log($var): void {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }

    public function index(): string {
        return view('landing', ['title' => 'Riveras Group']);
    }

    public function about(): string {
        return view('about', ['title' => 'About Us']);
    }
    public function contact(): string {
        return view('contact', ['title' => 'Contactanos']);
    }

    public function sucursal(): string {
        return view('sucursals', ['title' => 'Sucursales']);
    }

    public function terms(): string {
        return view('terms', ['title' => 'Terminos y Condiciones de Uso']);
    }

    public function privacy_policy(): string {
        return view('privacy', ['title' => 'Politicas de Provacidad']);
    }

    public function login() {


        return $this->session->has('client') && $this->session->has('user')
            ? redirect()->to(site_url('vip'))
            : view('login', ['title' => 'Iniciar Session',]);
    }

    public function logout() {
        $this->session->destroy();
        // return redirect()->route('login');
        return redirect()->to(site_url('/'));
    }

    public function login_post() {
        //obtener los datos del cliente
        $pin = $this->request->getPost('pin');
        $document = $this->request->getPost('document');

        if (strlen($pin) < 4 || strlen($document) < 10) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Datos Invalidos',
            ]);
        }
        $pin = intval($pin);
        $response = $this->post_data('client', ['pin' => $pin, 'document' => $document]);

        if ($response['status'] == 'success') {
            $data = $response['data'];
            $data['client'] = $pin;

            $this->session->set('user', $data);
            $this->session->set('client', $pin);
            $this->session->set('access_to_all_products', $data['web_products']);


            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Todo nice',
            ]);
        }
        return $this->response->setJSON($response);
    }

    public function image() {
        $image = $_GET['img'];
        $this->response->setHeader("Content-Type", "image/jpg")->setHeader('Cache-Control', 'public, store, cache')->setHeader('Pragma', 'cache');
        $this->response->setCache([
            'max-age' => 60000,
            's-maxage' => 60000,
            'etag' => 'abcde',
        ]);





        $path = ROOTPATH . 'public_html' . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . $image;

        if (!isset($_GET['renew']) && file_exists($path)) {


            return $this->response->setBody(file_get_contents($path))->send();
        }

        //Si no existe solicitar a la API
        $uri = 'product/image?img=' . urlencode($image);
        $data = $this->get_data($uri);

        //Si la Api respondio correctamente
        if ($data['status'] == 'success') {
            //decodificar la informacion
            $dataImage = base64_decode($data['image']);
            //verificar que sea un archivo valido
            if ($this->check_base64_image($dataImage)) {
                //guardar el contenido en un archivo
                file_put_contents($path, $dataImage);
                //enviar el stream de la imagen
                return $this->response->setBody($dataImage)->send();
            }
        }

        //en caso de que ni existe ni se haya obtenido d ela api, envia la imagen por defecto
        return $this->response->setBody(file_get_contents($this->notFoundPath))->send();
        // return $this->response->send();
    }

    private function post_data($uri, $data, $method = 'POST', $retry = true): array {
        $api_url = $_ENV['API_ROUTE_V1'] . $uri;
        $client = new Client();
        try {
            // Realizar la solicitud POST para autenticarse
            $res = $client->request($method, $api_url, [
                'json' => $data,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->last_token,
                    'Accept' => 'application/json',
                ],
            ]);

            // Obtener el cuerpo de la respuesta y decodificar
            $data = $res->getBody()->getContents();
            return json_decode($data, true);
        }
        catch (ClientException $e) {

            $this->last_token = $this->get_token();
            return $retry
                ? $this->post_data($uri, $data, 'POST', false)
                : ['status' => 'error', 'message' => 'Error de autenticacion Clase 1'];


            // $e->getMessage();
            // Manejar errores
        }
        catch (ServerException $e) {
            return [
                'status' => 'error',
                'message' => 'Error en el servidor, Clase 2'];


            // 'Server error: ' . $e->getMessage() . '\n Response: ' . $e->getResponse()->getBody()->getContents()
        }
        catch (ConnectException $e) {
            // Error de conexión
            return ['status' => 'error', 'message' => "Error de comunicación con el Servidor, Clase 3"];
        }
        catch (RequestException $e) {
            // Error general de solicitud

            return [
                'status' => 'error',
                'message' => "Error de comunicación con el Servidor, Clase 4"];
            // 'Request error: '
            // . $e->getMessage()
            // . ($e->hasResponse() ? '\nResponse: ' . $e->getResponse()->getBody()->getContents() : '')
        }
    }

    private function get_data($uri, $retry = true, $method = 'GET') {
        $api_url = $_ENV['API_ROUTE_V1'] . $uri;
        //Obtener el token almacenado
        $token = $this->last_token;
        // Crear el cliente Guzzle y capturar los errores
        $client = new Client();

        try {
            // Realizar la solicitud GET
            $res = $client->request($method, $api_url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ]);
            $data = [];
            // Obtener el cuerpo de la respuesta
            $data = $res->getBody()->getContents();
            // $this->log($data);
            return json_decode($data, true);

        }
        catch (ClientException $e) {
            $this->last_token = $this->get_token();
            return $retry
                ? $this->get_data($uri, false)
                : ['status' => 'error', 'message' => $e->getMessage()];
        }
        catch (ServerException $e) {
            return [
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage() . '\n Response: ' . $e->getResponse()->getBody()->getContents()];
        }
        catch (ConnectException $e) {
            // Error de conexión
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
        catch (RequestException $e) {
            // Error general de solicitud

            return [
                'status' => 'error',
                'message' => 'Request error: '
                    . $e->getMessage()
                    . ($e->hasResponse() ? '\nResponse: ' . $e->getResponse()->getBody()->getContents() : '')];
        }
    }



    private function get_token(): string {
        $api_url = $_ENV['API_ROUTE_V1'] . 'login';

        $client = new Client();
        try {
            // Realizar la solicitud POST para autenticarse
            $res = $client->request('POST', $api_url, [
                'json' => [
                    'identification' => $_ENV['API_IDENTIFICATION_V1'],
                    'secret' => $_ENV['API_SECRET_V1'],
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            // Obtener el cuerpo de la respuesta y decodificar
            $data = $res->getBody()->getContents();
            $decoded_data = json_decode($data, true);

            // Guardar el token en la sesión del servidor si la autenticación fue exitosa
            if (isset($decoded_data['token'])) {
                // Guardar el token en la sesión del servidor
                return ($this->save_new_token($decoded_data['token']) ? $decoded_data['token'] : null);
            }
            else {
                return 'token no recibido';
            }
        }
        catch (ClientException $e) {
            // Manejar errores
            return $e->getMessage();
        }
    }

    private function save_new_token($token): bool {
        $nombre_archivo = WRITEPATH . "token.php";
        try {
            if ($archivo = fopen($nombre_archivo, "w")) {
                if (fwrite($archivo, "<?php  return '" . $token . "';")) {
                    fclose($archivo);
                    return true;
                }
            }
        }
        catch (\Exception $e) {

        }
        return false;
    }

    private function check_base64_image($data): bool {
        if ($data != "") {
            try {
                $img = imagecreatefromstring($data);
                if ($img) {
                    $size = getimagesizefromstring($data);
                    return (!$size || $size[0] == 0 || $size[1] == 0 || !$size['mime']) ? false : true;
                }
            }
            catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }

    public function catalog() {
        if ($this->session->has('client') && $this->session->has('user')) {
            return redirect()->to(site_url('vip/product'));
        }
        $categories = $this->get_data('categories');
        $categories = $categories['status'] == "success" ? $categories['data'] : [];
        return view('products', ['title' => 'Riveras Group', 'categories' => $categories]);
    }

    public function catalogProducts() {

        $products = $this->get_data('products');
        if ($products['status'] == "success") {
            return $this->response->setJSON($products);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'No se pudieron obtener Productos']);

    }

    //De aqui para abajo solo los clientes que se han autenticado
    public function Vip() {
        if ($this->session->has('access_to_all_products') && $this->session->get('access_to_all_products') == true) {
            return view('clients/vip', ['title' => 'Riveras Group']);
        }
        return redirect()->to(site_url('vip/catalogs'));
    }

    public function ClientCatalogs() {
        $uri = 'catalog/' . $this->session->get('client');
        $catalogs = $this->get_data($uri);
        $catalogs = $catalogs['status'] == "success" ? $catalogs['data'] : [];
        // $this->log($catalogs);
        return view('clients/catalogs', ['title' => 'Catálogos -Riveras Group', 'categories' => $catalogs]);
    }
    public function ClientCatalog($id) {
        $name = isset($_GET['name']) ? $_GET['name'] : 'Catalogo #' . $id;
        return view('clients/catalogProducts', ['title' => $name . ' - Riveras Group', 'catalog' => $id, 'name' => $name]);
    }

    public function ClientCatalogProducts($id) {
        $uri = 'catalog/' . $this->session->get('client') . '/' . $id;
        $products = $this->get_data($uri);
        if ($products['status'] == "success") {
            $products['sellerName'] = $this->session->get('user')['seller_name'];
            $products['sellerNumber'] = $this->session->get('user')['seller_number'];
            return $this->response->setJSON($products);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'No se pudieron obtener Productos']);

    }

    public function ClientCategories() {
        if ($this->session->has('access_to_all_products') && $this->session->get('access_to_all_products') == true) {
            $categories = $this->get_data('categories');
            $categories = $categories['status'] == "success" ? $categories['data'] : [];
            return view('clients/categories', ['title' => 'Riveras Group', 'categories' => $categories]);
        }
        return redirect()->to(site_url('vip/catalogs'));
    }

    public function ClientCategorie($id) {
        //retornar la vista de visualñizacion de una categoria
        $data = $this->get_data('categories');
        $data = $data['status'] == "success" ? $data['data'] : [];
        $categorie = [];
        foreach ($data as $cat) {
            if (intval($cat['id']) == intval($id)) {
                $categorie = $cat;
            }
        }
        return view('clients/categoryProducts', ['title' => 'Ver categoria | Riveras Group', 'name' => 'Categoria ' . $categorie['name'], 'categories' => [$categorie,], 'lock_categorie' => $id]);


    }

    public function ClientallProducts() {
        //retornar la vista de visualñizacion de una categoria
        $categories = $this->get_data('categories');
        $categories = $categories['status'] == "success" ? $categories['data'] : [];
        return view('clients/categoryProducts', ['title' => 'Catálogo de Productos | Riveras Group', 'name' => 'Catálogo de Productos', 'categories' => $categories, 'lock_categorie' => null]);


    }

    public function clientProduct() {
        // /product_search/:client(\\d+)/:category(\\d+)/:onlyStock(\\d+)
        $cat = isset($_GET['cat']) ? $_GET['cat'] : '0';
        $products = $this->get_data('/product_search/' . $this->session->get('client') . '/' . $cat . '/1');
        if ($products['status'] == "success") {
            $products['sellerName'] = $this->session->get('user')['seller_name'];
            $products['sellerNumber'] = $this->session->get('user')['seller_number'];
            return $this->response->setJSON($products);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'No se pudieron obtener Productos']);

    }

    public function viewProduct($id) {
        // $product = $this->get_data('product/detail/'. $id);

        // if ($product['status'] == 'success') {
        //     $product = $product['data'];
        //     //verificar si el cliente esta loguiedo y devolver una vista u otra


        // }
        // return view('viewProduct');
        return view('test');
        // return view('Product404.php');
    }


    public function ClientViewProduct($id) {
        $product = $this->get_data('product/detail/' . $id);

        if ($product['status'] == 'success') {
            $product = $product['data'];
            //verificar si el producto tiene otras imagenes
            $more_images = [];
            //verificar si el cliente esta loguiedo y devolver una vista u otra
            return view('viewProduct_', ['title' => $product['name'] . ' | Riveras Group', 'product' => $product, 'images' => $more_images]);
        }

        return view('Product404');
    }

    public function getProductData($id) {
        $product = $this->get_data('product/detail/' . $id);

        if ($product['status'] == 'success') {
            return $this->response->setJSON($product);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Product ,no encontrado']);
    }

    public function rm_image() {

        if (isset($_GET['img'])) {

            $image = $_GET['img'];
            $path = ROOTPATH . 'public_html' . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . $image;

            if (file_exists($path)) {
                unlink($path);
                return $this->response->setJSON(["status" => "success", "message" => "Image deleted"]);
            }
            return $this->response->setJSON(["status" => "error", "message" => "image not found"]);
        }
        return $this->response->setJSON(["status" => "error", "message" => "Parametro no recibido"]);
    }

    public function downloadPDF($id) {
        $data = $this->get_data('dte/' . $id);


        if ($data['status'] == 'success') {

            $snappy = new Pdf($this->binary);
            //$snappy = new Pdf(ROOTPATH . '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
            $snappy->setOptions(array(
                'enable-javascript' => false,
                'encoding' => 'UTF-8',
                "lowquality" => null,
                "dpi" => 300,
                'page-size' => "Letter",
                "title" => $data['dte']['identificacion']['codigoGeneracion'] . ".pdf",
                "footer-center" => "Página [page] de [topage]",
                "footer-font-name" => "Verdana",
                "footer-font-size" => "6",
                "margin-bottom" => "20mm",
            ));



            $this->response->setHeader('Content-Type', 'application/pdf');
            $this->response->setBody($snappy->getOutputFromHtml($data['data']));
            return $this->response;


        }

        return $this->response->setBody('Documento no Encontrado');
    }

    public function getDTE($param) {
        $uuid = $this->request->getGet('uuid');
        $fecha = $this->request->getGet('fecha');

        if ($param == 'json') {
            $data = $this->get_data('dte_json/' . $uuid . '/' . $fecha);

            if ($data['status'] == 'success') {

                $jsonContent = json_encode($data['json'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

                // Verifica si hubo un error en la codificación JSON
                if ($jsonContent === false) {
                    return $this->response->setStatusCode(500)
                        ->setJSON(['status' => 'error', 'message' => 'Error al generar el archivo JSON.']);
                }

                // 3. Establecer las cabeceras HTTP para la descarga
                $filename = $data['json']['identificacion']['codigoGeneracion'] . '.json'; // Nombre del archivo sugerido

                return $this->response
                    ->setHeader('Content-Type', 'application/json') // Tipo de contenido JSON
                    ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"') // Fuerza la descarga con un nombre de archivo
                    ->setBody($jsonContent);

            }
            return $this->response->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Documento no encontrado, revise los datos y consulte nuevamente']);

        }
        else if ($param == 'inline' || $param == 'download') {

            $data = $this->get_data('dte/' . $uuid . '/' . $fecha);

            if ($data['status'] == 'success') {

                $snappy = new Pdf($this->binary);

                $document_name = $data['dte']['identificacion']['codigoGeneracion'] . ".pdf";
                $snappy->setOptions(array(
                    'enable-javascript' => false,
                    'encoding' => 'UTF-8',
                    "lowquality" => null,
                    "dpi" => 300,
                    'page-size' => "Letter",
                    "title" => $document_name,
                    "footer-center" => "Página [page] de [topage]",
                    "footer-font-name" => "Verdana",
                    "footer-font-size" => "6",
                    "margin-bottom" => "20mm",
                ));



                $this->response->setHeader('Content-Type', 'application/pdf');
                $this->response->setBody($snappy->getOutputFromHtml($data['data']));

                return $param == "inline" ? $this->response : $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $document_name . '"');
            }
            return $this->response->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Documento no encontrado, revise los datos y consulte nuevamente']);
        }

        return $this->response->setStatusCode(404)
            ->setJSON(['status' => 'error', 'message' => 'Opcion no valida']);
    }

    public function sendPDF($id) {
        try {
            if ($this->request->getGet('dummy_key') !== '03b10bac1ef3b941?hl=es') {
                return $this->response->setStatusCode(401)
                    ->setJSON(['status' => 'error', 'message' => '401 Unauthorized']);
            }

            $data = $this->get_data('dte/' . $id);
            if ($data['status'] == 'success') {
                if ($data['dte']['receptor']['correo'] == null || $data['dte']['receptor']['correo'] == "") {
                    return $this->response->setJSON(
                        ['status' => 'success', 'message' => 'Este documento no contiene una direccion de correo para enviar el documento']
                    );
                }

                $snappy = new Pdf($this->binary);

                $snappy->setOptions(array(
                    'enable-javascript' => false,
                    'encoding' => 'UTF-8',
                    "lowquality" => null,
                    "dpi" => 300,
                    'page-size' => "Letter",
                    "title" => $data['dte']['identificacion']['codigoGeneracion'] . ".pdf",
                    "footer-center" => "Página [page] de [topage]",
                    "footer-font-name" => "Verdana",
                    "footer-font-size" => "6",
                    "margin-bottom" => "20mm",

                ));
                $pdf_file_location = WRITEPATH . '/pdf/' . $data['dte']['identificacion']['codigoGeneracion'] . ".pdf";
                $snappy->generateFromHtml($data['data'], $pdf_file_location);

                $mail = new PHPMailer(true);
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->CharSet = 'UTF-8';
                // $mail->Host = "p3plzcpnl505881.prod.phx3.secureserver.net";
                $mail->Host = "mail.riverasgroup.com";
                $mail->SMTPAuth = true;
                $mail->Username = "facturacion@riverasgroup.com";
                $mail->isHTML(true);
                $mail->Password = "iennsI8%RGG_";
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom('facturacion@riverasgroup.com', 'Facturación Electrónica Riveras Group');
                //$mail->addAddress('luisrivera4540@gmail.com');
                //direccion del correo en el DTE
                $correo_recibe = $data['dte']['identificacion']['tipoDte'] == "14" ? $data['dte']['sujetoExcluido']['correo'] : $data['dte']['receptor']['correo'];
                $mail->addAddress($correo_recibe);

                $mail->Subject = 'FACTURA ELECTRONICA';

                $json_formateado = json_encode($data['dte'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $mail->addStringAttachment($json_formateado, $data['dte']['identificacion']['codigoGeneracion'] . ".json", '8bit', 'application/json', 'attachment');
                $mail->addAttachment($pdf_file_location);
                $mail->Body = view('dte/email', [
                    'client_name' => $data['dte']['receptor']['nombre'],
                    'date' => $data['dte']['identificacion']['fecEmi'],
                    'code' => $data['dte']['identificacion']['codigoGeneracion'],
                ]);

                $mail->AltBody = 'Estimad@ ' . $data['dte']['receptor']['nombre'] . ', se anexa su documento tributario electrónico';
                $mail->send();

                if (file_exists($pdf_file_location)) { // Siempre es buena práctica verificar si existe
                    unlink($pdf_file_location);
                }
                return $this->response->setJSON(
                    ['status' => 'success', 'message' => 'Mensaje Enviado']
                );
            }
            return $this->response->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => '404 Not Found']);
        }
        catch (\PHPMailer\PHPMailer\Exception $e) {
            // var_dump($e);
            // return $mail->ErrorInfo;

            return $this->response->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function testMail() {
        try {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            // $mail->Host = "p3plzcpnl505881.prod.phx3.secureserver.net";
            $mail->Host = "mail.riverasgroup.com";
            $mail->SMTPAuth = true;
            $mail->Username = "facturacion@riverasgroup.com";
            $mail->isHTML(true);
            $mail->Password = "iennsI8%RGG_";
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->setFrom('facturacion@riverasgroup.com', 'Facturación Electrónica Riveras Group');
            $mail->addAddress('luisrivera4540@gmail.com');
            $mail->Subject = 'FACTURA ELECTRONICA';
            $mail->Body = 'Estimad@ client->name, se anexa su documento tributario electrónico';
            $mail->AltBody = 'Estimad@ client->name, se anexa su documento tributario electrónico';
            $mail->send();

            return $this->response->setStatusCode(200)
                ->setJSON(['status' => 'success', 'message' => 'Mensaje Enviado']);
        }
        catch (\PHPMailer\PHPMailer\Exception $e) {
            // var_dump($e);
            // return $mail->ErrorInfo;

            return $this->response->setStatusCode(200)
                ->setJSON(['status' => 'error', 'message' => [$e->getMessage(), $mail->ErrorInfo],]);
        }
    }


}


