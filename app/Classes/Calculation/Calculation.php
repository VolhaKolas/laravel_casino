<?php
/**
 * Created by PhpStorm.
 * User: olgakolos
 * Date: 18.09.17
 * Time: 2:32
 */
namespace Casino\Classes\Calculation;
class Calculation
{
    private $arrayCards;

    public function __construct(array $array)
    {
        $this->arrayCards = $array;
    }

    private function pokerFlush() {
        $suit1 = []; //первая масть
        $suit2 = []; //вторая масть
        $suit3 = []; //третья масть
        $suit4 = []; //четвертая масть
        foreach ($this->arrayCards as $arrayCard) { //создаю 4 массива, содержащих разные масти исходного массива
            if($arrayCard >= 2 and $arrayCard <= 14) {
                $suit1 = array_merge($suit1, [$arrayCard]);
            } else if($arrayCard >= 102 and $arrayCard <= 114) {
                $suit2 = array_merge($suit2, [$arrayCard]);
            } else if($arrayCard >= 202 and $arrayCard <= 214) {
                $suit3 = array_merge($suit3, [$arrayCard]);
            } else {
                $suit4 = array_merge($suit4, [$arrayCard]);
            }}
        if(count($suit1) >= 5) {  //если количество карт первой масти больше или равно 5
            $result = $this->straightFlush($suit1); //проверяю не образует ли данная комбинация стрит флеш
            if(0 == $result) {//если стрит флеша нет
                foreach ($suit1 as $key1 => $s1) {//выбираю остатки от деления на 100
                    $suit1[$key1] = $s1 % 100;
                }
                rsort($suit1); //сортирую массив по убыванию
                $result = $suit1[0] * 1e+10 + $suit1[1] * 1e+8 + $suit1[2] * 1e+6 + $suit1[3] * 1e+4 + $suit1[4] * 1e+2;
            }
        } else if (count($suit2) >= 5) {   //если количество карт второй масти больше или равно 5
            $result = $this->straightFlush($suit2);
            if(0 == $result) {
                foreach ($suit2 as $key2 => $s2) {
                    $suit2[$key2] = $s2 % 100;
                }
                rsort($suit2);
                $result = $suit2[0] * 1e+10 + $suit2[1] * 1e+8 + $suit2[2] * 1e+6 + $suit2[3] * 1e+4 + $suit2[4] * 1e+2;
            }
        } else if (count($suit3) >= 5) { //если количество карт третьей масти больше или равно 5
            $result = $this->straightFlush($suit3);
            if(0 == $result) {
                foreach ($suit3 as $key3 => $s3) {
                    $suit3[$key3] = $s3 % 100;
                }
                rsort($suit3);
                $result = $suit3[0] * 1e+10 + $suit3[1] * 1e+8 + $suit3[2] * 1e+6 + $suit3[3] * 1e+4 + $suit3[4] * 1e+2;
            }
        } else if (count($suit4) >= 5) {     //если количество карт четвертой масти больше или равно 5
            $result = $this->straightFlush($suit4);
            if(0 == $result) {
                foreach ($suit4 as $key4 => $s4) {
                    $suit4[$key4] = $s4 % 100;
                }
                rsort($suit4);
                $result = $suit4[0] * 1e+10 + $suit4[1] * 1e+8 + $suit4[2] * 1e+6 + $suit4[3] * 1e+4 + $suit4[4] * 1e+2;
            }
        } else {
            $result = 0;
        }
        return $result;
    }

    private function straightFlush(array $arrayCards) {
        $newArrayCards = [];
        $ace = false;
        foreach ($arrayCards as $arrayCard) {
            $newArrayCards = array_merge($newArrayCards, [$arrayCard % 100]);  //создаю массив с остатками от деления на 100
            if (14 == $arrayCard % 100) {
                $ace = true;
            }
        }
        if ($ace == true) {
            $newArrayCards = array_merge($newArrayCards, [1]); //если в массиве присутствует туз, добавляю к массиву 1
        }
        rsort($newArrayCards); //сортирую массив с числами в порядке убывания
        $count = 0; //счетчик, к которому добавляется 1 в случае, если разница между текущим элементом цикла и предыдущим = 1
        $length = 4; //число, показывает до какого элемента массива проверять
        $result = 0; //окончательный результат
        $begin = 0; //число показывает, с какого элемента массива проверять
        for ($i = 1; $i <= $length; $i++) {
            if (-1 == ($newArrayCards[$i] - $newArrayCards[$i - 1])) {
                $count++;
            }
            if ($length == $i) {
                if (4 == $count) { //$count == 4, когда стрит
                    $result = $newArrayCards[$begin] * 1e+16;
                } else if ((7 == count($arrayCards) and ($length < 6 or (6 == $length and $ace == true))) or//если стрита нет, идем еще на один круг
                    (6 == count($arrayCards) and ($length < 5 or (5 == $length and $ace == true))) or //число элементов массива = 6
                    (5 == count($arrayCards) and (5 == $length and $ace == true))) {//число элеметов массива = 5
                    $length++;
                    $begin++;
                    $i = $begin;
                    $count = 0;
                }
            }
        }
        return $result;
    }

    private function straight() {
        $newArrayCards = [];
        $ace = false;
        foreach ($this->arrayCards as $arrayCard) {
            $newArrayCards = array_merge($newArrayCards, [$arrayCard % 100]);  //создаю массив с остатками от деления на 100
            if(14 == $arrayCard % 100) { //проверка на туз для комбинации А, 2, 3, 4, 5
                $ace = true;
            }

        }
        if($ace == true) {
            $newArrayCards = array_merge($newArrayCards, [1]); //если в массиве присутствует туз, добавляю к массиву 1
        }
        $newArrayCards = array_unique($newArrayCards);
        rsort($newArrayCards); //сортирую массив с числами в порядке убывания
        $count = 0; //счетчик, к которому добавляется 1 в случае, если разница между текущим элементом цикла и предыдущим = 1
        $length = 4; //число, показывает до какого элемента массива проверять
        $result = 0; //окончательный результат
        $begin = 0; //число показывает, с какого элемента массива проверять
        for($i = 1; $i <= $length; $i++) {
            if (-1 == ($newArrayCards[$i] - $newArrayCards[$i - 1])) {
                $count++;
            }
            if($length == $i) {
                if(4 == $count) { //$count == 4, когда стрит
                    $result = $newArrayCards[$begin] * 1e+8;
                }
                else if($length < count($newArrayCards) - 1) {//если стрита нет, идем еще на один круг
                    $length++; //увеличиваем число, до которого будем проверять
                    $begin++; //увеличиваем число, с которого будем проверять
                    $i = $begin; //при попадании в for к $i автоматически добавится 1 ($i++ в for)
                    $count = 0; //обнуляю счетчик
                }
            }
        }
        return $result;
    }

    private function couple() {
        $newArrayCards = [];
        foreach ($this->arrayCards as $arrayCard) {
            $newArrayCards = array_merge($newArrayCards, [$arrayCard % 100]); //создаю массив с остатками от деления на 100
        }
        rsort($newArrayCards); //сортирую массив с числами в порядке убывания

        $count1 = 0; //счетчик для первой пары
        $count2 = 0; //счетчик для второй пары
        $count3 = 0; //счетчик для третьей пары

        $match1 = 0; //сюда положу значение первой пары
        $match2 = 0; //сюда положу значение второй пары
        $match3 = 0; //сюда положу значение третьей пары


        for($i = 1; $i < count($newArrayCards); $i++) {

            if ($newArrayCards[$i] == $match1 or $match1 == 0) { //считаю первое парное сочетание
                if ($newArrayCards[$i] == $newArrayCards[$i - 1]) {
                    $match1 = $newArrayCards[$i];
                    $count1++;
                }
            } else if ($newArrayCards[$i] == $match2 or $match2 == 0) { //считаю второе парное сочетание
                if ($newArrayCards[$i] == $newArrayCards[$i - 1]) {
                    $match2 = $newArrayCards[$i];
                    $count2++;
                }
            } else if ($newArrayCards[$i] == $match3 or $match3 == 0) { //считаю третье парное сочетание
                if ($newArrayCards[$i] == $newArrayCards[$i - 1]) {
                    $match3 = $newArrayCards[$i];
                    $count3++;
                }
            }
        }

        //здесь я преобразую 111 к 110 (2 пары) и 211 к 210 (фулл хаус)
        if(($count1 == 1 or $count1 == 2) and $count2 == 1 and $count3 == 1) {
            $count3 = 0;
        }
        //здесь я преобразую 121 сначала к 211 для простоты вычислений, а затем к 210 (фулл хаус)
        else if($count2 == 2 and $count1 == 1 and $count3 == 1) {
            $support = $match2;
            $match2 = $match1;
            $match1 = $support;
            $count1 = 2;
            $count2 = 1;
            $count3 = 0;
        }
        //здесь я преобразую 112 сначала к 211 для простоты вычислений, а затем к 210 (фулл хаус)
        else if($count3 == 2 and $count1 == 1 and $count2 == 1) {
            $support = $match3;
            $match2 = $match1;
            $match1 = $support;
            $count1 = 2;
            $count3 = 0;
        }
        //здесь я преобразую 220 к 210 (фулл хаус)
        else if($count1 == 2 and $count2 == 2 and $count3 == 0) {
            $count2 = 1;
        }
        //здесь я преобразую 120 к 210 (фулл хаус)
        else if ($count1 == 1 and $count2 == 2 and $count3 == 0) {
            $support = $match1;
            $match1 = $match2;
            $match2 = $support;
            $count1 = 2;
            $count2 = 1;
        }
        //320 к 300 и 310 к 300
        else if($count1 == 3 and ($count2 == 2 or $count2 == 1)) {
            $count2 = 0;
        }
        //230 к 320 и затем к 300 и 130 к 310 и затем к 300
        else if($count2 == 3 and($count1 == 2 or $count1 == 1)) {
            $support = $match2;
            $match2 = $match1;
            $match1 = $support;
            $count1 = 3;
            $count2 = 0;
        }

        //каре
        if ($count1 == 3) {
            $count1 = 1e+14;
        }
        //фулл хаус
        else if ($count1 == 2 and $count2 == 1) {
            $count1 = 1e+12;
            $count2 = 1e+10;
        }
        //тройка
        else if ($count1 == 2 and $count2 == 0) {
            $count1 = 1e+6;
        }
        //2 пары
        else if ($count1 == 1 and $count2 == 1) {
            $count1 = 1e+4;
            $count2 = 1e+2;
        }
        //пара
        else if ($count1 == 1 and $count2 == 0) {
            $count1 = 1e+2;
        }

        $result = $match1 * $count1 + $match2 * $count2;// $match1 и $match2 будут равны 0, если совпадений не было
        return $result;
    }

    public function priority() {
        //здесь я определяю старшую карту
        if($this->arrayCards[5] % 100 > $this->arrayCards[6] % 100) { //условились, что две последние карты массива - карманные
            $highCard1 = $this->arrayCards[5] % 100;
            $highCard2 = $this->arrayCards[6] % 100;
        } else {
            $highCard1 = $this->arrayCards[6] % 100;
            $highCard2 = $this->arrayCards[5] % 100;
        }


        $flush = $this->pokerFlush(); //вызываю функцию для расчета флеша
        $straight = $this->straight(); //вызываю функцию для расчета стрита
        $couple = $this->couple(); //вызываю функцию для расчета пар

        //далее определяю результат согласно приоритету комбинаций
        if($flush >= 1e+16) {
            $result = $flush; //стрит флеш
        } else if($couple >= 1e+14 and $couple < 1e+16) {
            $result = $couple; //каре
        } else if($couple >= 1e+12 and $couple < 1e+14) {
            $result = $couple; //фулл хаус
        } else if($flush >= 1e+10) {
            $result = $flush; //флеш
        } else if($straight >= 1e+8 and $straight < 1e+10) {
            $result = $straight; //стрит
        } else if($couple >= 1e+6 and $couple < 1e+8) {
            $result = $couple; //тройка
        } else if($couple >= 1e+4 and $couple < 1e+6) {
            $result = $couple; //две пары
        } else if($couple >= 1e+2 and $couple < 1e+4) {
            $result = $couple; //пара
        } else {
            $result = $highCard1 + $highCard2 * 1e-2; //старшая карта
        }
        return $result;
    }
}
