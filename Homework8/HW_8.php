<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.js" type="text/javascript"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>


<!-- Validation -->
<script>
var result;
var paginationpagenumber;
var numberofPages;
var start=1;
var end=5;
var displayeditem=0;


function removeDivision() {
  $("#dynamicContent").empty();
  $("#mainHeading").empty();
  $("#pagination").empty();
}

function countItems(val1,val2){
		if(val1>val2){
			return val2;
		}
		else{
			return val1;
		}

	}

function moveToPage(elt) {
paginationpagenumber = elt;
ajaxCallFunction();
return false;
}

function decreasePage() {
  if(paginationpagenumber == start && start>=1) {
    end = end-5;
    start=start-5;
    paginationpagenumber=end;
  }
	else{
    paginationpagenumber--;
	}
  ajaxCallFunction();
return false;
}

function increasePage() {
  if(paginationpagenumber==end){
  if(paginationpagenumber<(numberofPages-5)){
    start=start+5;
    end=end+5;
    paginationpagenumber=start;
  }
  else{
    end=numberofPages;
    start=end-5;
  }
  }
  else{
    paginationpagenumber++;
  }
  ajaxCallFunction();
return false;
}

function parseData(){

  $("#dynamicContent").empty();
  $("#pagination").empty();
  $("#mainHeading").html("<h3>"+((paginationpagenumber-1)*parseInt(result["itemCount"])+1)+"-"+countItems((paginationpagenumber)*parseInt(result["itemCount"]),result['resultCount'])+" items out of "+result['resultCount']+"</h3>");

	for(var i=0;i<parseInt(result["itemCount"]);i++)
	{
    var counter = 0;
if(displayeditem < parseInt(result["resultCount"])){
  counter++;
	var createClone = $("#template").clone().attr('id',i);
  createClone.find("#galleryImage").attr('id',"#galleryImage" + i).attr("src",result["item"+i]["basicInfo"]["galleryURL"]);
	createClone.find(".media-heading-link").html(result["item"+i]["basicInfo"]["title"]);
	createClone.find(".media-heading-link").attr("href",result["item"+i]["basicInfo"]["viewItemURL"]);
	createClone.find(".price").html("Price:$" + result["item"+i]["basicInfo"]["convertedCurrentPrice"]);
	if(result["item"+i]["basicInfo"]["shippingServiceCost"]=="0.0" || result["item"+i]["basicInfo"]["shippingServiceCost"]==""){
	createClone.find(".shipping").html("(Free Shipping)");
	}
	else{
	createClone.find(".shipping").html("");
	}
	createClone.find(".location").html("<i>Location: "+result["item"+i]["basicInfo"]["location"]+"</i>");
	if(result["item"+i]["basicInfo"]["topRatedListing"]=="true"){
	createClone.find(".rating").html("<img src=\"http://cs-server.usc.edu:45678/hw/hw8/itemTopRated.jpg\" style=\"height:35px;width:45px\"></img>");
	}
	createClone.find(".fb").attr("src","http://cs-server.usc.edu:45678/hw/hw8/fb.png")+"</div>";
  createClone.css("display","block");
  $("#dynamicContent").append(createClone);
  displayeditem = displayeditem+counter;
}
	}

  // Pagination Starts Here
  $("#pagination").append("<ul class=\"pagination pagination-sm\" id=\"myPager\">");

  if(paginationpagenumber!=1) {
    $("#myPager").append('<li id=\"decrement\" ><a onclick=\"return decreasePage();\">&laquo</a></li>');
  }
  numberofPages = Math.ceil((result["resultCount"]/result["itemCount"]));
  for(i=start;i<=end;i++){
    if(i<=numberofPages){
	if(i==paginationpagenumber)
	{
    $("#myPager").append("<li class=\"active\" id='+(i)+'><a>"+i+"</a></li>");
	}
	else{
    $("#myPager").append("<li><a onclick=\"return moveToPage("+i+");\">"+i+"</a></li>");
	}
  }
}

  if(paginationpagenumber<numberofPages) {
    $("#myPager").append('<li id=\"decrement\" ><a onclick=\"return increasePage();\">&raquo</a></li>');
  }

  $("#pagination").append("</ul>");


  //End

  }
//End

var form;

//Ajax Call
function ajaxCallFunction() {
  $.ajax({
     url: "http://salonipriya.elasticbeanstalk.com",
     data: (form+"&pageNum="+ JSON.stringify(paginationpagenumber)),
     type: 'GET',
     success: function(output) {
     result = jQuery.parseJSON(output);
     if(result["ack"] == "No Results Found"){
       $("#dynamicContent").empty();
       $("#pagination").empty();
       $("#mainHeading").empty();
       $("#mainHeading").html("<h3>No Results Found</h3>");
     }
     else{
       parseData(paginationpagenumber);
     }
   },
   error: function(){
     alert("JSON parsing error");
   }
  });
  return;
}


//jQuery Validation
$.validator.setDefaults({
  submitHandler: function() {
    form = $('#ebay-form').serialize();
    paginationpagenumber = 1;
    displayedItem=0;
    start=1;
    end=5;
    ajaxCallFunction();
  }
});

$().ready(function() {

  // validate signup form on keyup and submit
  $("#ebay-form").validate({
    errorClass: "my-error-class",
    rules: {
      inputKeywords: "required",
      inputfrom: {
        number: true,
        min: 0,
      },
      inputto: {
        number: true,
        checkValue: true,
      },
      maxhandlingtime: {
        digits: true,
        min: 1,
      },
    },
    messages: {
      inputKeywords: "Please enter a key word",
      inputfrom: {
        number: "Price should be a valid number",
        min: "Minimum price cannot be below 0",
      },
      inputto: {
        number: "Price should be a valid number",
        min: "Maximum price cannot be less than minimum price or below 0",
      },
      maxhandlingtime: {
        digits: "Max handling time should be a valid digit",
        min: "Max handling time should be greater than or equal to 1",
      }
    },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
          },
           errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
  });
});

//Validation to check if the value entered is greater than Min Price
$.validator.addMethod("checkValue", function( value, element, param ) {

        var val_inputfrom = parseInt($("#inputfrom").val());

        return this.optional(element)
            || (parseInt(value) >= val_inputfrom);
    },"Maximum price cannot be less than minimum price or below 0");
//End


function displayModal(elt){
  // Traverse up until root hit or DIV with ID found
      while (elt && (elt.tagName != "DIV" || !elt.id))
          elt = elt.parentNode;
      if (elt)
      {// elt.id will be parent id
        $("#myModalLabel").text(result["item"+elt.id]["basicInfo"]["title"]);
        if(result["item"+elt.id]["basicInfo"]["pictureURLSuperSize"] == "")
        {
          result["item"+elt.id]["basicInfo"]["pictureURLSuperSize"] = result["item"+elt.id]["basicInfo"]["galleryURL"];
        }
          $("#bigImage").attr('src',result["item"+elt.id]["basicInfo"]["pictureURLSuperSize"]);
      }
}

function displayTabs(elt) {
  basicInfo(elt);
  // Traverse up until root hit or DIV with ID found
      while (elt && (elt.tagName != "DIV" || !elt.id))
          elt = elt.parentNode;
      if (elt)
      {// elt.id will be parent id
        $("#"+elt.id).find(".tabs").toggle();
      }
}

function basicInfo(elt) {
  $(elt).parent().addClass("active");
  var current = elt;
  // Traverse up until root hit or DIV with ID found
      while (elt && (elt.tagName != "DIV" || !elt.id))
          elt = elt.parentNode;

      if (elt)
      {// elt.id will be parent id
        if(result["item"+elt.id]["basicInfo"]["categoryName"] === "") {
          result["item"+elt.id]["basicInfo"]["categoryName"] = "N/A"
        }
        if(result["item"+elt.id]["basicInfo"]["conditionDisplayName"] === "") {
          result["item"+elt.id]["basicInfo"]["conditionDisplayName"] = "N/A"
        }
        if(result["item"+elt.id]["basicInfo"]["listingType"] === "") {
          result["item"+elt.id]["basicInfo"]["listingType"] = "N/A"
        }
        $("#"+elt.id).find(".category").html(result["item"+elt.id]["basicInfo"]["categoryName"]);
        $("#"+elt.id).find(".condition").html(result["item"+elt.id]["basicInfo"]["conditionDisplayName"]);
        $("#"+elt.id).find(".buyingformat").html(result["item"+elt.id]["basicInfo"]["listingType"]);
        $("#"+elt.id).find("#sellerInfo").hide();
        $("#"+elt.id).find("#shippingInfo").hide();
        $("#"+elt.id).find("#basicInfo").addClass('active');
        $("#"+elt.id).find("#basicInfo").show();
      }
}

function sellerInfo(elt) {
  var current = elt;
  // Traverse up until root hit or DIV with ID found
      while (elt && (elt.tagName != "DIV" || !elt.id))
          elt = elt.parentNode;
          $("#"+elt.id).find('li').removeClass('active');
          $(current).parent().addClass('active');
      if (elt)
      {// elt.id will be parent id

        if(result["item"+elt.id]["sellerInfo"]["sellerUserName"] === "") {
          result["item"+elt.id]["sellerInfo"]["sellerUserName"] = "N/A"
        }
        if(result["item"+elt.id]["sellerInfo"]["feedbackScore"] === "") {
          result["item"+elt.id]["sellerInfo"]["feedbackScore"] = "N/A"
        }
        if(result["item"+elt.id]["sellerInfo"]["positiveFeedbackPercent"] === "") {
          result["item"+elt.id]["sellerInfo"]["positiveFeedbackPercent"] = "N/A"
        }
        if(result["item"+elt.id]["sellerInfo"]["feedbackRatingStar"] === "") {
          result["item"+elt.id]["sellerInfo"]["feedbackRatingStar"] = "N/A"
        }
        $("#"+elt.id).find("#sellerInfo").find(".username").html(result["item"+elt.id]["sellerInfo"]["sellerUserName"]);
        $("#"+elt.id).find("#sellerInfo").find(".feedbackscore").html(result["item"+elt.id]["sellerInfo"]["feedbackScore"]);
        $("#"+elt.id).find("#sellerInfo").find(".positivefeedback").html(result["item"+elt.id]["sellerInfo"]["positiveFeedbackPercent"]);
        $("#"+elt.id).find("#sellerInfo").find(".feedbackrating").html(result["item"+elt.id]["sellerInfo"]["feedbackRatingStar"]);
        if(result["item"+elt.id]["sellerInfo"]["topRatedSeller"]=="true") {
          $("#"+elt.id).find("#sellerInfo").find(".toprated").html("<span class=\"glyphicon glyphicon-ok\" style=\"color:green\"></span>");
        }
        else {
          $("#"+elt.id).find("#sellerInfo").find(".toprated").html("<span class=\"glyphicon glyphicon-remove\" style=\"color:red\"></span>");
        }
        if(result["item"+elt.id]["sellerInfo"]["sellerStoreName"] === "") {
          $("#"+elt.id).find("#sellerInfo").find(".store").html("N/A");
        }
        else {
        $("#"+elt.id).find("#sellerInfo").find(".store").html("<a class=\"storeLink\" target=\"_blank\" style=\"text-decoration:none;\">"+result["item"+elt.id]["sellerInfo"]["sellerStoreName"]+"</a>");
        $("#"+elt.id).find("#sellerInfo").find(".storeLink").attr('href',result["item"+elt.id]["sellerInfo"]["sellerStoreURL"]);
          }
        $("#"+elt.id).find("#basicInfo").hide();
        $("#"+elt.id).find("#basicInfo").removeClass('active');
        $("#"+elt.id).find("#shippingInfo").hide();
        $("#"+elt.id).find("#sellerInfo").addClass('active');
        $("#"+elt.id).find("#sellerInfo").show();
      }
}

function shippingInfo(elt) {
  var current = elt;

  // Traverse up until root hit or DIV with ID found
      while (elt && (elt.tagName != "DIV" || !elt.id))
          elt = elt.parentNode;
          $("#"+elt.id).find('li').removeClass('active');
          $(current).parent().addClass('active');
      if (elt)
      {// elt.id will be parent id

        if(result["item"+elt.id]["shippingInfo"]["shippingType"] === "") {
          result["item"+elt.id]["shippingInfo"]["shippingType"] = "N/A"
        }
        if(result["item"+elt.id]["shippingInfo"]["handlingTime"] === "") {
          result["item"+elt.id]["shippingInfo"]["handlingTime"] = "N/A"
        }
        if(result["item"+elt.id]["shippingInfo"]["shipToLocations"] === "") {
          result["item"+elt.id]["shippingInfo"]["shipToLocations"] = "N/A"
        }

        $("#"+elt.id).find("#shippingInfo").find(".shippingtype").html(result["item"+elt.id]["shippingInfo"]["shippingType"]);
        $("#"+elt.id).find("#shippingInfo").find(".handlingtime").html(result["item"+elt.id]["shippingInfo"]["handlingTime"]+"(days)");
        $("#"+elt.id).find("#shippingInfo").find(".shippinglocation").html(result["item"+elt.id]["shippingInfo"]["shipToLocations"]);
        if(result["item"+elt.id]["shippingInfo"]["expeditedShipping"]=="true") {
          $("#"+elt.id).find("#shippingInfo").find(".expeditedshipping").html("<span class=\"glyphicon glyphicon-ok\" style=\"color:green\"></span>");
        }
        else {
          $("#"+elt.id).find("#shippingInfo").find(".expeditedshipping").html("<span class=\"glyphicon glyphicon-remove\" style=\"color:red\"></span>");
        }

        if(result["item"+elt.id]["shippingInfo"]["oneDayShippingAvailable"]=="true") {
          $("#"+elt.id).find("#shippingInfo").find(".onedayshipping").html("<span class=\"glyphicon glyphicon-ok\" style=\"color:green\"></span>");
        }
        else {
          $("#"+elt.id).find("#shippingInfo").find(".onedayshipping").html("<span class=\"glyphicon glyphicon-remove\" style=\"color:red\"></span>");
        }
        if(result["item"+elt.id]["shippingInfo"]["returnsAccepted"]=="true") {
          $("#"+elt.id).find("#shippingInfo").find(".returnsaccepted").html("<span class=\"glyphicon glyphicon-ok\" style=\"color:green\"></span>");
        }
        else {
          $("#"+elt.id).find("#shippingInfo").find(".returnsaccepted").html("<span class=\"glyphicon glyphicon-remove\" style=\"color:red\"></span>");
        }
        $("#"+elt.id).find("#basicInfo").hide();
        $("#"+elt.id).find("#sellerInfo").hide();
        $("#"+elt.id).find("#shippingInfo").addClass('active');
        $("#"+elt.id).find("#shippingInfo").show();
      }
}

// For Facebook
window.fbAsyncInit = function() {
    FB.init({
      appId      : '793618027418987',
      xfbml      : true,
      version    : 'v2.3'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

function facebookShare(elt) {
  while (elt && (elt.tagName != "DIV" || !elt.id))
      elt = elt.parentNode;

      FB.login(function(response)
      {
          if (response.authResponse)
          {
  		   FB.ui({

  		   method: 'feed',
  		   name: result["item"+elt.id]["basicInfo"]["title"],
  		   link: result["item"+elt.id]["basicInfo"]["viewItemURL"],
  		   description: "Price: $"+result["item"+elt.id]["basicInfo"]["convertedCurrentPrice"]+", Location:"+result["item"+elt.id]["basicInfo"]["location"],
  		   picture: result["item"+elt.id]["basicInfo"]["galleryURL"],
  		   caption: "Search information from eBay.com",
  		   display: 'popup'

          },function(response){ if(response && response.post_id){alert("Posted Successfully");} });
          }
          else
          {
              alert('Not logged in');
          }
      }, { scope : 'publish_stream' });

}

//End

function paginate(){
  paginationpagenumber = paginationpagenumber+1;
  ajaxCallFunction();
  alert("success");
}

</script>

<style type="text/css">
hr {
  border: 1.5px solid lightgray;
}
.my-error-class {
    color:red;
}
</style>
<div id="fb-root"></div>
<script>
(function() {
    var e = document.createElement('script');
    // replacing with an older version until FB fixes the cancel-login bug
    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
    //e.src = 'scripts/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
}());
</script>
</head>
<body>

  <div class="container-fluid" >
    <div style="margin-top:30px;">
      <div class="col-xs-12">
        <div class="form-group">
          <div class="col-md-1">
          </div>
          <div class="col-md-6">
            <label><img src="http://cs-server.usc.edu:45678/hw/hw6/ebay.jpg" height="50px;" width="100px;"><span style="font-size:150%;">Shopping</span></label>
          </div>
        </div>
      </div>
    </div>
    <form class="form-horizontal" role="form" method="GET" action="" id="ebay-form">

      <!-- Keywords -->
      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <label class="col-md-3 control-label">Key words:<span style="color:#B55F5E"> *</span></label>
            <div class="col-md-6">
              <input type="text" class="form-control" name="inputKeywords" id="inputKeywords" placeholder="Enter keyword">
            </div>
          </div>
        </div>
      </div>

     <!-- Price Range -->
      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <label for="inputPriceRange" class="col-md-3 control-label">Price range:</label>
            <div class="col-md-6">
              <div class="col-md-6 col-xs-12"><input type="text" class="form-control" name="inputfrom" id="inputfrom" placeholder="from ($)"></div>
              <div class="col-md-6 col-xs-12"><input type="text" class="form-control" name="inputto" id="inputto" placeholder="to ($)"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Condition -->
      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <label class="col-md-3 control-label">Condition:</label>
            <div class="col-md-6">
              <div class="checkbox">
              <label style="margin-right:10px;"><input type="checkbox" name="conditioncheckboxes[]" value="new" id="inputNew">New</label>
              <label style="margin-right:10px;"><input type="checkbox" name="conditioncheckboxes[]" value="used" id="inputUsed">Used</label>
              <label style="margin-right:10px;"><input type="checkbox" name="conditioncheckboxes[]" value="verygood" id="inputVeryGood">Very Good</label>
              <label style="margin-right:10px;"><input type="checkbox" name="conditioncheckboxes[]" value="good" id="inputGood">Good</label>
              <label style="margin-right:10px;"><input type="checkbox" name="conditioncheckboxes[]" value="acceptable" id="inputAcceptable">Acceptable</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Buying Formats -->
      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <label class="col-md-3 control-label">Buying formats:</label>
            <div class="col-md-6">
              <div class="checkbox">
              <label style="margin-right:10px;"><input type="checkbox" name="buyingformatcheckboxes[]" value="buyitnow">Buy It Now</label>
              <label style="margin-right:10px;"><input type="checkbox" name="buyingformatcheckboxes[]" value="auction">Auction</label>
              <label style="margin-right:10px;"><input type="checkbox" name="buyingformatcheckboxes[]" value="classifiedads">Classified Ads</label>
              </div>
          </div>
        </div>
      </div>
    </div>

      <!-- Seller -->
      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <label class="col-md-3 control-label">Seller:</label>
            <div class="col-md-6">
              <div class="checkbox">
              <label><input type="checkbox" name="inputReturnAccepted" id="inputReturnAccepted">Return Accepted</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Shipping -->
      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <label class="col-md-3 control-label">Shipping:</label>
            <div class="col-md-6">
              <div class="checkbox" style="margin-bottom:10px;">
              <label style="margin-right:10px;"><input type="checkbox" name="inputFreeShipping" id="inputFreeShipping">Free Shipping</label>
              <label style="margin-right:10px;"><input type="checkbox" name="inputExpeditedShipping" id="inputExpeditedShipping">Expedited Shipping</label>
              </div>
              <label for="inputmaxhandlingtime" class="col-md-3 control-label"></label>
              <input type="text" class="form-control" name="maxhandlingtime" id="maxhandlingtime" placeholder="Max Handling time(days)">
            </div>
          </div>
        </div>
      </div>

      <!-- Sort By -->
      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <label class="col-md-3 control-label">Sort by:</label>
            <div class="col-md-6">
              <select class="form-control" name="sortby" id="sortby">
               <option value="BestMatch">Best Match</option>
               <option value="CurrentPriceHighest">Price: highest first</option>
               <option value="PricePlusShippingHighest">Price + Shipping: highest first</option>
               <option value="PricePlusShippingLowest">Price + Shipping: lowest first</option>
             </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Results per page -->
      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <label class="col-md-3 control-label">Results per page:</label>
            <div class="col-md-6">
              <select class="form-control" name="resultsperpage" id="resultsperpage">
               <option>5</option>
               <option>10</option>
             </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Search-Clear Button -->
      <div class="row">
        <div class="col-xs-12">
          <div class="form-actions">
          <div class="col-md-9 col-xs-6">
            <div class="pull-right"><button type="submit" class="btn btn-primary submit">Search</button></div>
            <div class="pull-right" style="margin-right:10px;"><button type="reset" class="btn btn-default" onclick="removeDivision();">Clear</button></div>
          </div>
          </div>
          </div>
        </div>

        <!-- Horizontal Line -->
        <div class="row">
          <div class="col-xs-12">
            <div class="form-actions">
              <div class="col-md-2"></div>
            <div class="col-md-8">
            <div><hr/><div>
            </div>
            </div>
            </div>
          </div>
        </div>

    </form>
  </div><!-- /.container -->


  <div class="col-xs-12">
      <div class="col-md-2">
      </div>
      <div class="col-md-8">
        <div id="mainHeading"></div>
      </div>
  </div>



<!-- Output Div -->
<div class="media" id="template"  style="display:none;margin-bottom:10px;">
  <a data-toggle="modal" data-target=".bs-example-modal-lg" onclick="displayModal(this)"><img class="pull-left media-object"  src="" style="width:55px;height:55px;" id="galleryImage" class="imagehere" alt="image"></img>

   <div class="media-body">
     <div style="margin-left:10px;"><h4 class="media-heading" class="title"><a class="media-heading-link" style="text-decoration:none;" target="_blank"></a></h4></div>
   <b><div style="display:inline;margin-left:10px;" class="price"></div></b>
   <div style="padding-left:2px; display:inline;" class="shipping"></div>
   <div style="padding-left:10px; display:inline;" class="location"></div>
   <div style="padding-left:5px; display:inline;" class="rating"></div>
   <div style="padding-left:8px; display:inline;" class="details">
     <a class="view_details" style="text-decoration:none;" onclick="displayTabs(this);">View Details</a>
   </div>
   <div style="padding-left:10px; display:inline;" class="fb_div"><a onclick="facebookShare(this)"><img class="fb" style="height:20px;width:20px;"></a></div>

  <div class="tabs" style="display:none;margin-left:10px;">
  <ul class="nav nav-tabs">
   <li class="active"><a data-toggle="tab"  href="javascript:void(0);" onclick="basicInfo(this);">BasicInfo</a></li>
   <li><a data-toggle="tab"  href="javascript:void(0);" onclick="sellerInfo(this);">Seller Info</a></li>
   <li><a data-toggle="tab"  href="javascript:void(0);" onclick="shippingInfo(this);">Shipping Info</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
        <div id="basicInfo" class="" style="display:none;">
          <table style="border:none;">
            <tr>
              <td><b>Category Name</b></td>
              <td style="padding-left:20px;"><span class="category"></span></td>
            </tr>
            <tr>
              <td><b>Condition</b></td>
              <td style="padding-left:20px;"><span class="condition"></span></td>
            </tr>
            <tr>
              <td><b>Buying Format</b></td>
              <td style="padding-left:20px;"><span class="buyingformat"></span></td>
            </tr>
          </table>
      </div>

        <div id="sellerInfo" class="" style="display:none;">
          <table style="border:none;">
            <tr>
              <td><b>User name</b></td>
              <td style="padding-left:20px;"><span  class="username"></span></td>
            </tr>
            <tr>
              <td><b>Feeback Score</b></td>
              <td style="padding-left:20px;"><span  class="feedbackscore"></span></td>
            </tr>
            <tr>
              <td><b>Positive feedback</b></td>
              <td style="padding-left:20px;"><span  class="positivefeedback"></span></td>
            </tr>
              <tr>
                <td><b>Feedack rating</b></td>
                <td style="padding-left:20px;"><span  class="feedbackrating"></span></td>
              </tr>
              <tr>
                <td><b>Top rated</b></td>
                <td style="padding-left:20px;"><span  class="toprated"></span></td>
              </tr>
              <tr>
                <td><b>Store</b></td>
                <td style="padding-left:20px;"><span class="store"></span></td>
              </tr>
            </table>
        </div>

        <div id="shippingInfo" class="" style="display:none;">
          <table style="border:none;">
            <tr>
              <td><b >Shipping Type</b></td>
              <td style="padding-left:20px;"><span  class="shippingtype"></span></td>
            </tr>
            <tr>
              <td><b>Handling time</b></td>
              <td style="padding-left:20px;"><span  class="handlingtime"></span></td>
            </tr>
            <tr>
              <td><b>Shipping locations</b></td>
              <td style="padding-left:20px;"><span class="shippinglocation"></span></td>
            </tr>
              <tr>
                <td><b>Expedited Shipping</b></td>
                <td style="padding-left:20px;"><span class="expeditedshipping"></span></td>
              </tr>
              <tr>
                <td><b>One Day Shipping</b></td>
                <td style="padding-left:20px;"><span class="onedayshipping"></span></td>
              </tr>
              <tr>
                <td><b>Returns accepted</b></td>
                <td style="padding-left:20px;"><span class="returnsaccepted"></span></td>
              </tr>
            </table>
        </div>
  </div>
</div>
   </div>

</div>

<div class="container" class="col-xs-12">

  <!-- Spacing for Desktop View -->
  <div class="col-md-1">
  </div>
  <!-- End  -->

  <div id="dynamicContent" class="col-md-10">
  </div>

  <!-- Spacing for Desktop View -->

  <div class="col-xs-12">
      <div class="col-md-1">
      </div>
      <div class="col-md-8">
        <div id="pagination"></div>
      </div>
  </div>

  <!-- End  -->
  <!-- Modal -->
       <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-bottom:20px;background-color:black;opacity: 0.9;" >
         <div class="modal-dialog modal-lg">
           <div class="modal-content">
             <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h4 class="modal-title" id="myModalLabel"></h4>
             </div>
           <div class="modal-body">
             <center><img id="bigImage" class="img-responsive"></img></center>
           </div>
         </div><!-- /.modal-content -->
       </div><!-- /.modal-dialog -->
     </div><!-- /.modal -->

</div>
<noscript>
</body>
</html>
