<?php

header('Content-Type: application/json');

$currentRedeposit = array_key_exists('currentRedeposit', $_GET)?$_GET['currentRedeposit']:null;
$currentQtyRedeposits = array_key_exists('currentQtyRedeposits', $_GET)?$_GET['currentQtyRedeposits']:null;
$totalCustomers = array_key_exists('totalCustomers', $_GET)?$_GET['totalCustomers']:null;
$percentAffected = array_key_exists('percentAffected', $_GET)?$_GET['percentAffected']:null;

if(empty($currentRedeposit) || !is_numeric($currentRedeposit) || $currentRedeposit <= 0)
{
    $response = ["status"=>"failure", "error"=>"currentRedeposit field must be a number > 0"];
} 
elseif(empty($currentQtyRedeposits) || !is_numeric($currentQtyRedeposits) || $currentQtyRedeposits <= 0)
{
    $response = ["status"=>"failure", "error"=>"currentQtyRedeposits field must be a number > 0"];
}
elseif(empty($totalCustomers) || !is_numeric($totalCustomers) || $totalCustomers <= 0)
{
    $response = ["status"=>"failure", "error"=>"totalCustomers field must be a number > 0"];
}
elseif(empty($percentAffected) || !is_numeric($percentAffected) || $percentAffected < 0 || $percentAffected > 1.0)
{
    $response = ["status"=>"failure", "error"=>"percentAffected field must be number between 0 and 1"];
} 
else {

    $avgRedepositQtyIncrease = 0.25;
    $avgRedepositAmountIncrease = 1.28;

    $newRedeposit = $currentRedeposit * (1.0 + $avgRedepositAmountIncrease);
    $newQtyRedeposit = $currentQtyRedeposits * (1.0 + $avgRedepositQtyIncrease);

    $totalCurrentRedeposit = $currentRedeposit * $currentQtyRedeposits * $totalCustomers;
    $totalNewRedeposit = $newRedeposit * $newQtyRedeposit * $totalCustomers;
    $diff = round($totalNewRedeposit - $totalCurrentRedeposit);
    $value = round($diff * $percentAffected);

    $response = ["status"=>"success", "data"=>["totalvalue"=>$diff, "affectedvalue"=>$value]];
}

echo json_encode($response);


?>
