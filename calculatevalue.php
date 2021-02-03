<?php

header('Content-Type: application/json');

$currentRedeposit = array_key_exists('currentRedeposit', $_GET)?$_GET['currentRedeposit']:null;
$currentQtyRedeposits = array_key_exists('currentQtyRedeposits', $_GET)?$_GET['currentQtyRedeposits']:null;
$totalCustomers = array_key_exists('totalCustomers', $_GET)?$_GET['totalCustomers']:null;

if(empty($currentRedeposit) || !is_numeric($currentRedeposit) || $currentRedeposit <= 0)
{
    http_response_code(404);
    $response = ["status"=>"failure", "error"=>"currentRedeposit field must be a number > 0"];
} 
elseif(empty($currentQtyRedeposits) || !is_numeric($currentQtyRedeposits) || $currentQtyRedeposits <= 0)
{
    http_response_code(404);
    $response = ["status"=>"failure", "error"=>"currentQtyRedeposits field must be a number > 0"];
}
elseif(empty($totalCustomers) || !is_numeric($totalCustomers) || $totalCustomers <= 0)
{
    http_response_code(404);
    $response = ["status"=>"failure", "error"=>"totalCustomers field must be a number > 0"];
}
else {
    $percentAffected;
    $avgRedepositQtyIncrease = 0.25;
    $avgRedepositAmountIncrease = 1.28;

    $newRedeposit = $currentRedeposit * (1.0 + $avgRedepositAmountIncrease);
    $newQtyRedeposit = $currentQtyRedeposits * (1.0 + $avgRedepositQtyIncrease);

    $totalCurrentRedeposit = $currentRedeposit * $currentQtyRedeposits * $totalCustomers;
    $totalNewRedeposit = $newRedeposit * $newQtyRedeposit * $totalCustomers;
    $totalvalue = round($totalNewRedeposit - $totalCurrentRedeposit);
    $conservative = round($totalvalue * 0.1);
    $realistic = round($totalvalue * 0.25);
    $optimistic = round($totalvalue * 0.5);
    $ludicrous = round($totalvalue * 0.75);

    $response = ["status"=>"success", "data"=>[
        "totalvalue"=>$totalvalue, 
        "conservative"=>$conservative, 
        "realistic"=>$realistic, 
        "optimistic"=>$optimistic, 
        "ludicrous"=>$ludicrous]
    ];
}

echo json_encode($response);


?>
