
<html>
<head>
<script>
function checkform(form) {
  var x = form.key_words.value;
    if ( x== null || x == "") {
        alert("Please enter values for Key Words");
        return false;
    }

    if(isNaN(form.price_from.value))
    {
      alert("Enter numeric value for minimum price");
      return false;
    }
    if(isNaN(form.price_to.value))
    {
      alert("Enter numeric value for maximum price");
      return false;
    }


  var minimumprice = parseInt(form.price_from.value);
  var maxprice = parseInt(form.price_to.value);
  if(maxprice < minimumprice)
  {
    alert ("Min price should be lesser than Max price");
    return false;
  }

  var maxhandlingtime = form.maxhandlingtime.value;

  if(maxhandlingtime == "" || maxhandlingtime ==null)
  {
  }
  else
  {
    if(maxhandlingtime<1)
    {
      alert("Max handling time cannot be less than 1");
      return false;
    }
  }
}
function clearForm(clearingForm) {
  var frm_elements = clearingForm.elements;
  for (i = 0; i < frm_elements.length; i++)
{
    field_type = frm_elements[i].type.toLowerCase();
    switch (field_type)
    {
    case "text":
      frm_elements[i].value = "";
    case "checkbox":
        if (frm_elements[i].checked)
        {
            frm_elements[i].checked = false;
        }
        break;
    case "select-one":
        frm_elements[i].selectedIndex = 0;
        break;
    default:
        break;
    }
}
return false;
}


</script>
</head>
<body>

  <!--Image-->
  <!--The outer Division with image and inner_division-->
  <div style="border:solid 1px black;margin:200px;margin-top:30px;padding-top:15px;">
    <table align="center">
      <tr >
        <td rowspan="2" ><img src="http://cs-server.usc.edu:45678/hw/hw6/ebay.jpg" height="100px;" width="100px;"></td>
        <td><b style="font-size:30px;">Shopping<b></td>
      </tr>
    </table>
  <!--End of Image-->

  <!--The inner division starts here-->
    <div style="border:solid 2px black;margin:30px;padding:15px;padding-bottom:50px;">


    <!--Form starts here-->
  <form name="myForm" method="GET" action="" onsubmit="return checkform(this)">
      <b>Key Words*:</b>
    <div style="padding-left:180px;margin-top:-25px;">
      <input type="text" name="key_words" value="<?php echo isset($_GET['key_words']) ? $_GET['key_words'] : '' ?>" style="width:95%;height:25px;">
    </div>
      <hr/>
      <b>Price Range:</b>
      <div style="padding-left:180px;margin-top:-25px;">
        from $
        <input type="text" name="price_from" value="<?php echo isset($_GET['price_from']) ? $_GET['price_from'] : '' ?>" style="width:10%;height:25px;"></input>
        to $
        <input type="text" name="price_to" value="<?php echo isset($_GET['price_to']) ? $_GET['price_to'] : '' ?>" style="width:10%;height:25px;"></input>
        </div>
      <hr/>
      <b>Condition:</b>
        <div style="padding-left:180px;margin-top:-25px;" >
          <input type="checkbox" name="condition[1000]" id="1000" style="padding-left:180px;margin-top:-25px;height:20px;" value="1000"
          <?php if(isset($_GET['condition']['1000'])) {if ($_GET['condition']['1000'] === "1000") {echo 'checked="checked"';}}?>>&nbsp;New
          <input type="checkbox" name="condition[3000]" id="3000" style="padding-left:180px;margin-top:-25px;height:20px;" value="3000"
          <?php if(isset($_GET['condition']['3000'])) {if($_GET['condition']['3000'] === "3000") {echo 'checked="checked"';}}?>>&nbsp;Used
          <input type="checkbox" name="condition[4000]" id="4000" style="padding-left:180px;margin-top:-25px;height:20px;" value="4000"
          <?php if(isset($_GET['condition']['4000'])) {if($_GET['condition']['4000'] === "4000") {echo 'checked="checked"';}}?>>&nbsp;Very Good
          <input type="checkbox" name="condition[5000]" id="5000" style="padding-left:180px;margin-top:-25px;height:20px;" value="5000"
          <?php if(isset($_GET['condition']['5000'])) {if($_GET['condition']['5000'] === "5000") {echo 'checked="checked"';}}?>>&nbsp;Good
          <input type="checkbox" name="condition[6000]" id="6000" style="padding-left:180px;margin-top:-25px;height:20px;" value="6000"
          <?php if(isset($_GET['condition']['6000'])) {if($_GET['condition']['6000'] === "6000") {echo 'checked="checked"';}}?>>&nbsp;Acceptable
        </div>
      <hr/>
      <b>Buying formats:</b>
      <div style="padding-left:180px;margin-top:-25px;">
        <input type="checkbox" name="buyingformat[FixedPrice]"  id="FixedPrice" style="padding-left:180px;margin-top:-25px;height:20px;" value="FixedPrice"
        <?php if(isset($_GET['buyingformat']['FixedPrice'])) {if($_GET['buyingformat']['FixedPrice'] === "FixedPrice") {echo 'checked="checked"';}}?>>&nbsp;Buy It Now
        <input type="checkbox" name="buyingformat[Auction]"  id="Auction" style="padding-left:180px;margin-top:-25px;height:20px;" value="Auction"
        <?php if(isset($_GET['buyingformat']['Auction'])) {if($_GET['buyingformat']['Auction'] === "Auction") {echo 'checked="checked"';}}?>>&nbsp;Auction
        <input type="checkbox" name="buyingformat[Classified]" id="Classified" style="padding-left:180px;margin-top:-25px;height:20px;" value="Classified"
        <?php if(isset($_GET['buyingformat']['Classified'])) {if($_GET['buyingformat']['Classified'] === "Classified") {echo 'checked="checked"';}}?>>&nbsp;Classified Ads
      </div>
      <hr/>
      <b>Seller</b>
        <div style="padding-left:180px;margin-top:-25px;">
          <input type="checkbox" style="padding-left:180px;margin-top:-25px;height:20px;"name="seller" value="NA" <?php if(isset($_GET['seller'])) echo 'checked="checked"'; ?> >&nbsp;Returned Accepted
        </div>
      <hr/>
      <b>Shipping:</b>
        <div style="padding-left:180px;margin-top:-25px;">
          <input type="checkbox" name="freeshipping" style="padding-left:180px;margin-top:-25px;height:20px;" value="NA" <?php if(isset($_GET['freeshipping'])) echo 'checked="checked"'; ?>>&nbsp;Free Shipping<br/>
          <input type="checkbox" name="expeditedshippingavailable" style="padding-left:180px;margin-top:-25px;height:20px;" value="NA" <?php if(isset($_GET['expeditedshippingavailable'])) echo 'checked="checked"'; ?>>&nbsp;Expedited Shipping available<br/>
          Max handling time(days):&nbsp; <input type="text" name="maxhandlingtime" value="<?php echo isset($_GET['maxhandlingtime']) ? $_GET['maxhandlingtime'] : '' ?>" style="width:10%;height:25px;">
        </div>
      <hr/>
      <b>Sort by:</b>
      <div style="padding-left:180px;margin-top:-25px;">
        <select name="sortby">
        <option value="BestMatch" <?php if (isset($_GET['sortby']) && $_GET['sortby'] == 'BestMatch') echo 'selected="selected"'; ?>>Best Match</option>
        <option value="CurrentPriceHighest" <?php if (isset($_GET['sortby']) && $_GET['sortby'] === 'CurrentPriceHighest') echo 'selected="selected"'; ?>>Price: highest first</option>
        <option value="PricePlusShippingHighest" <?php if (isset($_GET['sortby']) && $_GET['sortby'] === 'PricePlusShippingHighest') echo 'selected="selected"'; ?>>Price + Shipping: highest first</option>
        <option value="PricePlusShippingLowest" <?php if (isset($_GET['sortby']) && $_GET['sortby'] === 'PricePlusShippingLowest') echo 'selected="selected"'; ?>>Price + Shipping: lowest first</option>
        </select>
      </div>
      <hr/>
      <b>Results Per Page:</b>
        <div style="padding-left:180px;margin-top:-25px;">
          <select name="resultperpage">
          <option value="5" <?php if (isset($_GET['resultperpage']) && $_GET['resultperpage'] === '5') echo 'selected="selected"'; ?>>5</option>
          <option value="10" <?php if (isset($_GET['resultperpage']) && $_GET['resultperpage'] === '10') echo 'selected="selected"'; ?>>10</option>
          <option value="15" <?php if (isset($_GET['resultperpage']) && $_GET['resultperpage'] === '15') echo 'selected="selected"'; ?>>15</option>
          <option value="20" <?php if (isset($_GET['resultperpage']) && $_GET['resultperpage'] === '20') echo 'selected="selected"'; ?>>20</option>
          </select>
        </div>
      <div style="float:right;padding-top:5px;padding-right:30px;">
          <input type="submit" value="Search" name="search" style="width:80px;">
          <button onclick="return clearForm(this.form);" style="width:80px;">clear</button>
      </div>
  </form>
  <!--End of form-->

  </div>
  <!--Inner division end here-->
  </div>
  <!--Outer division end here-->

    <?php if(isset($_GET["search"])):


    $ebayurl="http://svcs.ebay.com/services/search/FindingService/v1?siteid=0?OPERATION-NAME=findItemsAdvanced&SERVICE-VERSION=1.0.0&SECURITY-APPNAME=Universi-67b4-4a4e-b184-7ff2bd4b59e2&GLOBAL-ID=EBAY-US&keywords=".$_GET['key_words']."&paginationInput.entriesPerPage=".$_GET['resultperpage']."&sortOrder=".$_GET['sortby']."";

    $i =0;

    /*MinPrice Filter*/
    if(!empty($_GET['price_from']))
    {
    $ebayurl .= "&itemFilter[$i].name=MinPrice&itemFilter[$i].value=".$_GET['price_from'];
    $i++;
    }

    /*MaxPrice Filter*/
    if(!empty($_GET['price_to']))
    {
    $ebayurl .= "&itemFilter[$i].name=MaxPrice&itemFilter[$i].value=".$_GET['price_to'];
    $i++;
    }

    /*Condition Filter*/
    if(!empty($_GET['condition'])) {
    $j = 0;
    $ebayurl .= "&itemFilter[$i].name=Condition";
    foreach($_GET['condition'] as $check) {
      $ebayurl .= "&itemFilter[$i].value[$j]=".$check;
      $j++;
    }
    $i++;
    }

    /*BuyingFormat Filter*/
    if(!empty($_GET['buyingformat'])) {
    $j = 0;
    $ebayurl .= "&itemFilter[$i].name=ListingType";
    foreach($_GET['buyingformat'] as $check) {
      $ebayurl .= "&itemFilter[$i].value[$j]=".$check;
      $j++;
    }
    $i++;
    }

    /*Seller Filter*/
    $selleractive = isset($_GET['seller']) && $_GET['seller']  ? "true" : "false";
    if($selleractive == "true")
    {
      $ebayurl .= "&itemFilter[$i].name=ReturnsAcceptedOnly&itemFilter[$i].value=".$selleractive;
      $i++;
    }

    /*Shipping - Free Shipping Filter*/
    $freeshippingactive = isset($_GET['freeshipping']) && $_GET['freeshipping']  ? "true" : "false";
    if($freeshippingactive == "true")
    {
      $ebayurl .= "&itemFilter[$i].name=FreeShippingOnly&itemFilter[$i].value=".$freeshippingactive;
      $i++;
    }

    /*Shipping - Expedited shipping available*/
    $expeditedshippingavailableactive = isset($_GET['expeditedshippingavailable']) && $_GET['expeditedshippingavailable']  ? "true" : "false";
    if($expeditedshippingavailableactive == "true")
    {
      $ebayurl .= "&itemFilter[$i].name=ExpeditedShippingType&itemFilter[$i].value=".$expeditedshippingavailableactive;
      $i++;
    }

    /*Shipping - Max handling time (days) Filter*/
    if(!empty($_GET['maxhandlingtime']))
    {
    $ebayurl .= "&itemFilter[$i].name=MaxHandlingTime&itemFilter[$i].value=".$_GET['maxhandlingtime'];
    $i++;
    }

    $fetchxml = simplexml_load_file($ebayurl);

    if(($fetchxml->paginationOutput->totalEntries)==0):
      echo "<div style=\"border:solid 2px black;margin:200px;margin-top:-140px;padding-top:15px;\">";
      echo "<center>no results were found</center>";
      echo "</div>";
      else:
        /*Keep track of number of filter*/

        if ($fetchxml->ack == "Success") {
        echo "<div style=\"border:solid 2px black;margin:200px;margin-top:-140px;padding-top:15px;\">";
        echo "<center>{$fetchxml->paginationOutput->totalEntries}&nbsp;Results&nbsp;for&nbsp;{$_GET['key_words']}</center>";
        echo "<div style=\"border:solid 1px black;margin:30px;padding:5px;\">";
          foreach($fetchxml->searchResult->item as $item) {
            echo "<div><img src=\"{$item->galleryURL}\" style=\"height:250;width:250\"></div>";
            echo "<div style=\"margin-left:280px;margin-top:-220px;height:200;\"><a href=\"{$item->viewItemURL}\">{$item->title}</a>";
            echo "<div style=\"margin-top:30px;\"><b>Condition:</b>{$item->condition->conditionDisplayName}</div>";
            if($item->topRatedListing == "true")
            {
              echo "<div style=\"margin-top:-38px;margin-left:170px;\"><img src=\"http://cs-server.usc.edu:45678/hw/hw6/itemTopRated.jpg\" style=\"height:70;width:80\"></div>";
            }
            if($item->listingInfo->listingType == "FixedPrice" || $item->listingInfo->listingType == "StoreInventory")
            {
              echo "<div style=\"margin-top:20px;\"><b>Buy It Now</b></div>";
            }
            if($item->listingInfo->listingType == "Auction")
            {
              echo "<div style=\"margin-top:20px;\"><b>Auction</b></div>";
            }
            if($item->listingInfo->listingType == "Classified")
            {
              echo "<div style=\"margin-top:20px;\"><b>Classified&nbsp;Ad</b></div>";
            }
            if($item->returnsAccepted == "true")
            {
              echo "Seller Accepts returns";
            }
            if($item->shippingInfo->shippingServiceCost == "0.0")
            {
              echo "<br/>FREE Shipping --";
            }
            else
            {
              echo "<br/>Shipping not FREE --";
            }
            if($item->shippingInfo->expeditedShipping == "true")
            {
              echo "Expedited Shipping Available --";
            }
            echo "Handled for shipping is {$item->shippingInfo->handlingTime} day(s)";
            echo "<div style=\"margin-top:15px;\">";
            echo "<b>Price: \${$item->sellingStatus->convertedCurrentPrice}";
            if($item->shippingInfo->shippingServiceCost > "0")
            {
              echo " (+ \${$item->shippingInfo->shippingServiceCost}for shipping)";
            }
            echo "</b>";
            echo "<i> {$item->location}</i>";
            echo "</div>";
            echo "</div>";
            echo "<div style=\"margin-top:25px;\"><hr/></div>";
          }
        echo "</div>";
        echo "</div>";
      }

    ?>
    <?php endif;?>
    <?php endif;?>

  <noscript>
</body>
</html>
