<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 09/05/2021
 * Time: 4:11
 */

// Original version from
// https://jpgraph.net/download/manuals/chunkhtml/ch03s02.html#sec.verifying-phpgd-inst

$cx = 150;
$cy = 100;
//draw($cx,$cy);
$cmd = $_GET['cmd'];

$cmd($cx,$cy);

function left($cx, $cy){ $cx -= 50; draw($cx, $cy); }
function down($cx, $cy){ $cy += 50; draw($cx, $cy); }
function up($cx, $cy){ $cy -= 50; draw($cx, $cy); }
function right($cx, $cy){ $cx += 50; draw($cx, $cy); }

function draw($cx, $cy)
{
// content="text/plain; charset=utf-8"
    $im = imagecreatetruecolor(300, 200);
    $black = imagecolorallocate($im, 0, 0, 0);
    $lightgray = imagecolorallocate($im, 230, 230, 230);
    $darkgreen = imagecolorallocate($im, 80, 140, 80);
    $white = imagecolorallocate($im, 255, 255, 255);

    imagefilledrectangle($im, 0, 0, 299, 199, $lightgray);
    imagerectangle($im, 0, 0, 299, 199, $black);
//imagefilledellipse ($im,150,100,210,110,$white);

    imagefilledellipse($im, $cx, $cy, 200, 100, $darkgreen);
    header("Content-type: image/png");
    imagepng($im);
}