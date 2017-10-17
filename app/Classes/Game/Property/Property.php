<?php
/**
 * Created by PhpStorm.
 * User: olgakolos
 * Date: 17.10.17
 * Time: 3:10
 */
namespace Casino\Classes\Game\Property;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
class Property
{
    private static $instance;
    private $column;

    private function __construct() {}

    public static function getInstance() {
        if(empty(self::$instance)) {
            self::$instance = new Property();
        }
        return self::$instance;
    }

    public function __get($property) {
        $this->column = $property;
        return User::where('t_id', Auth::user()->t_id)->where($this->column, 1)->select()->get()[0];
    }

}