<?php
/*  Copyright (C) 2013  Johnny "usTrUcX" joaogarcia@gmail.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>. */
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