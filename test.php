<?php

use DressPress\ChineseNumber\ChineseNumberHelper;

error_reporting(E_ALL);

require __DIR__ . '/src/ChineseNumberHelper.php';


echo '<h2><code>ChineseNumberHelper::toChinese($number)</code></h2>';
echo '<pre><code>';
$to_chinse_results = [];
for ($length = 1; $length < 10; $length++) {
    $number = rand(pow(10, $length), pow(10, $length + 1) - 1);
    $chinese = ChineseNumberHelper::toChinese($number);

    $to_chinse_results[$number] = $chinese;

    printf("%s => %s\n", $number,  $chinese);
}
echo '</code></pre>';


echo '<h2><code>ChineseNumberHelper::toNumber($chinese)</code></h2>';
echo '<pre><code>';
foreach ($to_chinse_results as $number => $chinese) {
    $number = ChineseNumberHelper::toNumber($chinese);
    printf("%s => %s\n", $chinese,  $number);
}
echo '</code></pre>';
