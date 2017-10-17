<?php
/**
 * Created by PhpStorm.
 * User: olgakolos
 * Date: 16.10.17
 * Time: 1:48
 */
namespace Casino\Classes\Game\Cards;
class Cards
{
    private $arrayExistingCards;
    private $cardsAmount;
    private $arrayCards = [];

    public function __construct($cardsAmount = 2, $arrayExistingCards = [])
    {
        $this->cardsAmount = $cardsAmount;
        $this->arrayExistingCards = $arrayExistingCards;

        $this->cardsCreation();
    }

    private function cardsCreation() {
        for ($i = 0; $i < $this->cardsAmount; $i++) {
            $card = mt_rand(2, 14); // создаю случайное число в диапозоне от 2 до 14
            $multiplier = mt_rand(0, 3); //формирую случайным образом множитель
            if (1 == $multiplier) { //если множитель равен 1, добавляю к числу 100
                $card = $card + 100;
            } else if (2 == $multiplier) { //если множитель равен 2, добавляю к числу 200
                $card = $card + 200;
            } else if (3 == $multiplier) { //если множитель равен 3, добавляю к числу 300
                $card = $card + 300;
            }

            if (!in_array($card, $this->arrayCards) and !in_array($card, $this->arrayExistingCards)) { //проверяю, есть ли в массиве такое число, если нет добавляю его в массив
                $this->arrayCards = array_merge($this->arrayCards, [$card]);
            } else { // если число есть в массиве, откатываю цикл, чтобы сформировать новое число
                $i--;
            }
        }
    }

    public function getCards() {
        return $this->arrayCards;
    }
}