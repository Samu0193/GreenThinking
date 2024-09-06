<?php

namespace App\Utils;

class ResponseUtil
{
    // Retorna un json con la estructura de respuesta.
    public static function setResponse($code, $status, $message, $data) {

        // Crea un array con la respuesta
        $response = [
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        // Convierte el array a JSON para facilitar el logging
        return json_encode($response);
    }

    public static function logWithContext($message, $context = [])
    {
        // $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        // $caller = $trace[0];
        // $context = array_merge([
        //     'file' => $caller['file'] ?? 'N/A',
        //     'line' => $caller['line'] ?? 'N/A',
        //     'function' => $caller['function'] ?? 'N/A',
        //     'class' => $caller['class'] ?? 'N/A'
        // ], $context);

        // $logMessage = sprintf(
        //     "[%s:%d] %s in %s::%s",
        //     $context['file'],
        //     $context['line'],
        //     $message,
        //     $context['class'],
        //     $context['function']
        // );

        // Obtener el archivo y la línea actual
        $backtrace = debug_backtrace();
        $caller = array_shift($backtrace);
        $file = isset($caller['file']) ? $caller['file'] : 'unknown file';
        $line = isset($caller['line']) ? $caller['line'] : 'unknown line';

        // Formatear el mensaje de log
        $logMessage = sprintf(
            '[%s:%d] %s',
            str_replace(FCPATH, '', $file), // Elimina la parte del camino absoluto
            $line,
            $message
        );

        // if (ENVIRONMENT === 'development') {
        //     // En desarrollo, añade el contexto JSON completo
        //     if (!empty($context)) {
        //         $logMessage .= ' ' . json_encode($context);
        //     }
        // }

        log_message(ENVIRONMENT === 'production' ? 'error' : 'debug', $logMessage);
    }

}
