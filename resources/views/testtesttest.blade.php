   <?php

   function randomColorPart() {
       return str_pad( dechex( mt_rand( 0, 240 ) ), 2, '0', STR_PAD_LEFT);
   }
   function randomColor() {
       return randomColorPart() . randomColorPart() . randomColorPart();
   }

    function generateImage($name) {
        $backColor = randomColor();
        echo $backColor;
        echo '<img src="https://ui-avatars.com/api/?size=190&name='.$name.'&font-size=0.45&background='.$backColor.'&color=fff&rounded=false">';
        echo '<br><br>';
    }

    for ($i=0; $i<10; $i++) generateImage('Конюхова Антонина');
   ?>
<!---->
{{--    машины:--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова&font-size=0.45&background=40E0D0&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=90ee90&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=ffa07a&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=87cefa&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=dda0dd&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=778899&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=008080&color=fff&rounded=false" class="center-block">--}}
{{--    тосины:--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=66cdaa&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=4682b4&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=fa8072&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=6b8e23&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=bdb76b&color=fff&rounded=false" class="center-block">--}}
{{--        <img src="https://ui-avatars.com/api/?size=190&name=Конюхова+Антонина&font-size=0.45&background=8b4513&color=fff&rounded=false" class="center-block">--}}





