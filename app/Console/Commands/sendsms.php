<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SimpleXMLElement;

class sendsms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendsms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $curl = curl_init();
        $url = "http://192.168.8.1/api/webserver/SesTokInfo";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $content = curl_exec($curl);
        $xml = new SimpleXMLElement($content);

        $sess_id = $xml->SesInfo;
        $tokInfo = $xml->TokInfo;

        curl_close($curl);
        $curl2 = curl_init('http://192.168.8.1/api/sms/send-sms');

        $headers = array(
            "X-Requested-With: XMLHttpRequest",
            'Cookie:' . $sess_id,
            '__RequestVerificationToken:' . $tokInfo,
            '"Content-Type:text/xml"',
        );

        $data = "<?xml version='1.0' encoding='UTF-8'?><request><Index>-1</Index><Phones><Phone>+6281295369449</Phone></Phones><Sca></Sca><Content>CANDELAS!REQUEST='STATE'</Content><Length>5</Length><Reserved>1</Reserved><Date>-1</Date></request>";

        curl_setopt($curl2, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl2, CURLOPT_POST, true);

        curl_setopt($curl2, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);

        $content = curl_exec($curl2);

        curl_close($curl2);

        return 0;
    }
}
