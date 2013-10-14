(function($){
	$.extend({
		coinName : function(){
			$('.marketid').each(function(i, obj) {
				$.getJSON('cryptsy.php?action=coinName&id=' + $(this).text(), function(data) {
					$(obj).replaceWith("<span> " + data + " </span>");
				}); 
			});
		}
	});
})(jQuery);

(function($){
	$.extend({
		cancelOrder : function(id){
			$.getJSON('cryptsy.php?action=cancelOrder&id=' + id, function(data) {
				$("#refresh_button").trigger( "click" );
				alert(data);
			}); 
		}
	});
})(jQuery);

(function($){
	$.extend({
		createOrder : function(marketid, ordertype, quantity, price){
			$.getJSON('cryptsy.php?action=createOrder&marketid=' + marketid + '&ordertype=' + ordertype + '&quantity=' + quantity + '&price=' + price, function(data) {
				$("#refresh_button").trigger( "click" );
				alert(data);
			}); 
		}
	});
})(jQuery);

(function($){
	$.extend({
		getBalancesAvailable : function(){
		$( "#balances_available" ).addClass("shade");
			$.getJSON('cryptsy.php?action=getBalancesAvailable', function(data) {
				$( "#balances_available span" ).remove();
				$.each(data.balances_available, function(coin, quantity) {
					if(quantity != 0){
						$( "#balances_available" ).append("<span onclick='$('#select_coin').val(" + coin + ")'>" + coin + "</span><span> = " + quantity + " <br></span>");
					}
				});
				$( "#balances_available" ).removeClass("shade");
			}); 
		}
	});
})(jQuery);

(function($){
	$.extend({
		getBalancesHold : function(){
			$( "#balances_hold" ).addClass("shade");
			$.getJSON('cryptsy.php?action=getBalancesHold', function(data) {
				$( "#balances_hold span" ).remove();
				$.each(data.balances_hold, function(coin, quantity) {
					if(quantity != 0){
						$( "#balances_hold" ).append("<span>" + coin + " = " + quantity + "<br></span>");
					}
				});
				$( "#balances_hold" ).removeClass("shade");
			});
		}
	});
})(jQuery);

(function($){
	$.extend({
		getTrades : function(id){
			$( "#market_trades" ).addClass("shade");
			$.getJSON('cryptsy.php?action=getTrades&id=' + id, function(data) {
				$( "#market_trades span" ).remove();
				$( "#market_trades br" ).remove(); //replace with css class removal
				$( "#market_trades" ).append("<span>Trade ID</span><span>Date</span><span>Trade Price</span><span>Quantity</span><span>Total</span><span>Initiate Ordertype</span><br>");
				$.each(data, function(i, object) {
					$.each(object, function(property, value) {		
						if(property=='initiate_ordertype'){
							if(value=='Buy'){
								$( "#market_trades" ).append("</span><span class='buy'>" + value + "</span>");
							}else if(value=='Sell'){
								$( "#market_trades" ).append("<span class='sell'>" + value + "</span>");
							}
						}else{
							$( "#market_trades" ).append("<span>" + value + "</span>");
						}
					});
					$( "#market_trades" ).append("<br>"); //replace with css class removal
				});
				setTimeout(function(){ // transition out css effect fix
					$( "#market_trades" ).removeClass("shade");
				}, 50);
			});
		}
	});
})(jQuery);

(function($){
	$.extend({
		myOrders : function(){
			$( "#my_orders" ).addClass("shade");
			$.getJSON('cryptsy.php?action=myOrders', function(data) {
				$( "#my_orders span" ).remove();
				$( "#my_orders br" ).remove(); //replace with css class removal
				//$( "#my_orders a" ).remove();
				$( "#my_orders" ).append("<span>Order ID</span><span>Market</span><span>Created</span><span>Order Type</span><span>Price</span><span>Quantity</span><span>Original Quantity</span><span>Total</span><br>");
				$.each(data, function(i, object) {
					$.each(object, function(property, value) {	
						if(property == 'marketid'){
							$( "#my_orders" ).append("<span class='marketid'>" + value + "</span>");
						}else if(property == 'orderid'){
							//$( "#my_orders" ).append("<span>" + property + " : </span><span class='orderid'>" + value + "</span>");
							$( "#my_orders" ).append("<span class='cancel_button' onclick='$.cancelOrder(" + value + ")'>" + value + " | X | </span>");
						}else{
							$( "#my_orders" ).append("<span>" + value + "</span>");
						}
					});
					$( "#my_orders" ).append("<br>"); //replace with css class removal
				});
				setTimeout(function(){
					$.coinName();
					//$.cancelOrderButton();
					$( "#my_orders" ).removeClass("shade");
				}, 50);
			});
		}
	});
})(jQuery);

(function($){
	$.extend({
		getMarkets : function(){
			$( "#select_coin" ).addClass("shade");
			$.getJSON('cryptsy.php?action=getMarkets', function(data) {
				$.each(data, function(coin, quantity) {
					$( "#select_coin" ).append("<option value=" + quantity.marketid + ">" + quantity.label + " </option>");
				});
				$( "#select_coin" ).removeClass("shade");
			}); 
		}
	});
})(jQuery);

(function($){
	$.extend({
		getBuyOrders : function(id){
		$( "#buy_orders" ).addClass("shade");
			$.getJSON('cryptsy.php?action=getBuyOrders&id=' + id, function(data) {
				$( "#buy_orders span" ).remove();
				$( "#buy_orders br" ).remove(); //replace with css class removal
				$( "#buy_orders" ).append("<span>Buy Price</span><span>Quantity</span><span>Total</span><br>");
				$.each(data, function(i, obj) {
					$.each(obj, function(coin, quantity) {
						$( "#buy_orders" ).append("<span>" + quantity + "</span>");
					});
					$( "#buy_orders" ).append("<br>"); //replace with css class removal
				});
				$( "#buy_orders" ).removeClass("shade");
			}); 
		}
	});
})(jQuery);

(function($){
	$.extend({
		getSellOrders : function(id){
		$( "#sell_orders" ).addClass("shade");
			$.getJSON('cryptsy.php?action=getSellOrders&id=' + id, function(data) {
				$( "#sell_orders span" ).remove();
				$( "#sell_orders br" ).remove(); //replace with css class removal
				$( "#sell_orders" ).append("<span>Sell Price</span><span>Quantity</span><span>Total</span><br>");
				$.each(data, function(i, obj) {
					$.each(obj, function(coin, quantity) {
						$( "#sell_orders" ).append("<span>" + quantity + "</span>");
					});
					$( "#sell_orders" ).append("<br>"); //replace with css class removal
				});
				$( "#sell_orders" ).removeClass("shade");
			}); 
		}
	});
})(jQuery);