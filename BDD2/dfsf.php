<?php 
$a=1;
$b=1;
$c=3;
$t = [];

if (1==2) {
    echo'gyehbj';
} else if ($a==1 || $b==1 || $c==1) {
    if ($a==1) {
        $t[]="a";
    }
    if ($b==1) {
        $t[]="b";
    }
    if ($c==1) {
        $t[]="c";
    }
    var_dump($t);
}

?>