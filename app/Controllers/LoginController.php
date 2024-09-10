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

    // *************************************************************************************************************************
    //    CERRAR SESION:
    public function logout()
    {
        session()->remove(['id_usuario', 'id_persona', 'id_rol', 'usuario', 'estado', 'is_logged']);
        session()->destroy();
        // Redirige a una página pública, como el inicio de sesión
        return redirect()->to(site_url('login'));
    }

    // *************************************************************************************************************************
    //    RECUPERACION DE CONTRASEÑA:
    public function forgotPassword()
    {
        $session = session();
        if ($session->get('is_logged')) {
            // Redirige al Dashboard si el usuario ya está autenticado
            return redirect()->to(site_url('dashboard'));
        }

        if ($this->request->getMethod() == 'POST') {
            $email = $this->request->getPost('email');
            if (empty($email)) {
                return $this->response->setJSON(0);
            }

            $validateEmail = $this->loginModel->validateEmail($email);
            if ($validateEmail) {
                $user_id = $validateEmail['id_usuario'];
                $string = time() . $user_id . $email;
                $hash_string = hash('sha256', $string);
                $currentDate = date('Y-m-d H:i');
                $hash_expiry = date('Y-m-d H:i', strtotime($currentDate . ' + 1 days'));

                $data = [
                    'hash_key' => $hash_string,
                    'hash_expiry' => $hash_expiry
                ];

                $resetLink = site_url('login/password?hash=' . $hash_string);
                $message = '<div style="font-family: sans-serif; color:#000; text-align: center; padding: 1rem; width: 100%; height: 40vh; background-color: #EAEFFC;">
                                <h2>GREEN THINKING</h2>
                            <div style=" margin: 0 auto; text-align: center; padding: 1rem; width: 50%; background-color: #fff;
                            border-radius: 5px;">                
                                <h3>Actualizar Clave de Acceso</h3>
                                <p>Pulsa a Restablecer Password para Cambiar tu Clave de Acceso</p><br>
                                <a href="' . $resetLink . '" style="text-decoration: none; width: 150px; margin-bottom: 1rem; padding: 1rem; color: #fff; font-size: 16px; background-color: #325FEB; border: none; cursor: pointer; border-radius: 5px;"><b>Reestablecer Password</b></a>
                            </div>
                            </div>';

                $subject = "Green Thinking - Link para Reestablecer Password";
                $sentStatus = $this->sendEmail($email, $subject, $message);

                if ($sentStatus) {
                    $this->loginModel->updatePasswordHash($data, $email);
                    return $this->response->setJSON(1);
                } else {
                    return $this->response->setJSON(2);
                }
            } else {
                return $this->response->setJSON(3);
            }
        }

        return view('Login/forgot_password');
    }

    // *************************************************************************************************************************
    //    ENVIO DE CORREO PARA RECUPERACION DE CONTRASEÑA:
    public function sendEmail($email, $subject, $message)
    {
        $emailService = \Config\Services::email();
        $config = [
            // 'protocol' => 'smtp',
            // 'SMTPHost' => 'ssl://smtp.googlemail.com',
            // 'SMTPPort' => 465,
            // 'SMTPUser' => 'testgreenthinking@gmail.com',
            // 'SMTPPass' => 'greenthinking@U5AM',
            // 'mailType' => 'html',
            // 'charset' => 'iso-8859-1',
            // 'wordWrap' => true

            // 'protocol' => 'smtp',
            // 'SMTPHost' => 'smtp.gmail.com',
            // 'SMTPUser' => 'testgreenthinking@gmail.com',
            // 'SMTPPass' => 'greenthinking@U5AM',
            // 'SMTPPort' => 587,
            // 'SMTPCrypto' => 'tls',
            // 'mailType' => 'html',
            // 'charset' => 'iso-8859-1',
            // 'wordWrap' => true

            'protocol' => 'smtp',
            'SMTPHost' => 'smtp.gmail.com',
            'SMTPUser' => 'davidsanse37@gmail.com',
            'SMTPPass' => 'nqjn rscw rpff jnwm',
            'SMTPPort' => 587,
            'SMTPCrypto' => 'tls',
            'mailType' => 'html',
            'charset' => 'iso-8859-1',
            'wordWrap' => true
        ];

        $emailService->initialize($config);
        $emailService->setFrom('noreply', 'Green Thinking');
        $emailService->setTo($email);
        $emailService->setSubject($subject);
        $emailService->setMessage($message);

        return $emailService->send();
    }

    // *************************************************************************************************************************
    //    CAMBIAR CONTRASEÑA:
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
