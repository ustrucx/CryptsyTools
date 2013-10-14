<?php
// Prevent caching.
header('Cache-Control: no-cache, must-revalidate');
// The JSON standard MIME header.
// header('Content-type: application/json');
function api_query($method, array $req = array()) {
	// API settings
	$key = ''; // your API-key
	$secret = ''; // your Secret-key
	$req['method'] = $method;
	$mt = explode(' ', microtime());
	$req['nonce'] = $mt[1];
	// generate the POST data string
	$post_data = http_build_query($req, '', '&');
	$sign = hash_hmac("sha512", $post_data, $secret);
	// generate the extra headers
	$headers = array(
			'Sign: '.$sign,
			'Key: '.$key,
	);
	// our curl handle (initialize if required)
	static $ch = null;
	if (is_null($ch)) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; Cryptsy API PHP client; '.php_uname('s').'; PHP/'.phpversion().')');
	}
	curl_setopt($ch, CURLOPT_URL, 'https://www.cryptsy.com/api');
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	// run the query
	$res = curl_exec($ch);
	if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));
	$dec = json_decode($res, true);
	if (!$dec) throw new Exception('Invalid data received, please make sure connection is working and requested API exists');
	return $dec;
}

/* function findBuyVolume($marketid, $volume){
	$result = api_query("marketorders", array("marketid" => $marketid));
	$sell_price_0 = $result['return']['sellorders'][0]['sellprice'];
	$sell_quantity_0 = $result['return']['sellorders'][0]['quantity'];
	$buy_price_0 = $result['return']['buyorders'][0]['buyprice'];
	$buy_quantity_0 = $result['return']['buyorders'][0]['quantity'];
	$sell_price_1 = $result['return']['sellorders'][1]['sellprice'];
	$sell_quantity_1 = $result['return']['sellorders'][1]['quantity'];
	$buy_price_1 = $result['return']['buyorders'][1]['buyprice'];
	$buy_quantity_1 = $result['return']['buyorders'][1]['quantity'];
	$orders = array();
	if($volume <= $sell_quantity_0){
		$fee_0=api_query("calculatefees", array("ordertype" => 'Sell', 'quantity' => $volume, 'price' => $sell_price_0));
		$total_fee_0=$fee_0['return']['net'];
		$orders[1]="Buying: <span id='".$marketid."_buy_quantity_0'>".$volume."</span> ".$marketid." for: <span id='".$marketid."_sell_price_0'>".$sell_price_0."</span> ".$marketid." each totals: ".$total_fee_0." ".$marketid." spent.<input type='button' id='".$marketid."_buy_button_0'><br>";
		$orders[0]=1;
		$orders[4]=$volume;
		return $orders;
		$volume_leftover=$volume-$sell_quantity_0;
	}else if($volume_leftover <= $sell_quantity_1){
		$fee_0=api_query("calculatefees", array("ordertype" => 'Sell', 'quantity' => $sell_quantity_0, 'price' => $sell_price_0));
		$total_fee_0=$fee_0['return']['net'];
		$orders[1]="Spliting initial ".$volume." <br>1st ".$marketid." order: Buying: <span id='".$marketid."_buy_quantity_0'>".$sell_quantity_0."</span> ".$marketid." for: <span id='".$marketid."_sell_price_0'>".$sell_price_0."</span> ".$marketid." each totals: ".$total_fee_0." ".$marketid." spent.<input type='button' id='".$marketid."_buy_button_0'><br>";
		
		$volume_second_order_raw=$volume_leftover/$sell_price_1;
		$volume_second_order=$volume_second_order_raw-($volume_second_order_raw*0.002);
		
		$fee_1=api_query("calculatefees", array("ordertype" => 'Sell', 'quantity' => $volume_second_order, 'price' => $sell_price_1));
		$total_fee_1=$fee_1['return']['net'];
		$orders[2]="2nd ".$marketid." order: Buying: <span id='".$marketid."_buy_quantity_1'>".$volume_second_order."</span> ".$marketid." for: <span id='".$marketid."_sell_price_1'>".$sell_price_1."</span> ".$marketid." each totals: ".$total_fee_1." ".$marketid." spent.<input type='button' id='".$marketid."_buy_button_1'><br>";
		$orders[0]=2;
		$orders[4]=$volume_second_order;
		return $orders;
	}else{
		return NULL;
	}
} */
function volumeFinder($initial_volume, $id1, $id2, $id3){
	//pyramid generator
	$top_volume=findPiramidTopVolume($id1, $id2, $id3);
	echo $top_volume["market_0_name"]."<br>Buy price: ".$top_volume['market_0_buy_price']." volume: ".$top_volume['market_0_buy_volume']." total: ".$top_volume['market_0_buy_order_fill']."<br>";
	echo $top_volume["market_1_name"]."<br>Sell price: ".$top_volume['market_1_sell_price']." volume: ".$top_volume['market_1_sell_volume']." total: ".$top_volume['market_1_sell_order_fill']."<br>";
	echo $top_volume["market_2_name"]."<br>Sell price: ".$top_volume['market_2_sell_price']." volume: ".$top_volume['market_2_sell_volume']." total: ".$top_volume['market_2_sell_order_fill']."<br>";
	echo $top_volume["market_0_name"]." => ".$top_volume["market_1_name"]." => ".$top_volume["market_2_name"]."<br><br>";
	echo "Do I have funds to fulfill the initial order?<br>";
	//set balance against my available balance
	if($initial_volume > 1){
		$balance=$initial_volume;
	}else{
		if($top_volume['market_2_sell_volume']>$top_volume['market_0_buy_volume']){
			$balance=compareBalance($id1, $top_volume['market_0_buy_volume']);
		}else{
			$balance=compareBalance($id1, $top_volume['market_2_sell_volume']);
		}
	}
	if($balance>=$top_volume['market_0_buy_volume']){
		$fee=api_query("calculatefees", array("ordertype" => 'Sell', 'quantity' => $top_volume['market_0_buy_volume'], 'price' => $top_volume['market_0_buy_price']));
		$total=$fee['return']['net'];
		echo "-Yes! ".$balance." .<br>";
		echo "-I can spend: ".$total." in the next trade.<br>";
	}else{
		$fee=api_query("calculatefees", array("ordertype" => 'Sell', 'quantity' => $balance, 'price' => $top_volume['market_0_buy_price']));
		$total=$fee['return']['net'];
		echo "-No, using all my balance instead: ".$balance." .<br>";
		echo "-I can spend: ".$total." in the next trade.<br>";
	}
	echo "<br>Is the next coin volume enough for buyin: ".$total." worth of it?<br><br>";
	//this tells the function either to tell the profit or to run itself with new volume
	$insuficient_volume_warning=0;
	//calculate how much I can buy with $total
	$new_total=$total/$top_volume['market_1_sell_price'];
	$new_volume=$new_total-($new_total*0.002);
	echo "I can buy: ".$new_volume." with ".$total."<br>";
	if($new_volume > $top_volume['market_1_sell_volume']){
		$insuficient_volume_warning=1;
		echo "-No! ".($top_volume['market_1_sell_volume']-($new_volume))." missing.<br>";
		echo "I can only buy: ".$top_volume['market_1_sell_volume']." .<br>";
		echo "Recalculating the starting volume to match: ".$top_volume['market_1_sell_volume']." sell volume.<br>";
		$new_volume=$top_volume['market_1_sell_order_fill']+($top_volume['market_1_sell_order_fill']*0.002);
		echo "Starting volume changed to: ".$new_volume." .<br>";
	}else{
		$fee_0=api_query("calculatefees", array("ordertype" => 'Buy', 'quantity' => $new_volume, 'price' => $top_volume['market_1_sell_price']));
		$total_0=$fee_0['return']['net'];
		echo "-Yes! ".($top_volume['market_1_sell_volume']-$new_volume)." still remains.<br>";
		echo "I spent: ".$total_0." in this trade from: ".$total." available.<br>";
		echo "This order will generate: ".$new_volume." for the next step.<br>";
	}
	echo "<br>How about the last coin, enough volume for: ".$new_volume." ?<br><br>";
	//calculate how much I can buy with $total...
	$new_total_0=$new_volume/$top_volume['market_2_sell_price'];
	$new_volume_0=$new_total_0-($new_total_0*0.002);
	echo "I can buy: ".$new_volume_0." with ".$new_volume."<br>";
	if($new_volume_0 > $top_volume['market_2_sell_volume']){
		$insuficient_volume_warning=1;
		$buying_volume=$top_volume['market_2_sell_volume'];
		echo "-No! ".($top_volume['market_2_sell_volume']-$new_volume_0)." missing.<br>";
		echo "I can only buy: ".$top_volume['market_2_sell_volume']." .<br>";
		echo "Recalculating the starting volume to match: ".$top_volume['market_2_sell_volume']." sell volume.<br>";
		$new_volume_0=$top_volume['market_2_sell_order_fill']+($top_volume['market_2_sell_order_fill']*0.002);
		echo "Starting volume changed to: ".$new_volume_0." .<br>";
	}else{
		$fee_1=api_query("calculatefees", array("ordertype" => 'Buy', 'quantity' => $new_volume_0, 'price' => $top_volume['market_2_sell_price']));
		$total_1=$fee_1['return']['net'];
		echo "-Yes! ".($top_volume['market_2_sell_volume']-$new_volume_0)." still remains.<br>";
		echo "I spent: ".$total_1." in this trade from: ".$new_volume." available.<br>";
		echo "This order will generate: ".$new_volume_0." .<br>";
	}
	//if any -No run again with $new_volume_0 as $balance if -Yes, echo as final buy volume
	if($insuficient_volume_warning==1){
		echo "<br><br>The safe volume to start the chain is: ".$buying_volume." run me again.";
		return $buying_volume;
	}else{
		// echo "The initial volume: ".$total." is enough for the whole chain, the profit is: ".($total-$new_volume_0)." .";
		echo "Estimate profit: ".($new_volume_0-$balance)." .";
	}
}
function findPiramidTopVolume($marketid_0, $marketid_1, $marketid_2){
	$top_volume = array();
	//find initial market max buy volume
	$market_0_top_orders=getTopOrders($marketid_0);
	$top_volume['market_0_name']=coinName($marketid_0);
	$top_volume['market_0_buy_volume']=$market_0_top_orders['buy_volume'];
	$top_volume['market_0_buy_price']=$market_0_top_orders['buy_price'];
	$top_volume['market_0_buy_order_fill']=$market_0_top_orders['buy_volume']*$market_0_top_orders['buy_price'];
	$top_volume['market_0_sell_volume']=$market_0_top_orders['sell_volume'];
	$top_volume['market_0_sell_price']=$market_0_top_orders['sell_price'];
	$top_volume['market_0_sell_order_fill']=$market_0_top_orders['sell_volume']*$market_0_top_orders['sell_price'];
	//find second market max selling volume
	$market_1_top_orders=getTopOrders($marketid_1);
	$top_volume['market_1_buy_volume']=$market_1_top_orders['buy_volume'];
	$top_volume['market_1_buy_price']=$market_1_top_orders['buy_price'];
	$top_volume['market_1_buy_order_fill']=$market_1_top_orders['buy_volume']*$market_1_top_orders['buy_price'];
	$top_volume['market_1_name']=coinName($marketid_1);
	$top_volume['market_1_sell_volume']=$market_1_top_orders['sell_volume'];
	$top_volume['market_1_sell_price']=$market_1_top_orders['sell_price'];
	$top_volume['market_1_sell_order_fill']=$market_1_top_orders['sell_volume']*$market_1_top_orders['sell_price'];
	//find third market max selling volume
	$market_2_top_orders=getTopOrders($marketid_2);
	$top_volume['market_2_buy_volume']=$market_2_top_orders['buy_volume'];
	$top_volume['market_2_buy_price']=$market_2_top_orders['buy_price'];
	$top_volume['market_2_buy_order_fill']=$market_2_top_orders['buy_volume']*$market_2_top_orders['buy_price'];
	$top_volume['market_2_name']=coinName($marketid_2);
	$top_volume['market_2_sell_volume']=$market_2_top_orders['sell_volume'];
	$top_volume['market_2_sell_price']=$market_2_top_orders['sell_price'];
	$top_volume['market_2_sell_order_fill']=$market_2_top_orders['sell_volume']*$market_2_top_orders['sell_price'];
	return $top_volume;
	// var_dump($top_volume);
}
function getTopOrders($marketid){
	//return top order and price for given market
	$orders = array();
	$result = api_query("marketorders", array("marketid" => $marketid));
	$orders['sell_price'] = $result['return']['sellorders'][0]['sellprice'];
	$orders['sell_volume'] = $result['return']['sellorders'][0]['quantity'];
	$orders['buy_price'] = $result['return']['buyorders'][0]['buyprice'];
	$orders['buy_volume'] = $result['return']['buyorders'][0]['quantity'];
	return $orders;
}
function compareBalance($coin, $volume){
	//find my balance for given coin
	$coin_code=coinSingleName($coin);
	$my_info=api_query("getinfo");
	$balance_available=$my_info['return']['balances_available'][$coin_code];
	if($volume < $balance_available){
		//return initial volume
		return $volume;
	}else{
		//return balance
		return $balance_available;
	}
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
function myOrders(){
	$my_orders=api_query("allmyorders");
	return $my_orders;
}
function getBalancesAvailable(){
	$my_info=api_query("getinfo");
	return $my_info;
}
function getMarkets(){
	$markets = api_query("getmarkets");
	return $markets;
}
function getBalancesHold(){
	$my_info=api_query("getinfo");
	return $my_info;
}
function cancelOrder($id){
	$result=api_query("cancelorder", array("orderid" => $id));
	return($result);
}
function createOrder($marketid, $ordertype, $quantity, $price){
	$result=api_query("createorder", array("marketid" => $marketid, "ordertype" => $ordertype, "quantity" => $quantity, "price" => $price));
	return($result);
}
function getSellorders($id){
	$market_orders=api_query("marketorders", array("marketid" => $id));
	return($market_orders);
}
function getBuyorders($id){
	$market_orders=api_query("marketorders", array("marketid" => $id));
	return($market_orders);
}
function getTrades($id){
	$market_trades=api_query("markettrades", array("marketid" => $id));
	return($market_trades);
}
if(isset($_GET['action'])){
	$action=$_GET['action'];
	switch ($action){
		case cancelOrder:
			if(isset($_GET['id'])){
				$id=$_GET['id'];
				$return=cancelOrder($id);
				if($return['success'] == 1){
					echo json_encode($return['return']);
					break;
				}else{
					echo "Error! Nothing done. ";
					echo json_encode($return['error']);
					break;
				}
			}else{
				break;
			}
		case createOrder:
			if(isset($_GET['marketid'])){
				$marketid=$_GET['marketid'];
				$ordertype=$_GET['ordertype'];
				$quantity=$_GET['quantity'];
				$price=$_GET['price'];
				$return=createOrder($marketid, $ordertype, $quantity, $price);
				if($return['success'] == 1){
					echo json_encode($return['moreinfo']);
					break;
				}else{
					echo "Error! Nothing done. ";
					echo $return['error'];
					break;
				}
			}else{
				break;
			}
		case getBalancesAvailable:
			$result=getBalancesAvailable();
			if($result['success'] == 1){
				echo json_encode($result['return']);
				break;
			}else{
				echo "Error! Nothing done. ";
				echo $return['error'];
				break;
			}
		case getBalancesHold:
			$result=getBalancesHold();
			if($result['success'] == 1){
				echo json_encode($result['return']);
				break;
			}else{
				echo "Error! Nothing done. ";
				echo $return['error'];
				break;
			}
		case getTrades:
			if(isset($_GET['id'])){
				$result=getTrades($_GET['id']);
				if($result['success'] == 1){
					echo json_encode($result['return']);
					break;
				}else{
					echo "Error! Nothing done. ";
					echo $return['error'];
					break;
				}
			}else{
				break;
			}
		case getMarkets:
			$result=getMarkets();
			if($result['success'] == 1){
				echo json_encode($result['return']);
				break;
			}else{
				echo "Error! Nothing done. ";
				echo $return['error'];
				break;
			}
		case myOrders:
			$result=myOrders();
			if($result['success'] == 1){
				echo json_encode($result['return']);
				break;
			}else{
				echo "Error! Nothing done. ";
				echo $return['error'];
				break;
			}
		case coinName:
			$result=coinName($_GET['id']);
			echo json_encode($result);
			break;
		case coinSingleName:
			$result=coinSingleName($_GET['id']);
			echo json_encode($result);
			break;
		case getBuyOrders:
			$result=getBuyOrders($_GET['id']);
			if($result['success'] == 1){
				echo json_encode($result['return']['buyorders']);
				break;
			}else{
				echo "Error! Nothing done. ";
				echo $return['error'];
				break;
			}
		case getSellOrders:
			$result=getSellOrders($_GET['id']);
			if($result['success'] == 1){
				echo json_encode($result['return']['sellorders']);
				break;
			}else{
				echo "Error! Nothing done. ";
				echo $return['error'];
				break;
			}
		default:
			return json_encode("Do'h!!");
			break;
	}
}
