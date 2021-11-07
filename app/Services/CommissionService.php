<?php

namespace App\Services;

use Carbon\Carbon;

class CommissionService
{
    protected $weeklyOperations = [];

    public function generateCommission($operations){
        $data = [];
        foreach ($operations as $operation){
            array_push($data, $this->roundCommission($this->{$operation[3]}($operation)));
        }
        return $data;
    }

    private function withdraw($operation){
        return $this->{$operation[2]}($operation); // private/business
    }

    private function deposit($operation){
        $depositCom = env('DEPOSIT_COMMISSION', 0.03);
        return ($operation[4] * $depositCom)/100;
    }

    private function private($operation){
        $amountFreePerWeek = env('WITHDRAW_AMOUNT_FREE_PER_WEEK', 1000);
        $numberOfFreeWithdraw = env('NUMBER_OF_FREE_WITHDRAW', 3);
        $privateWithdrawCom = env('PRIVATE_WITHDRAW_COMMISSION', 0.3);


        $convert = new CurrencyConversionService();
        $converted = $convert->convertToDefaultCurrency($operation[4], $operation[5], env('DEFAULT_CURRENCY', 'EUR'));
        $amount = $converted['amount'];
        $date = Carbon::parse($operation[0]);




        $key = $operation[1];  // id
        if(isset($this->weeklyOperations[$key]) && $this->weeklyOperations[$key]['lastWithdrawDate']->isSameWeek($date)){
            $val = [
                'total' => $this->weeklyOperations[$key]['total'] + $amount
                , 'count' => $this->weeklyOperations[$key]['count'] ++
                , 'lastWithdrawDate' => $date
            ];
        }
        else{
            $val = [
                'total' => $amount
                , 'count' => 1
                , 'lastWithdrawDate' => $date
            ];
        }

        $this->weeklyOperations[$key] = $val;

        $applyCom = $this->weeklyOperations[$key]['total'] - $amountFreePerWeek;



        if($applyCom > 0 || $this->weeklyOperations[$key]['count'] > $numberOfFreeWithdraw){ // apply charge
            if($this->weeklyOperations[$key]['count'] > $numberOfFreeWithdraw){
                $applyCom = $amount;
            }
            else{
                $applyCom = min($applyCom ? $applyCom : 0, $amount);
            }
            return ($applyCom * $converted['rate'] * $privateWithdrawCom)/100;
        }
        return 0;
    }

    private function business($operation){
        $businessWithdrawCom = env('BUSINESS_WITHDRAW_COMMISSION', 0.5);
        return ($operation[4] * $businessWithdrawCom)/100;
    }

    private function roundCommission($amount){
        return $amount ? number_format(ceil($amount * 100)/100, 2, '.', '') : 0;
    }
}
