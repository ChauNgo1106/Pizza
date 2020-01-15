"use strict";
// avoid warnings on using fetch and Promise --
/* global fetch, Promise */
// use port 80, i.e., apache server for webservice execution 
const baseUrl = "http://localhost:8080/cs637/chau1993/pizza2_server/api";
// globals representing state of data and UI
let selectedUser = 'none';
let selectedID = -1;
let sizes = [];
let toppings = [];
let users = [];
let orders = [];
let orderToppings = [];

let main = function () {//(sizes, toppings, users, orders) {
    setupTabs();  // for home/order pizza and meat/meatless
    // for home tab--
    displaySizesToppingsOnHomeTab();//done
    setupUserForm(); //done
    setupRefreshOrderListForm();//done
    setupAcknowledgeForm();//done
    displayOrders();
    // for order tab--
    setupOrderForm();
    displaySizesToppingsOnOrderForm();
};

//add code for showing Toppings and Sizes here
function displaySizesToppingsOnHomeTab() {
    // find right elements to build lists in the HTML
    // loop through sizes, creating <li>s for them
    // with class=horizontal to get them to go across horizontally
    // similarly with toppings
	for (let index = 0 ; index < sizes.length ; index++){
		$("#sizes").append($("<li class = horizontal>").text(sizes[index].size));
		$("#sizes").append($("</li>"));
	}
	for (let index = 0; index <toppings.length ; index++){
		$("#toppings").append($("<li class = horizontal>").text(toppings[index].topping));
		$("#toppings").append($("</li>"));
	}
}

function setupUserForm() {
    // find the element with id userselect
    // create <option> elements with value = username, for
    // each user with the current user selected, 
    // plus one for user "none".
    // Add a click listener that finds out which user was
    // selected, make it the "selectedUser", and fill it in the
    //  "username-fillin" spots in the HTML.
    //  Also change the visibility of the order-area
    // and redisplay the orders
    console.log("display users");
  	$('#userselect').append($('<option>').text(selectedUser));
    for (let z = 0 ; z < users.length ; z++){
    	$('#userselect').append($('<option>').text(users[z].username));
    	
    }
   $("#userform input").change(function(){
  // $("#userform input").on("click", function(){
    	selectedUser = $('#userselect option:selected').text();
    	//using foreach
    	users.forEach(function (user) {
            if (user.username === selectedUser) {
            	selectedID = user.id;
            }
        });
    });
   // $("#order-area").show();	 
        
}
function setupAcknowledgeForm() {
    console.log("setupAckForm...");
    $("#ackform input").on("click", function () {
        console.log("ack by user = " + selectedUser);
        //keep track the username you opted.
        let userRec = (users.filter(user => user.username === selectedUser))[0];
        console.log("ack user_rec: ", userRec);
        let selectedUserId = userRec.id;
        orders.forEach(function (order) {
            if (order.user_id == selectedUserId && order.status === 'Baked') {
                console.log("Found baked order for user " + order.user_id);
                order.status = 'Finished';
                updateOrder(order); // post update to server
            }
        });
        displayOrders();
        return false;
    });
}

function setupRefreshOrderListForm() {
    console.log("setupRefreshForm...");
    document.querySelector("#refreshbutton input").addEventListener("click", function () {
        console.log("refresh orders by user = " + selectedUser);
        getOrders();
        return false;
    });
}
function displayOrders() {
    console.log("displayOrders");

    // remove class "active" from the order-area
    // if selectedUser is "none", just return--nothing to do
    // empty the ordertable, i.e., remove its content: we'll rebuild it
    // add class active to order-area
    // find the user_id of selectedUser via the users array
    // find the in-progress orders for the user by filtering array 
    // orders on user_id and status
    // if there are no orders for user, make ordermessage be "none yet"
    //  and remove active from element id'd order-info
    // Otherwise, add class active to element order-info, make
    //   ordermessage be "", and rebuild the order table 
    // Finally, if there are Baked orders here, make sure that
    // ackform is active, else not active
   $("#userselect").change(function(){
   // $("#userform input").on("click", function(){
    	selectedUser = $('#userselect option:selected').text();
    	//using foreach
    	users.forEach(function (user) {
            if (user.username === selectedUser) {
            	selectedID = user.id;
            }
        });	 
    	  //show the orders
  		$("#order-area").show();
    	//fill in username for order table
  		$('#username-fillin1').append(selectedUser);
  		$("#order-info").show();
    	//fill in username for order form
    	$('#username-fillin2').append(selectedUser);
    	let label = $("<tr>");
        label.append($("<td>").text("Order ID"));
        label.append($("<td>").text("Size"));
        label.append($("<td>").text("Toppings"));
        label.append($("<td>").text("Status"));
        label.append($("</tr>"))
        $("#ordertable").append(label);
        //display orders 
    	orders.forEach(function (order) {
    		orderToppings.forEach(function (orderTopping) {
    		
    		//display order including toppings
            if (order.user_id === selectedID && orderTopping.order_id === order.id) {
            	let temp = $("<tr>");
            	temp.append($("<td>").text(order.id));
            	temp.append($("<td>").text(order.size));
            	temp.append($("<td>").text(orderTopping.topping));
            	temp.append($("<td>").text(order.status));
            	temp.append($("</tr>"))
            	$("#ordertable").append(temp); 	
            	//turn on ack button if the order is baked one.
            	if(order.status === "Baked"){
            		$("#ackform").show();
            	}	
            }
        });
	});	
	});
}

// Let user click on one of two tabs, show its related contents
// Contents for both tabs are in the HTML after initial setup, 
// but one part is not displayed because of display:none in its CSS
// This implementation works for multiple two-tab setups because
// it works from the clicked-on element and finds the related
// content nearby. The related content needs to be a sibling of
// the clicked-on element's grandparent.
function setupTabs() {
    console.log("starting setupTabs");

    // Do this last. You may have a better approach, but here's one
    // way to do it. Also edit the html for better initial settings
    // of class active on these elements.
    // Find <span> elements inside <a>'s inside elements with class tabs
    // and process them as follows:  (there are four of them)
    // add a click listener to the element. When a click happens,
    // add class "active" to that element, and figure out this element's
    // parent's (the parent is an <a>) position among its siblings. If it
    // is the first child, the other <a> is its next sibling, and the other
    // <span> is the first child of that <a>. Similarly in the other case.
    // Remove class active from that other tab.
    // Now find the related tabContent element. It's the <span>'s
    // grandparent's next sibling, or sibling after that. Add class active
    // to the newly active one and remove it from the other one.
    $(".tabs a span").toArray().forEach(function (element) {
        let $element = $(element); //convert back to JQUERY object
        $element.on("click", function () {
            // set class=active on selected element
            element.classList.add("active");
            
            let childno = $element.parent().index();  // 0 or 1
           
            let other_tab = childno === 0 ? // The tab to make inactive
                    $element.parent().next().children()[0] :
                    $element.parent().prev().children()[0];
            other_tab.classList.remove("active");
            // Set class=active on the appropriate content element to make
            // it show up. e.g., if $element is <span> of first <a> in tabs,
            // make first content element active. That element is a sibling 
            // of <span>'s grandparent
            let $related_content = childno === 0 ? // up two levels and over...
                    $element.parent().parent().next() :
                    $element.parent().parent().next().next();
                    
            let $other_content = childno === 0 ? // up two levels and over...
                    $element.parent().parent().next().next() :
                    $element.parent().parent().next();

            $related_content.addClass("active");
            $other_content.removeClass("active");
            $("#order-message").text("");  // clean up old message
            return false; 
        });
    });
}
function displaySizesToppingsOnOrderForm() {
    console.log("displaySizesToppingsOnOrderForm");
    // find the element with id order-sizes, and loop through sizes,
    // setting up <input> elements for radio buttons for each size
    // and labels for them too
    // Then find the spot for meat toppings, and meatless ones
    // and for each create an <input> element for a checkbox
    // and a <label> for each
    
    //options of sizes
    sizes.forEach(function(size){
    	let input = 
                $("<input type='radio' name='pizza_size' required='required'>");
    	input.attr("value", size['size']);
    	let label = $("<label>");
    	label.text(size['size']);
    	$("#order-sizes").append(input);
    	$("#order-sizes").append(label);
    });
    let meatPos = $("#meats");
    let meatlessPos = $("#meatlesses");
    
    //options of kind of toppings
    toppings.forEach(function (top) {
    	var input = $("<input type='checkbox' name='pizza_topping'>");
    	input.attr("value", top['topping']);
    	var label = $("<label>");
    	label.text(top['topping']);
    	//1 = meat, 0 = meatless
    	if(top.is_meat == 1){
    		meatPos.append(input);
    		meatPos.append(label);
    	} else {
    		meatlessPos.append(input);
    		meatlessPos.append(label);
    	}
    });
    
    /* ####second way###########
 	for (let z = 0 ; z < sizes.length ; z++){
    	var radioBtn = $('<input type="radio" name="radioButton" />');
    	radioBtn.appendTo('#order-sizes');
    	$('#order-sizes').append($('<label>').text(sizes[z].size));
    }
    //showing the list of toppings in order form
  	toppings.forEach(function (top) {
    	if (top.is_meat == 1) {
   			//$('#meats').append($('<input>', { type: 'checkbox', id: 'cb' + top.id , value: top.topping}));
   			$('#meats').append($('<input>', { type: 'checkbox', class: 'chk'}));
   			$('#meats').append($('<label>', { 'for': 'cb'+ top.id, text: top.topping}));
        } else {
           //  $('<input>', { type: 'checkbox', id: 'cb' + top.id , value: top.topping }).appendTo($('#meatlesses'));
            $('<input>', { type: 'checkbox', class: 'chk' }).appendTo($('#meatlesses'));
   			 $('<label>', { 'for': 'cb' +top.id, text: top.topping}).appendTo($('#meatlesses'));
          }
    });
    */
}

function setupOrderForm() {
    console.log("setupOrderForm...");
    // find the orderform's submitbutton and put an event listener on it
    // When the click event comes in, figure out the sizeName from
    // the radio button and the toppings from the checkboxes
    // Complain if these are not specified, using order-message
    // Else, figure out the user_id of the selectedUser, and
    // compose an order, and post it. On success, report the
    // new order number to the user using order-message
    
    $("#orderform .submitbutton").on("click", function() {
    	var size = $("#order-sizes input:radio[name='pizza_size']:checked").val();
    	let user_recording = (users.filter(user => user.username === selectedUser))[0];//which user you choose
    	var toppings =  $('input[type=checkbox]:checked').toArray().map(elt => elt.value);
    	//creating an associate order
    	var order = {
    		"user_id": user_recording["id"], "size": size, "toppings": toppings, "day": 1, "status": 'Preparing'
    	};
    	//if this order is successful
    	if (order !== null){
    		console.log("Success");
    	}
    postOrder(order);
    return false; //when not execution
  });
}

// JQuery/Ajax: for use with $.when: return $.ajax object
//function getSizes0() {
  function getSizes() {
    return $.ajax({
        url: baseUrl + "/sizes",
        type: "GET",
        dataType: "json",
        //  headers: {"Content-type":"application/json"}, // needed
        success: function (result) {
            console.log("We did GET to /sizes");
            console.log(result);
            sizes = result;
        }
    });
}

function getToppings() {
    return $.ajax({
        url: baseUrl + "/toppings",
        type: "GET",
        dataType: "json",
        //  headers: {"Content-type":"application/json"}, // needed
        success: function (result) {
            console.log("We did GET to /toppings");
            console.log(result);
            toppings = result;
        }
    });
}

function getUsers() {
	return $.ajax({
        url: baseUrl + "/users",
        type: "GET",
        dataType: "json",
        success: function (result) {
            console.log("We did GET to /users");
            console.log(result);
            users = result;
        }
    });
}
function getOrders() {
	return $.ajax({
        url: baseUrl + "/orders",
        type: "GET",
        dataType: "json",
        success: function (result) {
            console.log("We did GET to /orders");
            console.log(result);
            orders = result;
        }
    });
}
function getOrderToppings() {
	return $.ajax({
        url: baseUrl + "/orderToppings",
        type: "GET",
        dataType: "json",
        success: function (result) {
            console.log("We did GET to /orderToppings");
            console.log(result);
            orderToppings = result;
        }
    });
}
function updateOrder(order) {
    return $.ajax({
        url: baseUrl + "/order/" + order.id,
        type: "PUT",
        dataType: "json",
        data: JSON.stringify(order),
        headers: {"Content-type": "application/json"}, // needed
        success: function (result) {
            console.log("We did PUT to /orders/" + order.id);
            console.log("data: " + JSON.stringify(order))
            console.log(result);
        }
    });
}
function postOrder(order) {
	$.ajax({
        url: baseUrl + "/orders",
        type: "POST",
        dataType: "json",
        data: JSON.stringify(order),
        headers: {"Content-type": "application/json"}, // needed
        success: function (result) {
            console.log("We did POST to /orders");
            console.log(result);
            //onSuccess(result); // do caller-specified action
        }
    });
}
function refreshData(thenFn) {
    // wait until all promises from fetches "resolve", i.e., finish fetching
    Promise.all([getSizes(), getToppings(), getOrderToppings(), getUsers(), getOrders()]).then(thenFn);
    // JQuery way: wait for all these Ajax requests to finish
    // $.when(getSizes(), getToppings(), getUsers(), getOrders()).done(function (a1, a2, a3, a4) {
    //     thenFn();
    //});
}

console.log("starting...");
refreshData(main);
