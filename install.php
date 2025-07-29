<?php
require_once (__DIR__.'/crest.php');

$install_result = CRest::installApp();

// embedded for placement "placement.php"
$handlerBackUrl = ($_SERVER['HTTPS'] === 'on' || $_SERVER['SERVER_PORT'] === '443' ? 'https' : 'http') . '://'
    . $_SERVER['SERVER_NAME']
    . (in_array($_SERVER['SERVER_PORT'], ['80', '443'], true) ? '' : ':' . $_SERVER['SERVER_PORT'])
    . str_replace($_SERVER['DOCUMENT_ROOT'], '',__DIR__)
    . '/placement.php';

$result = CRest::call(
    'placement.bind',
    [
        'PLACEMENT' => 'CRM_DEAL_DETAIL_TAB',
        'HANDLER' => $handlerBackUrl,
        'TITLE' => 'My App'
    ]
);

$handlerBackUrlTest = ($_SERVER['HTTPS'] === 'on' || $_SERVER['SERVER_PORT'] === '443' ? 'https' : 'http') . '://'
    . $_SERVER['SERVER_NAME']
    . (in_array($_SERVER['SERVER_PORT'], ['80', '443'], true) ? '' : ':' . $_SERVER['SERVER_PORT'])
    . str_replace($_SERVER['DOCUMENT_ROOT'], '',__DIR__)
    . '/TestApp.php';
$TestApp = CRest::call(
    'placement.bind',
    [
        'PLACEMENT' => 'CRM_DEAL_DETAIL_TAB',
        'HANDLER' => $handlerBackUrlTest,
        'TITLE' => 'Test app'
    ]
);

CRest::setLog(['deal_tab' => $result], 'installation');
CRest::setLog(['deal_tab' => $TestApp], 'installation');
if($install_result['rest_only'] === false): ?>
<head>
    <script src="//api.bitrix24.com/api/v1/"></script>
    <?php if($install_result['install'] == true): ?>
    <script>
        BX24.init(function(){
            BX24.installFinish();
        });
    </script>
    <?php endif; ?>
</head>
<body>
    <?php if($install_result['install'] == true): ?>
        installation has been finished
    <?php else: ?>
        installation error
    <?php endif; ?>
</body>
<?php endif; ?>

