<?php

$site = "https://staging.strange-dev.com/";

echo "================================\n\r";
echo "\n\r";
echo "     PHP CURL REQUESTER v0.1\r\n";
echo "\n\r";
echo "================================\n\r";
echo "\n\r";

if(empty($argv[1])) {
    echo "ERROR! Counter is empty\n\r";
    echo "Example: php {$argv[0]} 13\n\r";
    echo "\n\r";
} else {
    $counter = $argv[1];

    if(!is_numeric($counter)) { echo "ERROR! The argument must be a number\n\r"; exit();}

    $mh = curl_multi_init();

    for($i = 0; $i < $counter; $i++) {

        $url = $site;
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
        curl_setopt(${'ch_'.$i}, CURLOPT_URL,$url);

        curl_multi_add_handle($mh, ${'ch_'.$i});
    }

    do {
        $status = curl_multi_exec($mh, $active);
        if ($active) {
            curl_multi_select($mh);
        }
    } while ($active && $status == CURLM_OK);


    //echo "Your argument is: ".$counter."\n\r";
    echo "Enjoy!\n\r";
    echo "\n\r";
}



?>