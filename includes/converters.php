<?php
defined('ABSPATH') or die("Jog on!");

function ws_ls_stones_pounds_to_pounds_only($stones, $pounds)
{
  return ($stones * 14) + $pounds;
}
function ws_ls_to_kg($stones, $pounds)
{
	$pounds += $stones * 14;
	return round($pounds / 2.20462, 2);
}
function ws_ls_pounds_to_kg($pounds)
{
	return round($pounds / 2.20462, 2);
}
function ws_ls_to_lb($kg)
{
	$pounds = $kg * 2.20462;
	return round($pounds, 2);
}
function ws_ls_to_stones($pounds)
{
	$pounds = $pounds / 14;
	return round($pounds, 2);
}
function ws_ls_pounds_to_stone_pounds($lb)
{
	$weight = array ("stones" => 0, "pounds" => 0);
	$weight["stones"] = floor($lb / 14);
 	$weight["pounds"] = Round(fmod($lb, 14), 1);
  return $weight;
}
function ws_ls_to_stone_pounds($kg)
{
		$weight = array ("stones" => 0, "pounds" => 0);
    $totalPounds = Round($kg * 2.20462, 3);
    $weight["stones"] = floor($totalPounds / 14);
    $weight["pounds"] = Round(fmod($totalPounds, 14), 1);
    return $weight;
}
function ws_ls_round_decimals($value)
{
  if (is_numeric($value)) {
    $value = round($value, 2);
  }
  return $value;
}
