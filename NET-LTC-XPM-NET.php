<?php
include 'tool.php';
echo "<table border='1'>";
echo "<tr><td>Market</td><td>Sell Price</td><td>Sell Volume</td><td>Total</td><td>Buy Price</td><td>Buy Volume</td><td>Total</td></tr>";
if($result=getTopOrders(108)){
	// var_dump($result);
	echo "<tr><td id='".$result['market_name']."_market_name'>".$result['market_name']."</td>";
	echo "<td id='".$result['market_name']."_sell_price'>".$result['sell_price'].$result['market_name_secondary']."</td>";
	echo "<td id='".$result['market_name']."_sell_volume'>".$result['sell_volume'].$result['market_name_primary']."</td>";
	echo "<td id='".$result['market_name']."_sell_volume_total'>".$result['sell_volume_total'].$result['market_name_secondary']."</td>";
	echo "<td id='".$result['market_name']."_buy_price'>".$result['buy_price'].$result['market_name_secondary']."</td>";
	echo "<td id='".$result['market_name']."_buy_volume'>".$result['buy_volume'].$result['market_name_primary']."</td>";
	echo "<td id='".$result['market_name']."_buy_volume_total'>".$result['buy_volume_total'].$result['market_name_secondary']."</td></tr>";
}
if($result=getTopOrders(106)){
	// var_dump($result);
	echo "<tr><td id='".$result['market_name']."_market_name'>".$result['market_name']."</td>";
	echo "<td id='".$result['market_name']."_sell_price'>".$result['sell_price'].$result['market_name_secondary']."</td>";
	echo "<td id='".$result['market_name']."_sell_volume'>".$result['sell_volume'].$result['market_name_primary']."</td>";
	echo "<td id='".$result['market_name']."_sell_volume_total'>".$result['sell_volume_total'].$result['market_name_secondary']."</td>";
	echo "<td id='".$result['market_name']."_buy_price'>".$result['buy_price'].$result['market_name_secondary']."</td>";
	echo "<td id='".$result['market_name']."_buy_volume'>".$result['buy_volume'].$result['market_name_primary']."</td>";
	echo "<td id='".$result['market_name']."_buy_volume_total'>".$result['buy_volume_total'].$result['market_name_secondary']."</td></tr>";
}
if($result=getTopOrders(104)){
	// var_dump($result);
	echo "<tr><td id='".$result['market_name']."_market_name'>".$result['market_name']."</td>";
	echo "<td id='".$result['market_name']."_sell_price'>".$result['sell_price'].$result['market_name_secondary']."</td>";
	echo "<td id='".$result['market_name']."_sell_volume'>".$result['sell_volume'].$result['market_name_primary']."</td>";
	echo "<td id='".$result['market_name']."_sell_volume_total'>".$result['sell_volume_total'].$result['market_name_secondary']."</td>";
	echo "<td id='".$result['market_name']."_buy_price'>".$result['buy_price'].$result['market_name_secondary']."</td>";
	echo "<td id='".$result['market_name']."_buy_volume'>".$result['buy_volume'].$result['market_name_primary']."</td>";
	echo "<td id='".$result['market_name']."_buy_volume_total'>".$result['buy_volume_total'].$result['market_name_secondary']."</td></tr>";
}
echo "</table>";
compareMarkets(108, 106, 104);