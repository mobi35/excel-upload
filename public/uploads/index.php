<?php


enum Buratilya: string {


case Adrian = 'mobi';

case Radores = 'pogi';

}

$adrian = Buratilya::Adrian->value;

echo $adrian;


