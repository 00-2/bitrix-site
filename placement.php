<?php
// placement.php — большой iframe, если поля есть; иначе — таблица всех полей
header('Content-Type: text/html; charset=UTF-8');

$crestPath = __DIR__ . '/crest.php';
if (!is_file($crestPath)) { echo 'Нет конфигурации приложения (crest.php).'; exit; }
require_once $crestPath;

// 1) Достаём ID сделки из PLACEMENT_OPTIONS (учтём HTML-экранирование)
$poRaw = $_REQUEST['PLACEMENT_OPTIONS'] ?? '';
$po = [];
if ($poRaw !== '') {
    $po = json_decode(html_entity_decode((string)$poRaw, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'), true) ?: [];
}
$dealId = $po['ID'] ?? ($_REQUEST['ID'] ?? $_REQUEST['DEAL_ID'] ?? null);
if (!$dealId) { echo 'Нет ID сделки. Откройте приложение как вкладку сделки (PLACEMENT).'; exit; }

// 2) Тянем сделку
$deal = CRest::call('crm.deal.get', ['ID' => $dealId]);
if (!is_array($deal) || !empty($deal['error'])) {
    $code = $deal['error'] ?? 'unknown_error';
    $desc = $deal['error_description'] ?? 'Нет описания';
    echo 'Ошибка crm.deal.get: ' . htmlspecialchars("$code $desc", ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    exit;
}
$res = $deal['result'] ?? [];

// 3) Достаём нужные поля (при множественных берём первый)
$orderId  = $res['UF_CRM_1760209329628'] ?? null;
$execVkId = $res['UF_CRM_1760209703822'] ?? null;
if (is_array($orderId))  { $orderId  = reset($orderId) ?: null; }
if (is_array($execVkId)) { $execVkId = reset($execVkId) ?: null; }

$haveFields = ($orderId !== '' && $orderId !== null && $execVkId !== '' && $execVkId !== null);

// 4) Утилита для безопасного вывода
function show_val($v) {
    if (is_array($v) || is_object($v)) $v = json_encode($v, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// 5) Если поля найдены — готовим URL
$url = null;
if ($haveFields) {
    $url = 'https://startproj.ru/order_card/formal?' . http_build_query([
        'order_id'   => $orderId,
        'exec_vk_id' => $execVkId,
    ]);
}

// Параметры отображения
$iframeHeight = $haveFields ? '180vh' : '80vh';  // большое поле, как просил
$bodyPadding  = $haveFields ? '0' : '16px';      // без отступов, если только виджет
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Deal #<?= htmlspecialchars((string)$dealId) ?></title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    html, body { margin:0; padding: <?= $bodyPadding ?>; height:100%; background:#f7f7f7;
                 font-family: system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif; }
    .note{margin:8px 0;color:#666;font-size:12px}
    .iframe-wrap{margin-top:12px}
    iframe{border:0;width:100%;height: <?= $iframeHeight ?>; background:#fff}
    table{border-collapse:collapse;width:100%;background:#fff}
    th,td{border:1px solid #e6e6e6;padding:6px 8px;text-align:left;font-size:13px;vertical-align:top}
    th{background:#fafafa}
    h1{font-size:16px;margin:0 0 12px}
  </style>
</head>
<body>
<?php if ($haveFields): ?>
  <!-- Только виджет, без лишнего текста -->
  <iframe src="<?= htmlspecialchars($url, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"></iframe>
<?php else: ?>
  <h1>Сделка #<?= htmlspecialchars((string)$dealId) ?></h1>
  <div class="note">
    Нужные поля не заполнены (или имеют другие коды). Ниже — все поля текущей сделки:
  </div>
  <table>
    <thead><tr><th>Поле</th><th>Значение</th></tr></thead>
    <tbody>
      <?php foreach ($res as $k => $v): ?>
        <tr>
          <td><?= htmlspecialchars($k, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
          <td><?= show_val($v) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
</body>
</html>

