<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LoginModel;

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
        return view('Login/index');
        // return redirect()->to(site_url('login'));
    }

    // ****************************************************************************************************************************
    // *!*   VALIDA INICIO DE SESION:
    // ****************************************************************************************************************************
    public function verifica()
    {
        if ($this->request->isAJAX()) {

            try {
                // Acceder a la solicitud usando $this->request
                $nombre   = $this->request->getPost('nombre');
                $password = sha1($this->request->getPost('password'));

                // Validación básica
                if (empty($nombre) || empty($password)) {
                    $jsonResponse = $this->responseUtil->setResponse(400, "error", 'Usuario y contraseña son requeridos...', false);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                    // return $this->response->setJSON(['status' => 0, 'message' => 'Usuario y contraseña son requeridos...']);
                }

                // Verificación del usuario
                $res = $this->loginModel->loginView($nombre, $password);
                if (!$res || !isset($res['id_usuario'])) {
                    $jsonResponse = $this->responseUtil->setResponse(400, "error", 'Verifica tus credenciales...', false);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                    // return $this->response->setJSON(['status' => 1, 'message' => 'Verifica tus credenciales...']);
                }

                // Respuesta con la URL a redirigir
                $sessionData = [
                    'id_usuario'          => $res['id_usuario'],
                    'id_persona'          => $res['id_persona'],
                    'id_rol'              => $res['id_rol'],
                    'usuario'             => $res['usuario'],
                    'estado'              => $res['estado'],
                    'is_logged'           => true,
                    'currently_logged_in' => 1
                ];
                session()->set($sessionData);
                $jsonResponse = $this->responseUtil->setResponse(200, "success", 'Logueado exitosamente!', site_url('dashboard'));
                return $this->response->setStatusCode(200)->setJSON($jsonResponse);
                // return $this->response->setJSON(['status' => 2, 'url' => site_url('dashboard')]);

            } catch (\Exception $e) {
                $jsonResponse = $this->responseUtil->setResponse(500, "server_error", 'Error inesperado.', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, "server_error", 'Exception: ' . $e->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
            }

        }

        return redirect()->back();

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
    // *!*   RECUPERACION DE CONTRASEÑA:
    // ****************************************************************************************************************************
    public function forgotPassword()
    {
        $session = session();
        if ($session->get('is_logged')) {
            // Redirige al Dashboard si el usuario ya está autenticado
            return redirect()->to(site_url('dashboard'));
        }

        if ($this->request->getMethod() == 'POST') {

            if ($this->request->isAJAX()) {

                try {
                    $email = $this->request->getPost('email');
                    if (empty($email)) {
                        $jsonResponse = $this->responseUtil->setResponse(400, "error", 'Correo electrónico requerido...', []);
                        return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                        // return $this->response->setJSON(0);
                    }

                    $validUser = $this->loginModel->validateEmail($email);
                    if (!$validUser) {
                        $jsonResponse = $this->responseUtil->setResponse(400, "error", 'Correo electrónico no existe en la base de datos...', []);
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

                    $resetLink = site_url('login/password?hash=' . $hash_string);
                    // $body =
                    //     '<div style="font-family: sans-serif; color:#000; text-align: center; padding: 1rem; width: 100%; height: 40vh; background-color: #EAEFFC;">
                    //             <h2>GREEN THINKING</h2>
                    //         <div style=" margin: 0 auto; text-align: center; padding: 1rem; width: 50%; background-color: #fff;
                    //         border-radius: 5px;">                
                    //             <h3>Actualizar Clave de Acceso</h3>
                    //             <p>Pulsa a Restablecer Password para Cambiar tu Clave de Acceso</p><br>
                    //             <a href="' . $resetLink . '" style="text-decoration: none; width: 150px; margin-bottom: 1rem; padding: 1rem; color: #fff; font-size: 16px; background-color: #325FEB; border: none; cursor: pointer; border-radius: 5px;"><b>Reestablecer Password</b></a>
                    //         </div>
                    //     </div>';
                    // $body =
                    //     '<div style="font-family: sans-serif; color:#000; text-align: center; padding: 1rem; width: 100%; height: auto; background-color: #EAEFFC;">
                    //         <h1>Green Thinking</h1>
                    //         <div style="margin: 0 auto; text-align: left; padding: 1rem; width: 600px; background-color: #fff; border-radius: 12px;">
                    //             <div style="padding: 20px; border: 1px solid #DBDBDB; border-radius: 12px; font-family: Sans-serif;">
                    //                 <h2>Restablecer contraseña</h2>
                    //                 <p style="margin-bottom: 25px;">
                    //                     Estimado/a&nbsp;<b>' . $user . '</b>:
                    //                 </p>
                    //                 <p style="margin-bottom: 25px;">
                    //                     Se solicitó un restablecimiento de contraseña para tu cuenta, haz clic en el botón
                    //                     que aparece a continuación para cambiar tu contraseña.
                    //                 </p>
                    //                 <a style="padding: 12px; border-radius: 12px; background-color: #3ca230; color: #fff; text-decoration: none;" href="' . $resetLink . '"
                    //                     target="_blank">
                    //                     Cambiar contraseña
                    //                 </a>
                    //                 <p style="margin-top: 25px;">Gracias.</p>
                    //             </div>
                    //         </div>
                    //     </div>';

                    // // Leer el contenido del archivo index.html
                    $html    = file_get_contents(FCPATH . 'assets/templates/correo.html');
                    $body    = str_replace('@USUARIO', $user, $html);
                    $body    = str_replace('@LINK', $resetLink, $body);
                    $subject = "Restablecer contraseña.";
                    // $sentStatus = $this->sendEmail($email, $subject, $body);
                    $sentStatus = $this->phpMailer->sendmail($email, $subject, $body);

                    if ($sentStatus) {
                        $this->loginModel->updatePasswordHash($data, $email);
                        $jsonResponse = $this->responseUtil->setResponse(200, "error", 'Enviando correo de recuperación... Por favor, espere...', true);
                        return $this->response->setStatusCode(200)->setJSON($jsonResponse);
                        // return $this->response->setJSON(1);
                    }

                    $jsonResponse = $this->responseUtil->setResponse(500, "server_error", 'Error al enviar correo.', false);
                    return $this->response->setStatusCode(500)->setJSON($jsonResponse);
                    // return $this->response->setJSON(2);

                } catch (\Exception $e) {
                    $jsonResponse = $this->responseUtil->setResponse(500, "server_error", 'Error inesperado.', []);
                    $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, "server_error", 'Exception: ' . $e->getMessage(), []));
                    return $this->response->setStatusCode(500)->setJSON($jsonResponse);
                }

            }

            return redirect()->back();
        }

        return view('Login/forgot_password'); // METHOD = GET
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
    // *!*   CAMBIAR CONTRASEÑA:
    // ****************************************************************************************************************************
    public function password()
    {
        $hash = $this->request->getGet('hash');
        if ($hash) {
            $getHashDetails = $this->loginModel->getHashDetails($hash);

            if ($getHashDetails) {
                $hash_expiry = $getHashDetails['hash_expiry'];
                $currentDate = date('Y-m-d H:i');

                if ($currentDate < $hash_expiry) {
                    if ($this->request->getMethod() == 'POST') {
                        $newPassword = $this->request->getPost('password');
                        $cnewPassword = $this->request->getPost('cpassword');

                        if (empty($newPassword) || empty($cnewPassword)) {
                            return $this->response->setJSON(0);
                        } elseif ($newPassword != $cnewPassword) {
                            return $this->response->setJSON(1);
                        } else {
                            $newPassword = sha1($newPassword);

                            $data = [
                                'password' => $newPassword,
                                'hash_key' => null,
                                'hash_expiry' => null
                            ];

                            $this->loginModel->updateNewPassword($data, $hash);
                            return $this->response->setJSON(2);
                        }
                    } else {
                        $data['hash'] = $hash;
                        return view('Login/reset_password', $data);
                    }
                } else {
                    $this->session->setFlashdata('message', 'El Link Ha Expirado');
                    // return redirect()->to(site_url('login/forgotPassword'));
                    return view('Login/invalid_link');
                }
            } else {
                $this->session->setFlashdata('message', 'El Link no es v&aacute;lido');
                return view('Login/invalid_link');
            }
        }

        return redirect()->to(site_url('login/forgotPassword'));
    }
}
