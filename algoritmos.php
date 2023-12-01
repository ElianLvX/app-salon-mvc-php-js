<?php

$array = array(16, 4, 3, 5, 2, 22, 1);

echo count($array); // 6

function bubleSort(&$array) : void {
    do {
        $mibool = false;
        for($i = 0; $i < count($array) - 1; $i++) {
            if($array[$i] > $array[$i + 1]) { // 16 > 4
                $temp = $array[$i]; // 16
                $array[$i] = $array[$i + 1];// $array[$i] = 4
                $array[$i + 1] = $temp; // $array[$i + 1] = 16
                $mibool = true;
            } // al salir la posision 1 del array vale 16 (este fue el mayor) 
        }
    } while($mibool);
}

bubleSort($array);

echo '<pre>';
print_r($array);
echo '</pre>';

/*

{6,5,3,1,8,7,2,4}
{**5,6**,3,1,8,7,2,4} -- 5 < 6 -> swap
{5,**3,6**,1,8,7,2,4} -- 3 < 6 -> swap
{5,3,**1,6**,8,7,2,4} -- 1 < 6 -> swap
{5,3,1,**6,8**,7,2,4} -- 8 > 6 -> no swap
{5,3,1,6,**7,8**,2,4} -- 7 < 8 -> swap
{5,3,1,6,7,**2,8**,4} -- 2 < 8 -> swap
{5,3,1,6,7,2,**4,8**} -- 4 < 8 -> swap

*/