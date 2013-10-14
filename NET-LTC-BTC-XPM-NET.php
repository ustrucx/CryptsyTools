<?php
// Prevent caching.
header('Cache-Control: no-cache, must-revalidate');
// The JSON standard MIME header.
//header('Content-type: application/json');
include 'cryptsy.php';

// echo "<h3>Cryptsy sniffage</h3>";


//LTC/BTC
$ltcbtc_result = api_query("marketorders", array("marketid" => 3));
$ltcbtc_sell_price = $ltcbtc_result['return']['sellorders'][0]['sellprice'];
$ltcbtc_sell_quantity = $ltcbtc_result['return']['sellorders'][0]['quantity'];
$ltcbtc_buy_price = $ltcbtc_result['return']['buyorders'][0]['buyprice'];
$ltcbtc_buy_quantity = $ltcbtc_result['return']['buyorders'][0]['quantity'];
// echo "LTC/BTC<br>Sell price: ".$ltcbtc_sell_price."btc, quantity: ".$ltcbtc_sell_quantity."ltc.<br>";
// echo "Buy price: ".$ltcbtc_buy_price."btc, quantity: ".$ltcbtc_buy_quantity."ltc.<br>";
//XPM/BTC
$xpmbtc_result = api_query("marketorders", array("marketid" => 63));
$xpmbtc_sell_price = $xpmbtc_result['return']['sellorders'][0]['sellprice'];
$xpmbtc_sell_quantity = $xpmbtc_result['return']['sellorders'][0]['quantity'];
$xpmbtc_buy_price = $xpmbtc_result['return']['buyorders'][0]['buyprice'];
$xpmbtc_buy_quantity = $xpmbtc_result['return']['buyorders'][0]['quantity'];
// echo "XPM/BTC<br>Sell price: ".$xpmbtc_sell_price."btc, quantity: ".$xpmbtc_sell_quantity."xpm.<br>";
// echo "Buy price: ".$xpmbtc_buy_price."btc, quantity: ".$xpmbtc_buy_quantity."xpm.<br>";
//XPM/LTC
$xpmltc_result = api_query("marketorders", array("marketid" => 108));
$xpmltc_sell_price = $xpmltc_result['return']['sellorders'][0]['sellprice'];
$xpmltc_sell_quantity = $xpmltc_result['return']['sellorders'][0]['quantity'];
$xpmltc_buy_price = $xpmltc_result['return']['buyorders'][0]['buyprice'];
$xpmltc_buy_quantity = $xpmltc_result['return']['buyorders'][0]['quantity'];
// echo "XPM/LTC<br>Sell price: ".$xpmltc_sell_price."ltc, quantity: ".$xpmltc_sell_quantity."xpm.<br>";
// echo "Buy price: ".$xpmltc_buy_price."ltc, quantity: ".$xpmltc_buy_quantity."xpm.<br>";
//NET/LTC
$netltc_result = api_query("marketorders", array("marketid" => 108));
$netltc_sell_price = $netltc_result['return']['sellorders'][0]['sellprice'];
$netltc_sell_quantity = $netltc_result['return']['sellorders'][0]['quantity'];
$netltc_buy_price = $netltc_result['return']['buyorders'][0]['buyprice'];
$netltc_buy_quantity = $netltc_result['return']['buyorders'][0]['quantity'];
// echo "NET/LTC<br>Sell price: ".$netltc_sell_price."ltc, quantity: ".$netltc_sell_quantity."net.<br>";
// echo "Buy price: ".$netltc_buy_price."ltc, quantity: ".$netltc_buy_quantity."net.<br>";
//NET/XPM
$netxpm_result = api_query("marketorders", array("marketid" => 104));
$netxpm_sell_price = $netxpm_result['return']['sellorders'][0]['sellprice'];
$netxpm_sell_quantity = $netxpm_result['return']['sellorders'][0]['quantity'];
$netxpm_buy_price = $netxpm_result['return']['buyorders'][0]['buyprice'];
$netxpm_buy_quantity = $netxpm_result['return']['buyorders'][0]['quantity'];
// echo "NET/XPM<br>Sell price: ".$netxpm_sell_price."xpm, quantity: ".$netxpm_sell_quantity."net.<br>";
// echo "Buy price: ".$netxpm_buy_price."xpm, quantity: ".$netxpm_buy_quantity."net.<br>";

echo "<h3>Chains Outlook</h3>";
echo "NET-LTC-BTC-XPM-NET<br><br>";
$my_info=api_query("getinfo");
$net_balance_available=$my_info['return']['balances_available']['NET'];
if($netltc_buy_quantity < $net_balance_available){
	$initial_net_investment=$netltc_buy_quantity;
}else {
	$initial_net_investment=$net_balance_available;
}
//net-ltc
$netltc_fee=api_query("calculatefees", array("ordertype" => 'Sell', 'quantity' => $initial_net_investment, 'price' => $netltc_buy_price));
$netltc_total=$netltc_fee['return']['net'];

echo "Selling: <span id='netltc_buy_quantity'>".$initial_net_investment."</span>net for: <span id='netltc_buy_price'>".$netltc_buy_price."</span>ltc each totals: ".$netltc_total."ltc gain.<input type='button' id='netltc_buy_button'><br>";

//ltc-btc
$ltcbtc_fee=api_query("calculatefees", array("ordertype" => 'Sell', 'quantity' => $netltc_total, 'price' => $ltcbtc_buy_price));
$ltcbtc_total=$ltcbtc_fee['return']['net'];

echo "Selling: <span id='netltc_total'>".$netltc_total."</span>ltc for: <span id='ltcbtc_buy_price'>".$ltcbtc_buy_price."</span>btc each totals: ".$ltcbtc_total."btc gain.<input type='button' id='ltcbtc_buy_button'><br>";

//btc-xpm
$xpm_buy_raw_quantity=$ltcbtc_total/$xpmbtc_sell_price;
$xpm_buy_quantity=$xpm_buy_raw_quantity-($xpm_buy_raw_quantity*0.002);
$btcxpm_fee=api_query("calculatefees", array("ordertype" => 'Buy', 'quantity' => $xpm_buy_quantity, 'price' => $xpmbtc_sell_price));
$btcxpm_total=$btcxpm_fee['return']['net'];

echo "Buying: <span id='xpm_buy_quantity'>".$xpm_buy_quantity."</span>xpm for: <span id='xpmbtc_sell_price'>".$xpmbtc_sell_price."</span>btc each totals: ".$btcxpm_total."btc spent.<input type='button' id='xpmbtc_buy_button'><br>";

//xpm-net
$net_buy_raw_quantity=$xpm_buy_quantity/$netxpm_sell_price;
$net_buy_quantity=$net_buy_raw_quantity-($net_buy_raw_quantity*0.002);
$xpmnet_fee=api_query("calculatefees", array("ordertype" => 'Buy', 'quantity' => $net_buy_quantity, 'price' => $netxpm_sell_price));
$xpmnet_total=$xpmnet_fee['return']['net'];

echo "Buying: <span id='net_buy_quantity'>".$net_buy_quantity."</span>net for: <span id='netxpm_sell_price'>".$netxpm_sell_price."</span>xpm each totals: ".$xpmnet_total."xpm spent.<input type='button' id='netxpm_buy_button'><br><br>";

$allMightyTotal=$net_buy_quantity-$initial_net_investment;
echo 'Total profit :'.$allMightyTotal.'net.';
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="js/ui/main.dev.js"></script>
<script  type="text/javascript">
		$(document).ready(function() {
			$("#netltc_buy_button").click(function() {
				$.createOrder(108, "Sell", $("#netltc_buy_quantity").text(), $("#netltc_buy_price").text());
			});
			$("#ltcbtc_buy_button").click(function() {
				$.createOrder(3, "Sell", $("#netltc_total").text(), $("#ltcbtc_buy_price").text());
			});
			
			$("#xpmbtc_buy_button").click(function() {
				$.createOrder(63, "Buy", $("#xpm_buy_quantity").text(), $("#xpmbtc_sell_price").text());
			});
			$("#netxpm_buy_button").click(function() {
				$.createOrder(104, "Buy", $("#net_buy_quantity").text(), $("#netxpm_sell_price").text());
			});
		});
	</script>