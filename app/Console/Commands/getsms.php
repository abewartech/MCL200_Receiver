<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDO;
use SimpleXMLElement;

class getsms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getsms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get SMS';

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
        $xml = new simpleXMLElement($content);

        $sess_id = $xml->SesInfo;
        $tokInfo = $xml->TokInfo;

        curl_close($curl);
        $curl2 = curl_init('http://192.168.8.1/api/sms/sms-list');

        $headers = array(
            "X-Requested-With: XMLHttpRequest",
            'Cookie:' . $sess_id,
            '__RequestVerificationToken:' . $tokInfo,
            '"Content-Type:text/xml"',
        );

        $data = "<request><PageIndex>1</PageIndex><ReadCount>10</ReadCount><BoxType>1</BoxType><SortType>0</SortType><Ascending>0</Ascending><UnreadPreferred>1</UnreadPreferred></request>";


        curl_setopt($curl2, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl2, CURLOPT_POST, true);

        curl_setopt($curl2, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);

        $content = curl_exec($curl2);

        curl_close($curl2);

        foreach(simplexml_load_string($content)->Messages->Message as $item){
            if($item->Phone == '+6281295369449'){
                dd($item);
            }
        }

        return 0;
    }
}
