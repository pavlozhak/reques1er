<?php

echo "================================\n\r";
echo "\n\r";
echo "     PHP CURL REQUESTER v0.1\r\n";
echo "\n\r";
echo "================================\n\r";
echo "\n\r";

$url = getopt(null, ["url:"]);
$counter = getopt(null, ["counter:"]);

var_dump($counter);

function sendRequests($url, $counter) {
    $mh = curl_multi_init();

    for($i = 0; $i < $counter['counter']; $i++) {
        $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

        ${'ch_'.$i} = curl_init();
        curl_setopt(${'ch_'.$i}, CURLOPT_HTTPHEADER, array(
            'Connection: Keep-Alive',
            'Keep-Alive: 300'
        ));
        curl_setopt(${'ch_'.$i}, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt(${'ch_'.$i}, CURLOPT_VERBOSE, true);
        curl_setopt(${'ch_'.$i}, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(${'ch_'.$i}, CURLOPT_USERAGENT, $agent);
        curl_setopt(${'ch_'.$i}, CURLOPT_URL,$url['url']);

        curl_multi_add_handle($mh, ${'ch_'.$i});
    }

    do {
        $status = curl_multi_exec($mh, $active);
        if ($active) {
            curl_multi_select($mh);
        }
    } while ($active && $status == CURLM_OK);

    sendRequests($url, $counter);
}

sendRequests($url, $counter);
?>