<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Controllers\BaseController;

class LoginController extends BaseController
{
    protected $loginModel;

    public function __construct()
    {
        $this->loginModel = new LoginModel();
    }


    // ****************************************************************************************************************************
    // *!*   CARGAR LA VISTA DE LOGIN:
    // ****************************************************************************************************************************
    public function index()
    {
        $session = session();
        if ($session->get('is_logged')) {
            // Redirige al Dashboard si el usuario ya está autenticado
            return redirect()->to(site_url('dashboard'));
        }

        // Código para mostrar el formulario de inicio de sesión
        return view('login/index');
        // return redirect()->to(site_url('login'));
    }


    // *************************************************************************************************************************
    //    ENVIO DE CORREO PARA RECUPERACION DE CONTRASEÑA:
    public function sendEmail($email, $subject, $body)
    {
        $emailService = \Config\Services::email();
        $config = [
            'protocol' => 'smtp',
            'SMTPHost' => 'smtp.gmail.com',
            'SMTPUser' => 'davidsanse37@gmail.com',
            'SMTPPass' => 'lgrz xtqg jopt mqiw',
            'SMTPPort' => 587,
            'SMTPCrypto' => 'tls',
            'mailType' => 'html',
            'charset' => 'utf-8',
            'wordWrap' => true
        ];

        $emailService->initialize($config);
        $emailService->setFrom('noreply', 'Green Thinking');
        $emailService->setTo($email);
        $emailService->setSubject($subject);
        $emailService->setMessage($body);

        // Adjuntar la imagen usando CID
        // $emailService->attach(FCPATH . 'assets/images/logo.png', 'inline', 'logo.png', 'image/png');
        // $cid = $emailService->setAttachmentCid(FCPATH . 'assets/images/logo.png');

        $emailService->attach(FCPATH . 'assets/images/logoGT.jpeg', 'inline', 'logoGT.jpeg', 'image/jpeg');
        $cid = $emailService->setAttachmentCid(FCPATH . 'assets/images/logoGT.jpeg');
        $body = str_replace('../img/logoGT.jpeg', "cid:$cid", $body);

        // log_message('debug', $body);
        $emailService->setMessage($body);

        return $emailService->send();
    }


    // ****************************************************************************************************************************
    // *!*   CERRAR SESION:
    // ****************************************************************************************************************************
    public function logout()
    {
        session()->remove(['id_usuario', 'id_persona', 'id_rol', 'usuario', 'estado', 'is_logged']);
        session()->destroy();
        // Redirige a una página pública, como el inicio de sesión
        return redirect()->to(site_url('login'));
    }


    // ****************************************************************************************************************************
    // *!*   CARGAR VISTA PARA RESTABLECER CONTRASEÑA:
    // ****************************************************************************************************************************
    public function resetPassword() {

        $session = session();
        if ($session->get('is_logged')) {
            // Redirige al Dashboard si el usuario ya está autenticado
            return redirect()->to(site_url('dashboard'));
        }

        return view('login/password_reset');
    }


    // ****************************************************************************************************************************
    // *!*   CARGAR VISTA PARA CAMBIAR CONTRASEÑA:
    // ****************************************************************************************************************************
    public function changePassword()
    {
        $hash = $this->request->getGet('hash');
        if (!$hash) {
            return redirect()->to(site_url('resetPassword'));
        }

        try {
            $getHashDetails = $this->loginModel->getHashDetails($hash);
            if (!$getHashDetails) {
                $this->session->setFlashdata('message', 'El link no es válido');
                return view('login/invalid_link');
            }

            $hash_expiry = $getHashDetails['hash_expiry'];
            $currentDate = date('Y-m-d H:i:s');
            if ($currentDate >= $hash_expiry) {
                $this->session->setFlashdata('message', 'El link ha expirado');
                return view('login/invalid_link');
            }

            $data['hash'] = $hash;
            return view('login/password_update', $data);

        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
            // Logear el error y mostrar un mensaje de error genérico
            $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Database error: ' . $dbException->getMessage(), []));
            $this->session->setFlashdata('message', 'Error en la base de datos');
            return view('login/invalid_link');

        } catch (\Exception $e) {
            // Logear el error y mostrar un mensaje de error genérico
            $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Exception: ' . $e->getMessage(), []));
            $this->session->setFlashdata('message', 'Error inesperado');
            return view('login/invalid_link');
        }
    }


    // ****************************************************************************************************************************
    // ****************************************************************************************************************************
    //                        ******                      ****         ******          ****             ****
    //                       ********	                  ****        ********           ****         ****
    //                      ****  ****	                  ****       ****  ****            ****     ****
    //                     ****    ****	                  ****      ****    ****             *********
    //                    **************   	 ****         ****     **************            *********
    //                   ****************	 ****         ****    ****************         ****     ****
    //                  ****          ****    ***************    ****          ****      ****         ****
    //                 ****            ****     ***********     ****            ****   ****             ****
    // ****************************************************************************************************************************
    // ****************************************************************************************************************************


    // ****************************************************************************************************************************
    // *!*   VALIDA INICIO DE SESION (AJAX):
    // ****************************************************************************************************************************
    public function verifica()
    {
        if ($this->request->isAJAX()) {

            try {

                $data = $this->request->getPost();
                if (!$this->validate($this->loginModel->validatorLogin)) {
                    $errors       = $this->validator->getErrors();
                    $firstError   = reset($errors);
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', $errors, []);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                }

                // Acceder a la solicitud usando $this->request
                $nombre   = $this->request->getPost('nombre');
                $password = sha1($this->request->getPost('password'));

                // Verificación del usuario
                $resultado = $this->loginModel->loginView($nombre, $password);
                if (!$resultado || !isset($resultado['id_usuario'])) {
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'Verifica tus credenciales', false);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                    // return $this->response->setJSON(['status' => 1, 'message' => 'Verifica tus credenciales']);
                }

                // Respuesta con la URL a redirigir
                $sessionData = [
                    'id_usuario'          => $resultado['id_usuario'],
                    'id_persona'          => $resultado['id_persona'],
                    'id_rol'              => $resultado['id_rol'],
                    'usuario'             => $resultado['usuario'],
                    'estado'              => $resultado['estado'],
                    'is_logged'           => true,
                    'currently_logged_in' => 1
                ];
                session()->set($sessionData);
                $jsonResponse = $this->responseUtil->setResponse(200, 'success', 'Logueado exitosamente!', site_url('dashboard'));
                return $this->response->setStatusCode(200)->setJSON($jsonResponse);
                // return $this->response->setJSON(['status' => 2, 'url' => site_url('dashboard')]);

            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Database error: ' . $dbException->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);

            } catch (\Exception $e) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error inesperado', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Exception: ' . $e->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
            }

        }

        return redirect()->back();

    }


    // ****************************************************************************************************************************
    // *!*   VALIDAR CORREO (AJAX):
    // ****************************************************************************************************************************
    public function validarEmail()
    {
        if ($this->request->isAJAX()) {

            try {

                $email = $this->request->getPost('email');
                if (!$email) {
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'Email no proporcionado', []);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                }

                $resultado = $this->loginModel->validateEmail($email);
                if ($resultado) {
                    $jsonResponse = $this->responseUtil->setResponse(200, 'success', 'Email encontrado', []);
                    return $this->response->setStatusCode(200)->setJSON($jsonResponse);
                }

                $jsonResponse = $this->responseUtil->setResponse(404, 'not_found', 'Email no existe en la base de datos', []);
                return $this->response->setStatusCode(404)->setJSON($jsonResponse);

            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Database error: ' . $dbException->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);

            } catch (\Exception $e) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error inesperado', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Exception: ' . $e->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
            }

        }

        return redirect()->back();
    }


    // ****************************************************************************************************************************
    // *!*   RECUPERACION DE CONTRASEÑA (AJAX):
    // ****************************************************************************************************************************
    public function sendPasswordResetEmail()
    {
        if ($this->request->isAJAX()) {

            try {
                $email = $this->request->getPost('email');
                if (empty($email)) {
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'Correo electrónico requerido', []);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                    // return $this->response->setJSON(0);
                }

                $validUser = $this->loginModel->validateEmail($email);
                if (!$validUser) {
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'Correo electrónico no existe en la base de datos', []);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                    // return $this->response->setJSON(3);
                }

                $user_id     = $validUser['id_usuario'];
                $user        = $validUser['nombre_apellido'];
                $string      = time() . $user_id . $email;
                $hash_string = hash('sha256', $string);
                $currentDate = date('Y-m-d H:i');
                $hash_expiry = date('Y-m-d H:i', strtotime($currentDate . ' + 1 days'));

                $data = [
                    'hash_key'    => $hash_string,
                    'hash_expiry' => $hash_expiry
                ];

                $resetLink  = site_url('changePassword?hash=' . $hash_string);
                $html       = file_get_contents(FCPATH . 'assets/templates/correo.html');
                $body       = str_replace('@USUARIO', $user, $html);
                $body       = str_replace('@LINK', $resetLink, $body);
                $subject    = 'Restablecer contraseña';
                $sentStatus = $this->phpMailer->sendmail($email, $subject, $body);
                // $sentStatus = $this->phpMailer->sendmail($email, $subject, file_get_contents(FCPATH . 'assets/templates/prueba_correo.html'));
                // $sentStatus = $this->sendEmail($email, $subject, $body);

                if ($sentStatus) {
                    $this->loginModel->updatePasswordHash($data, $email);
                    $jsonResponse = $this->responseUtil->setResponse(200, 'error', 'Correo de recuperación enviado', true);
                    return $this->response->setStatusCode(200)->setJSON($jsonResponse);
                    // return $this->response->setJSON(1);
                }

                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al enviar correo', false);
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
                // return $this->response->setJSON(2);

            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Database error: ' . $dbException->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);

            } catch (\Exception $e) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error inesperado', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Exception: ' . $e->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
            }

        }

        return redirect()->back();
    }


    // ****************************************************************************************************************************
    // *!*   CAMBIAR CONTRASEÑA (AJAX):
    // ****************************************************************************************************************************
    public function updatePassword()
    {
        if ($this->request->isAJAX()) {

            try {

                $hash = $this->request->getPost('hash');
                if ($hash) {

                    $getHashDetails = $this->loginModel->getHashDetails($hash);
                    if ($getHashDetails) {

                        $hash_expiry = $getHashDetails['hash_expiry'];
                        $currentDate = date('Y-m-d H:i');
                        if ($currentDate < $hash_expiry) {

                            $data = $this->request->getPost();
                            if (!$this->validate($this->loginModel->validatorPassword)) {
                                $errors       = $this->validator->getErrors();
                                $firstError   = reset($errors);
                                $jsonResponse = $this->responseUtil->setResponse(400, 'error', $errors, []);
                                return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                            }

                            $dataUpdate = [
                                'password' => sha1($this->request->getPost('password')),
                                'hash_key' => null,
                                'hash_expiry' => null
                            ];

                            $resultado = $this->loginModel->updateNewPassword($dataUpdate, $hash);
                            if ($resultado) {
                                $jsonResponse = $this->responseUtil->setResponse(200, 'success', 'Contraseña actualizada', true);
                                return $this->response->setStatusCode(200)->setJSON($jsonResponse);
                            }

                            $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'No se pudo actualizar la contraseña', false);
                            return $this->response->setStatusCode(500)->setJSON($jsonResponse);

                        } else {

                            // $this->session->setFlashdata('message', 'El Link Ha Expirado');
                            // return view('login/invalid_link');
                            $jsonResponse = $this->responseUtil->setResponse(410, 'gone', 'El link ha expirado', []);
                            return $this->response->setStatusCode(410)->setJSON($jsonResponse);
                        }

                    } else {
                        $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'El link no es válido', []);
                        return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                        // $this->session->setFlashdata('message', 'El Link no es v&aacute;lido');
                        // return view('login/invalid_link');
                    }

                }

                // return redirect()->to(site_url('login/forgotPassword'));
                $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'Token no proporcionado', []);
                return $this->response->setStatusCode(400)->setJSON($jsonResponse);

            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
                // Captura específicamente errores de la base de datos
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Database error: ' . $dbException->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);

            } catch (\Exception $e) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error inesperado', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Exception: ' . $e->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
            }

        }

        return redirect()->back();

    }
    
}
