<?php
// === Настройки ===
$logFile    = __DIR__ . '/hook.log';
$endpoint1C = 'https://aclient.1c-hosting.com/1R91657/1R91657_BUH3_jhqzm7v0vz/hs/bitrix/UpdateOrder/';
$user1C     = 'Администратор';
$pass1C     = ''; // пустой пароль

// === Приём запроса ===
$methodIn = $_SERVER['REQUEST_METHOD'];                // что прислал Bitrix
$raw      = file_get_contents('php://input');          // сырое тело
$now      = date('Y-m-d H:i:s');

// Попробуем распарсить вход как JSON; если нет — берём PHP-парсинг формы/квери
$parsed = json_decode($raw, true);
if ($parsed === null) {
    // Если пришло form-url-encoded — PHP уже положил в $_POST/$_REQUEST
    // (на случай пустого тела возьмём $_REQUEST, там и query string)
    $parsed = $_POST ?: $_REQUEST;
}
// Всегда готовим JSON для 1С
$jsonFor1C = json_encode($parsed, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// === Лог входящего и того, что ушло в 1С ===
file_put_contents(
    $logFile,
    $now . PHP_EOL .
    "METHOD_IN: {$methodIn}" . PHP_EOL .
    "RAW_IN:" . PHP_EOL . ($raw === '' ? '(empty)' : $raw) . PHP_EOL .
    "PARSED:" . PHP_EOL . print_r($parsed, true) .
    "JSON_TO_1C:" . PHP_EOL . $jsonFor1C . PHP_EOL .
    str_repeat('-', 40) . PHP_EOL,
    FILE_APPEND
);

// === Заголовки к 1С (всегда JSON) ===
$headers1C = [
    'Authorization: Basic ' . base64_encode($user1C . ':' . $pass1C),
    'Content-Type: application/json; charset=utf-8',
    'Expect:', // отключить 100-continue
];

// === Пересылка в 1С (форсим POST + JSON) ===
$ch = curl_init($endpoint1C);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST  => 'POST',
    CURLOPT_POSTFIELDS     => $jsonFor1C,
    CURLOPT_HTTPHEADER     => $headers1C,
    CURLOPT_TIMEOUT        => 20,
]);
$resp1C  = curl_exec($ch);
$errno1C = curl_errno($ch);
$error1C = curl_error($ch);
$code1C  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// === Ответ клиенту ===
if ($errno1C) {
    http_response_code(502);
    header('Content-Type: text/plain; charset=utf-8');
    $msg = "1C cURL error: {$error1C}";
    echo $msg;
    file_put_contents($logFile, "{$now}\n1C ERROR -> {$msg}\n" . str_repeat('=',40) . "\n", FILE_APPEND);
    exit;
}

http_response_code($code1C);
if ($code1C >= 400) {
    header('Content-Type: text/plain; charset=utf-8');
    echo ($resp1C !== '' ? $resp1C : "HTTP {$code1C}");
} else {
    echo $resp1C;
}
