<?php
namespace App\Helpers;
use App\Models\AcctAccount;
use App\Models\AcctAccountSetting;
use App\Models\CompanySetting;
use App\Models\PreferenceTransactionModule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AppHelper
{
    protected static $data;
    /**
     * Get random quote from storage
     *
     * @return string format: {quote}-{by}
     */
    public static function quote()
    {
        $quotes = collect(json_decode(Storage::get('public/quotes.min.json')))->random();
        return $quotes->quote.' - '.$quotes->by;
    }
    /**
     * Get sales order status
     *
     * @return Collection
     */
    public static function status():Collection {
        return collect(['id'=>[1=>'Sudah Dibayar',2=>'Sudah Check-In',3=>'Sudah Check-Out',],'type'=>[1=>'success',2=>'primary',3=>'info',]]);
    }
    /**
     * Get menu type
     *
     * @return Collection
     */
    public static function menuType():Collection {
        return collect([1 => 'Breakfast', 2 => 'Lunch', 3 => 'Dinner']);
    }
    /**
     * Get order (booking) type
     *
     * @return Collection
     */
    public static function orderType():Collection {
        return collect([0 => 'Dengan Uang Muka', 3 => 'Tanpa Uang Muka', 4 => 'Full Book']);
    }
    /**
     * Get Transaction Module
     *
     * @param [string] $transaction_module_code
     * @return Collection
     */
    public static function getTransactionModule(string $transaction_module_code)
    {
        return PreferenceTransactionModule::select(['transaction_module_name as name','transaction_module_id as id'])->where('transaction_module_code',$transaction_module_code)->first();
    }
    /**
     * Get Account Seting status and account id
     *
     * @param string $account_setting_name
     * @return Collection
     */
    public static function getAccountSetting(string $account_setting_name){
        return AcctAccountSetting::select(['account_setting_status as status','account_id'])->where('company_id', Auth::user()->company_id)->where('account_setting_name', $account_setting_name)->first();
    }
    /**
     * Get account default status
     *
     * @param [int] $account_id
     * @return string
     */
    public static function getAccountDefaultStatus(int $account_id)
    {
        $data = AcctAccount::where('account_id',$account_id)->first();

        return $data->account_default_status;
    }
    /**
     * Get Month
     *
     * @param [int] $month
     * @return string
     */
    public static function month($month=null){
        $coll = collect([
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ]);
        if(is_null($month)){
        return  $coll;
        }
        return $coll[$month];
    }
    /**
     * Convert integer to roman numeral
     *
     * @param integer $int
     * @return string
     */
    public static function toRome(int $number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
    /**
     * Get app config
     * return false if config not found
     * @param mixed|string $name
     * @return mixed|bool
     */
    public static function config($name) {
        return (CompanySetting::where('company_id',Auth::user()->company_id)->where('name',$name)->first()->value??false);
    }
     /**
     * Set app config
     * return false if config not found
     * @param mixed|string $name (key)
     * @return void
     */
    public static function setConfig($name,$value) {
         CompanySetting::updateOrCreate(['company_id'=>Auth::user()->company_id,'name'=>$name],['value'=>$value]);
    }
    /**
     * Get Payment Type Array
     *
     * @return array|string
     */
    public static function paymentType($id=null) {
        $type = [0=>"Tunai",1=>"Transfer"];
        if(is_null($id)){
            return $type;
        }
        return $type[$id];
    }
    /**
     * Get Payment Status Array
     *
     * @return array|string
     */
    public static function paymentStatus($id=null) {
        $type = [0=>"Belum Dibayar",1=>"Lunas"];
        if(is_null($id)){
            return $type;
        }
        return $type[$id];
    }
    function __destruct() {
       self::$data = ''; 
      }
}