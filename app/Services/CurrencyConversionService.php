<?php

namespace App\Services;

class CurrencyConversionService
{
    private $conversionApiUrl;
    private $accessKey;

    public function __construct()
    {
        $this->conversionApiUrl = env("EXCHANGE_API_URL");
        $this->accessKey = env("EXCHANGE_API_ACCESS_KEY");
    }

    public function convertToDefaultCurrency($amount, $currency, $toCurrency){
        if($currency == $toCurrency){
            return ['amount' => $amount, 'rate' => 1];
        }
        $rates = $this->callConversionApi();
        return ['amount' => $amount / $rates[$currency], 'rate' => $rates[$currency]];
    }

    private function callConversionApi(){
        if(env('TEST_RUN')){
            $response = $this->testConversion();
        }
        else{
            $headers = [];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->conversionApiUrl . $this->accessKey);
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            curl_close($ch);
        }
        if($response){
            $res = json_decode($response, true);
            return $res['rates'];
        }
    }

    private function testConversion(){
        return $response = '{
          "success": true,
          "timestamp": 1635867184,
          "base": "EUR",
          "date": "2021-11-02",
          "rates": {
            "AED": 4.258543,
            "AFN": 104.565828,
            "ALL": 122.953271,
            "AMD": 554.367844,
            "ANG": 2.089797,
            "AOA": 692.153705,
            "ARS": 115.767267,
            "AUD": 1.556339,
            "AWG": 2.087476,
            "AZN": 1.970821,
            "BAM": 1.954518,
            "BBD": 2.32324,
            "BDT": 99.354502,
            "BGN": 1.955318,
            "BHD": 0.437118,
            "BIF": 2316.454596,
            "BMD": 1.159387,
            "BND": 1.562751,
            "BOB": 7.994933,
            "BRL": 6.584966,
            "BSD": 1.159522,
            "BTC": 0.000018213449,
            "BTN": 86.628645,
            "BWP": 13.297573,
            "BYN": 2.848488,
            "BYR": 22723.979015,
            "BZD": 2.324839,
            "CAD": 1.436764,
            "CDF": 2332.686048,
            "CHF": 1.059877,
            "CLF": 0.034273,
            "CLP": 945.700247,
            "CNY": 7.420766,
            "COP": 4383.62946,
            "CRC": 739.93327,
            "CUC": 1.159387,
            "CUP": 30.723747,
            "CVE": 110.72314,
            "CZK": 25.581057,
            "DJF": 206.451406,
            "DKK": 7.439842,
            "DOP": 65.447258,
            "DZD": 159.009699,
            "EGP": 18.225788,
            "ERN": 17.392237,
            "ETB": 54.780859,
            "EUR": 1,
            "FJD": 2.417346,
            "FKP": 0.850088,
            "GBP": 0.851007,
            "GEL": 3.669473,
            "GGP": 0.850088,
            "GHS": 7.07239,
            "GIP": 0.850088,
            "GMD": 60.28872,
            "GNF": 11130.112386,
            "GTQ": 8.971277,
            "GYD": 242.737047,
            "HKD": 9.022237,
            "HNL": 28.010572,
            "HRK": 7.523148,
            "HTG": 113.83558,
            "HUF": 359.444341,
            "IDR": 16560.157677,
            "ILS": 3.63855,
            "IMP": 0.850088,
            "INR": 86.526298,
            "IQD": 1692.704559,
            "IRR": 48972.493301,
            "ISK": 150.209857,
            "JEP": 0.850088,
            "JMD": 179.263657,
            "JOD": 0.822031,
            "JPY": 131.88893,
            "KES": 128.982083,
            "KGS": 98.314779,
            "KHR": 4716.385148,
            "KMF": 492.304561,
            "KPW": 1043.447669,
            "KRW": 1364.30776,
            "KWD": 0.349891,
            "KYD": 0.966251,
            "KZT": 496.566647,
            "LAK": 11959.073642,
            "LBP": 1776.180163,
            "LKR": 234.225361,
            "LRD": 172.603726,
            "LSL": 16.672721,
            "LTL": 3.423367,
            "LVL": 0.701301,
            "LYD": 5.286992,
            "MAD": 10.523171,
            "MDL": 20.308866,
            "MGA": 4596.968257,
            "MKD": 61.573665,
            "MMK": 2090.636621,
            "MNT": 3305.323842,
            "MOP": 9.29586,
            "MRO": 413.900847,
            "MUR": 50.456592,
            "MVR": 17.912157,
            "MWK": 946.059565,
            "MXN": 24.068578,
            "MYR": 4.809714,
            "MZN": 74.003311,
            "NAD": 16.67216,
            "NGN": 475.835703,
            "NIO": 40.833213,
            "NOK": 9.820034,
            "NPR": 138.605952,
            "NZD": 1.630341,
            "OMR": 0.446381,
            "PAB": 1.159522,
            "PEN": 4.630623,
            "PGK": 4.092583,
            "PHP": 58.606418,
            "PKR": 198.777194,
            "PLN": 4.607227,
            "PYG": 8002.52777,
            "QAR": 4.221316,
            "RON": 4.950952,
            "RSD": 117.521106,
            "RUB": 82.987158,
            "RWF": 1159.386684,
            "SAR": 4.348893,
            "SBD": 9.304923,
            "SCR": 15.30889,
            "SDG": 510.706732,
            "SEK": 9.917765,
            "SGD": 1.562993,
            "SHP": 1.59694,
            "SLL": 12608.330146,
            "SOS": 679.401061,
            "SRD": 25.043331,
            "STD": 23996.963576,
            "SVC": 10.146189,
            "SYP": 1457.316627,
            "SZL": 16.671563,
            "THB": 38.539754,
            "TJS": 13.021558,
            "TMT": 4.057853,
            "TND": 3.28628,
            "TOP": 2.591751,
            "TRY": 11.11771,
            "TTD": 7.862122,
            "TWD": 32.295299,
            "TZS": 2670.067065,
            "UAH": 30.495128,
            "UGX": 4120.333951,
            "USD": 1.159387,
            "UYU": 51.245598,
            "UZS": 12405.437757,
            "VEF": 247911912510.44647,
            "VND": 26374.307991,
            "VUV": 130.194989,
            "WST": 2.987731,
            "XAF": 655.516941,
            "XAG": 0.049438,
            "XAU": 0.000648,
            "XCD": 3.133301,
            "XDR": 0.821848,
            "XOF": 650.993236,
            "XPF": 119.938164,
            "YER": 290.1368,
            "ZAR": 17.880988,
            "ZMK": 10435.928687,
            "ZMW": 20.077322,
            "ZWL": 373.322039
          }
        }';
    }
}
