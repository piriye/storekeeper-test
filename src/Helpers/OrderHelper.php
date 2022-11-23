<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Helpers;

class OrderHelper
{
    public static function objectToArray($obj) 
    {
        if (is_object($obj) || is_array($obj)) {
            $ret = (array) $obj;

            foreach($ret as &$item) {
                $item = self::objectToArray($item);
            }
            return $ret;
        }  else {
            return $obj;
        }
    }

    public static function getOrderTotal($items) 
    {
        $totalPrice = 0;

        foreach ($items as $item) {
            $totalPrice += $item['quantity'] * $item['item_price'];
        }

        return $totalPrice;
    }
}
