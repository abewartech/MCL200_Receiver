<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
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

        foreach (simplexml_load_string($content)->Messages->Message as $item) {
            if ($item->Phone == '+6281295369449') {
                if(strlen($item->Content) > 2){
                    $response = Http::post('https://dev.cakrawala.id/api/lantern', [
                        'device_imei' => (String) $item->Phone,
                        'latitude' => $this->convertDMSToDecimal(str_replace('*',' ',substr(explode(' ',$item->Content)[3],4))),
                        'longitude' => $this->convertDMSToDecimal(str_replace('*',' ',substr(explode(' ',$item->Content)[4],5))),
                        'bat_lvl' => substr(explode(' ',$item->Content)[8], 0, -1),
                        'created_at' => (String) $item->Date,
                    ]);
                    $this->line($response->body());
                }
            }
        }

        return 0;
    }

    public function convertDMSToDecimal($latlng) {
        $valid = false;
        $decimal_degrees = 0;
        $degrees = 0; $minutes = 0; $seconds = 0; $direction = 1;
    
        $num_periods = substr_count($latlng, '.');
        if ($num_periods > 1) {
            $temp = preg_replace('/\./', ' ', $latlng, $num_periods - 1); 
            $temp = trim(preg_replace('/[a-zA-Z]/','',$temp)); 
            $chunk_count = count(explode(" ",$temp));
            if ($chunk_count > 2) {
                $latlng = preg_replace('/\./', ' ', $latlng, $num_periods - 1); 
            } else {
                $latlng = str_replace("."," ",$latlng); 
            }
        }
        
        $latlng = trim($latlng);
        $latlng = str_replace("º"," ",$latlng);
        $latlng = str_replace("°"," ",$latlng);
        $latlng = str_replace("'"," ",$latlng);
        $latlng = str_replace("\""," ",$latlng);
        $latlng = str_replace("  "," ",$latlng);
        $latlng = substr($latlng,0,1) . str_replace('-', ' ', substr($latlng,1)); 
    
        if ($latlng != "") {
            if (preg_match("/^([nsewoNSEWO]?)\s*(\d{1,3})\s+(\d{1,3})\s*(\d*\.?\d*)$/",$latlng,$matches)) {
                $valid = true;
                $degrees = intval($matches[2]);
                $minutes = intval($matches[3]);
                $seconds = floatval($matches[4]);
                if (strtoupper($matches[1]) == "S" || strtoupper($matches[1]) == "W")
                    $direction = -1;
            }
            elseif (preg_match("/^(-?\d{1,3})\s+(\d{1,3})\s*(\d*(?:\.\d*)?)\s*([nsewoNSEWO]?)$/",$latlng,$matches)) {
                $valid = true;
                $degrees = intval($matches[1]);
                $minutes = intval($matches[2]);
                $seconds = floatval($matches[3]);
                if (strtoupper($matches[4]) == "S" || strtoupper($matches[4]) == "W" || $degrees < 0) {
                    $direction = -1;
                    $degrees = abs($degrees);
                }
            }
            if ($valid) {
                $decimal_degrees = ($degrees + ($minutes / 60) + ($seconds / 3600)) * $direction;
            } else {
                if (preg_match("/^([nsewNSEW]?)\s*(\d+(?:\.\d+)?)$/",$latlng,$matches)) {
                    $valid = true;
                    if (strtoupper($matches[1]) == "S" || strtoupper($matches[1]) == "W")
                        $direction = -1;
                    $decimal_degrees = $matches[2] * $direction;
                }
                elseif (preg_match("/^(-?\d+(?:\.\d+)?)\s*([nsewNSEW]?)$/",$latlng,$matches)) {
                    $valid = true;
                    if (strtoupper($matches[2]) == "S" || strtoupper($matches[2]) == "W" || $degrees < 0) {
                        $direction = -1;
                        $degrees = abs($degrees);
                    }
                    $decimal_degrees = $matches[1] * $direction;
                }
            }
        }
        if ($valid) {
            return $decimal_degrees;
        } else {
            return false;
        }
    }
}
