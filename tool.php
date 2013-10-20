<?php
include 'cryptsy.php';
function compareMarkets($id0, $id1, $id2){
	$market_0=getTopOrders($id0);
	$market_1=getTopOrders($id1);
	$market_2=getTopOrders($id2);
	if($market_0['buy_volume_total']<$market_1['sell_volume_total']&&$market_0['buy_volume']<
	$market_2['sell_volume']){
		//market 0
		echo $market_0['market_name']." is the lower volume with: ".$market_0['buy_volume'].$market_0['market_name_primary']." .";
		echo "<br>Trying ".$market_0['market_name']." order: Sell ".$market_0['sell_volume'].$market_2['market_name_secondary']." for: ".$market_0['sell_volume_total']." .";
	}else if($market_1['sell_volume_total']<$market_0['buy_volume_total']&&$market_1['buy_volume']<$market_2['sell_volume_total']){
		//market 1
		echo $market_1['market_name']." is the lower volume with: ".$market_1['sell_volume_total'].$market_1['market_name_secondary']." .";
		//first order
		echo "<br>Trying ".$market_0['market_name']." order: Sell ".$market_1['sell_volume_total'].$market_1['market_name_secondary']." worth of: ".$market_0['market_name_primary']." .";
		$amount_to_sell=$market_1['sell_volume_total']/$market_0['buy_price'];
		$amount_to_sell=$amount_to_sell+($amount_to_sell*0.003);
		$initial_investment=$amount_to_sell;
		echo "<br>I can Sell: ".$amount_to_sell.$market_0['market_name_primary']." .";
		$fee_calc=api_query("calculatefees", array("ordertype" => 'Sell', 'quantity' => $amount_to_sell, 'price' => $market_0['buy_price']));
		$market_0_total=$fee_calc['return']['net'];
		echo "<br>This sucker gave me only: ".$market_0_total.$market_0['market_name_secondary']." moving on.";
		//second order
		echo "<br>Trying ".$market_1['market_name']." order: Buy ".$market_0_total.$market_1['market_name_secondary']." worth of: ".$market_1['market_name_primary']." .";
		$amount_to_buy=$market_0_total/$market_1['sell_price'];
		$amount_to_buy=$amount_to_buy-($amount_to_buy*0.002);
		echo "<br>I can Buy: ".$amount_to_buy.$market_1['market_name_primary']." .";
		$fee_calc=api_query("calculatefees", array("ordertype" => 'Buy', 'quantity' => $amount_to_buy, 'price' => $market_1['sell_price']));
		$market_1_total=$fee_calc['return']['net'];
		echo "<br>This sucker charged me: ".$market_1_total.$market_1['market_name_secondary']." moving on.";
		//third order
		echo "<br>Trying ".$market_2['market_name']." order: Buy ".$amount_to_buy.$market_2['market_name_secondary']." worth of: ".$market_2['market_name_primary']." .";
		$amount_to_buy=$amount_to_buy/$market_2['sell_price'];
		$amount_to_buy=$amount_to_buy-($amount_to_buy*0.002);
		echo "<br>I can Buy: ".$amount_to_buy.$market_2['market_name_primary']." .";
		$fee_calc=api_query("calculatefees", array("ordertype" => 'Buy', 'quantity' => $amount_to_buy, 'price' => $market_2['sell_price']));
		$market_2_total=$fee_calc['return']['net'];
		echo "<br>This sucker charged me: ".$market_2_total.$market_2['market_name_secondary']." finished.";
		echo "<br>I ended up with: ".$amount_to_buy.$market_2['market_name_primary']." from the starting: ".$initial_investment.$market_0['market_name_primary']." .";
		if($initial_investment>$amount_to_buy){
			echo "<br>Theres no profit here: ".($amount_to_buy-$initial_investment).$market_2['market_name_primary']." from the initial: ".$initial_investment.$market_0['market_name_primary']." .";
		}else{
			echo "<br>Theres ".($amount_to_buy-$initial_investment).$market_2['market_name_primary']." profit here from the initial: ".$initial_investment.$market_0['market_name_primary']." investment.";
		}
	}else if($market_2['sell_volume_total']<$market_1['buy_volume']&&$market_2['sell_volume']<$market_0['buy_volume']){
		//market 2
		echo $market_2['market_name']." is the lower volume with: ".$market_2['sell_volume'].$market_2['market_name_primary']." .";
		//first order
		$initial_investment=$market_2['sell_volume'];
		echo "<br>Trying ".$market_0['market_name']." order: Sell ".$market_2['sell_volume'].$market_2['market_name_primary']." .";
		$fee_calc=api_query("calculatefees", array("ordertype" => 'Sell', 'quantity' => $market_2['sell_volume'], 'price' => $market_0['buy_price']));
		$market_0_total=$fee_calc['return']['net'];
		echo "<br>This sucker gave me only: ".$market_0_total.$market_0['market_name_secondary']." moving on.";
		//second order
		echo "<br>Trying ".$market_1['market_name']." order: Buy ".$market_0_total.$market_1['market_name_secondary']." worth of: ".$market_1['market_name_primary']." .";
		$amount_to_buy=$market_0_total/$market_1['sell_price'];
		$amount_to_buy=$amount_to_buy-($amount_to_buy*0.002);
		echo "<br>I can Buy: ".$amount_to_buy.$market_1['market_name_primary']." .";
		$fee_calc=api_query("calculatefees", array("ordertype" => 'Buy', 'quantity' => $amount_to_buy, 'price' => $market_1['sell_price']));
		$market_1_total=$fee_calc['return']['net'];
		echo "<br>This sucker charged me: : ".$market_1_total.$market_1['market_name_primary']." moving on.";
		//third order
		echo "<br>Trying ".$market_2['market_name']." order: Buy ".$amount_to_buy.$market_2['market_name_secondary']." worth of: ".$market_2['market_name_primary']." .";
		$amount_to_buy=$amount_to_buy/$market_2['sell_price'];
		$amount_to_buy=$amount_to_buy-($amount_to_buy*0.002);
		echo "<br>I ended up with: ".$amount_to_buy.$market_2['market_name_primary']." from the starting: ".$initial_investment.$market_0['market_name_primary']." .";
		if($initial_investment>$amount_to_buy){
			echo "<br>Theres no profit here: ".($amount_to_buy-$initial_investment).$market_2['market_name_primary']." from the initial: ".$initial_investment.$market_0['market_name_primary']." .";
		}else{
			echo "<br>Theres ".($amount_to_buy-$initial_investment).$market_2['market_name_primary']." profit here from the initial: ".$initial_investment.$market_0['market_name_primary']." investment.";
		}
	}
}
function getTopOrders($marketid){
	//return top order and price for given market
	$orders = array();
	$result = api_query("marketorders", array("marketid" => $marketid));
	$orders['market_name']=coinName($marketid);
	$orders['market_name_primary']=coinSingleName($marketid);
	$orders['market_name_secondary']=coinPostName($marketid);
	$orders['sell_price'] = $result['return']['sellorders'][0]['sellprice'];
	$orders['sell_volume'] = $result['return']['sellorders'][0]['quantity'];
	$orders['sell_volume_total'] = $result['return']['sellorders'][0]['total'];
	$orders['buy_price'] = $result['return']['buyorders'][0]['buyprice'];
	$orders['buy_volume'] = $result['return']['buyorders'][0]['quantity'];
	$orders['buy_volume_total'] = $result['return']['buyorders'][0]['total'];
	return $orders;
}
function coinName($id){
	$markets = api_query("getmarkets");
	foreach($markets["return"] as $market){
		if($market["marketid"]==$id){
			return $market["label"];
		}
	}
}
function coinSingleName($id){
	$markets = api_query("getmarkets");
	foreach($markets["return"] as $market){
		if($market["marketid"]==$id){
			return $market['primary_currency_code'];
		}
	}
}
function coinPostName($id){
	$markets = api_query("getmarkets");
	foreach($markets["return"] as $market){
		if($market["marketid"]==$id){
			return $market['secondary_currency_code'];
		}
	}
}