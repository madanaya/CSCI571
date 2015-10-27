package com.example.mrsagarwal.hw_9;

import android.media.Image;
import android.os.Bundle;
import java.io.IOException;
import android.graphics.*;
import java.io.InputStream;
import java.lang.String;
import java.util.*;
import android.app.Activity;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.drawable.Drawable;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.graphics.drawable.BitmapDrawable;

import org.json.JSONObject;
import org.json.JSONException;
import android.support.v7.app.ActionBarActivity;
import android.util.Log;
import android.net.Uri;

import android.graphics.Typeface;
import android.widget.Toast;

import com.facebook.CallbackManager;
import com.facebook.FacebookCallback;
import com.facebook.FacebookException;
import com.facebook.FacebookSdk;
import com.facebook.login.LoginResult;
import com.facebook.login.widget.LoginButton;
import com.facebook.share.model.ShareLinkContent;
import com.facebook.share.widget.ShareDialog;
import com.facebook.share.Sharer;

public class DetailsActivity extends ActionBarActivity{
    private Activity activity;
    ImageView pictureImage,topratedImage,facebookImage;
    //BasicInfo
    TextView title,price,location;
    CallbackManager callbackManager;
    ShareDialog shareDialog;
   Button basicInfo;

    private class DownloadImageTask extends AsyncTask<String, Void, Bitmap> {
        ImageView bmImage;

        public DownloadImageTask(ImageView bmImage) {
            this.bmImage = bmImage;
        }

        protected Bitmap doInBackground(String... urls) {
            String urldisplay = urls[0];
            Bitmap mIcon11 = null;
            try {
                InputStream in = new java.net.URL(urldisplay).openStream();
                mIcon11 = BitmapFactory.decodeStream(in);
            } catch (Exception e) {
                Log.e("Error", e.getMessage());
                e.printStackTrace();
            }
            return mIcon11;
        }

        protected void onPostExecute(Bitmap result) {
            bmImage.setImageBitmap(result);
        }
    }
    @Override
    protected void onActivityResult(final int requestCode, final int resultCode, final Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        callbackManager.onActivityResult(requestCode, resultCode, data);


    }

    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        FacebookSdk.sdkInitialize(getApplicationContext());
        setContentView(R.layout.activity_details);
        callbackManager = CallbackManager.Factory.create();
        basicInfo = (Button) findViewById(R.id.button);
        //basicInfo.setOnClickListener(myhandler1);


            shareDialog = new ShareDialog(this);

        shareDialog.registerCallback(callbackManager, new FacebookCallback<Sharer.Result>() {

            @Override
            public void onSuccess(Sharer.Result result) {
                if(result.getPostId() == null) {
                    Toast.makeText(getBaseContext(), "Post cancelled", Toast.LENGTH_SHORT).show();
                }
                else {
                    Toast.makeText(getBaseContext(), "Posted Story, ID:" + result.getPostId(), Toast.LENGTH_LONG).show();
                }

            }

            @Override
            public void onError(FacebookException error) {
                // Log.d(TAG, "error");
                Toast.makeText(getBaseContext(), "Posting failed", Toast.LENGTH_SHORT).show();
            }

            @Override
            public void onCancel() {
                //Log.d(TAG, "cancel");
                Toast.makeText(getBaseContext(), "Post Cancelled", Toast.LENGTH_SHORT).show();

            }
        });




        title=(TextView)findViewById(R.id.textView2);
        price=(TextView)findViewById(R.id.textView3);
        location=(TextView)findViewById(R.id.textView4);
        pictureImage = (ImageView) findViewById(R.id.imageView2) ;
        topratedImage = (ImageView) findViewById(R.id.imageView3);



        try {
            Bundle b = getIntent().getExtras();
            String resultJSONString = null;

            //Log.i("Result",resultJSONString);
            if (b != null) {
                resultJSONString = b.getString("resultJSON");
                Log.i("ResultActivity", resultJSONString);
            }

            final JSONObject resultJSON = resultJSONString == null ? null : new JSONObject(resultJSONString);

            //Log.i("JSON",resultJSONString.toString());
            title.setText(resultJSON.getJSONObject("basicInfo").getString("title"));

            //Display price
            if (resultJSON.getJSONObject("basicInfo").getString("shippingServiceCost").equals("0.0")) {
                price.setText( "Price: $"+ resultJSON.getJSONObject("basicInfo").getString("convertedCurrentPrice")+"(FREE Shipping)");
            } else if (resultJSON.getJSONObject("basicInfo").getString("shippingServiceCost").equals("")) {
                price.setText( "Price: $"+ resultJSON.getJSONObject("basicInfo").getString("convertedCurrentPrice") + "(N/A)");
            } else
                price.setText( "Price: $"+ resultJSON.getJSONObject("basicInfo").getString("convertedCurrentPrice") + " Shipping)");

            //Location
            location.setText(resultJSON.getJSONObject("basicInfo").getString("location"));

            //PictureImage
            if(resultJSON.getJSONObject("basicInfo").getString("galleryURL").equals("")) {
                DownloadImageTask task = new DownloadImageTask(pictureImage);
                task.execute(resultJSON.getJSONObject("basicInfo").getString("galleryURL"));
            }
            else {
                DownloadImageTask task = new DownloadImageTask(pictureImage);
                task.execute(resultJSON.getJSONObject("basicInfo").getString("pictureURLSuperSize"));
            }

            //TopRated
            if(resultJSON.getJSONObject("basicInfo").getString("topRatedListing").equals("true")) {
                topratedImage.setVisibility(View.VISIBLE);
            }
            else {
                topratedImage.setVisibility(View.INVISIBLE);
            }

            Button buyNow =(Button) findViewById(R.id.button2);
            buyNow.setOnClickListener(new View.OnClickListener() {
                String url = resultJSON.getJSONObject("basicInfo").getString("viewItemURL");
                @Override
                public void onClick(View v) {
                    Intent viewIntent =
                            new Intent("android.intent.action.VIEW",
                                    Uri.parse(url));
                    startActivity(viewIntent);
                }


            });



            final RelativeLayout tl=(RelativeLayout)findViewById(R.id.mainlayout);
            final Button btnBasicInfo =(Button) findViewById(R.id.button);
            final Button btnSellerInfo =(Button) findViewById(R.id.button3);
            final Button btnShippingInfo =(Button) findViewById(R.id.button4);

            btnBasicInfo.setOnClickListener(new View.OnClickListener() {

                @Override
                public void onClick(View v) {
                  //  btnBasicInfo.setBackgroundColor(Color.BLUE);
                    //btnSellerInfo.setBackgroundColor(Color.GRAY);
                   // btnShippingInfo.setBackgroundColor(Color.GRAY);
                    btnBasicInfo.setBackgroundResource(R.drawable.active);
                    btnSellerInfo.setBackgroundResource(R.drawable.inactive);
                    btnShippingInfo.setBackgroundResource(R.drawable.inactive);
                    tl.removeAllViews();
                    v = getLayoutInflater().inflate(R.layout.basic_info, null);
                    TextView categoryname = (TextView) v.findViewById(R.id.textView8);
                    TextView condition = (TextView) v.findViewById(R.id.textView9);
                    TextView buyingformat = (TextView) v.findViewById(R.id.textView10);

                    try {
                        if(resultJSON.getJSONObject("basicInfo").getString("categoryName").equals("")) {
                            categoryname.setText("N/A");
                        }
                        else
                            categoryname.setText(resultJSON.getJSONObject("basicInfo").getString("categoryName"));
                    } catch (JSONException e) {
                        Log.e("JSONparseError", "unexpected JSON exception", e);
                        // Do something to recover ... or kill the app.
                    }

                    try {
                        if(resultJSON.getJSONObject("basicInfo").getString("conditionDisplayName").equals("")) {
                            condition.setText("N/A");
                        }
                        else
                            condition.setText(resultJSON.getJSONObject("basicInfo").getString("conditionDisplayName"));
                    } catch (JSONException e) {
                        Log.e("JSONparseError", "unexpected JSON exception", e);
                        // Do something to recover ... or kill the app.
                    }


                    try {
                        if(resultJSON.getJSONObject("basicInfo").getString("listingType").equals("")) {
                            buyingformat.setText("N/A");
                        }
                        else
                            buyingformat.setText(resultJSON.getJSONObject("basicInfo").getString("listingType"));
                    } catch (JSONException e) {
                        Log.e("JSONparseError", "unexpected JSON exception", e);
                        // Do something to recover ... or kill the app.
                    }




                    tl.addView(v, 0, new ViewGroup.LayoutParams(ViewGroup.LayoutParams.FILL_PARENT, ViewGroup.LayoutParams.FILL_PARENT));

                }
            });
            btnBasicInfo.performClick();

            btnSellerInfo.setOnClickListener(new View.OnClickListener() {

                @Override
                public void onClick(View v) {
                    btnBasicInfo.setBackgroundResource(R.drawable.inactive);
                    btnSellerInfo.setBackgroundResource(R.drawable.active);
                    btnShippingInfo.setBackgroundResource(R.drawable.inactive);
                    tl.removeAllViews();
                    v = getLayoutInflater().inflate(R.layout.seller_info, null);
                    TextView username = (TextView) v.findViewById(R.id.textView8);
                    TextView feedbackscore = (TextView) v.findViewById(R.id.textView9);
                    TextView positivefeedback = (TextView) v.findViewById(R.id.textView10);
                    TextView feedbackrating = (TextView) v.findViewById(R.id.textView12);
                    TextView store = (TextView) v.findViewById(R.id.textView16);

                    try {
                        if(resultJSON.getJSONObject("sellerInfo").getString("sellerUserName").equals("")) {
                            username.setText("N/A");
                        }
                        else
                            username.setText(resultJSON.getJSONObject("sellerInfo").getString("sellerUserName"));
                    } catch (JSONException e) {
                        Log.e("JSONparseError", "unexpected JSON exception", e);
                        // Do something to recover ... or kill the app.
                    }

                    try {
                        if(resultJSON.getJSONObject("sellerInfo").getString("feedbackScore").equals("")) {
                            feedbackscore.setText("N/A");
                        }
                        else
                            feedbackscore.setText(resultJSON.getJSONObject("sellerInfo").getString("feedbackScore"));
                    } catch (JSONException e) {
                        Log.e("JSONparseError", "unexpected JSON exception", e);
                        // Do something to recover ... or kill the app.
                    }


                    try {
                        if(resultJSON.getJSONObject("sellerInfo").getString("positiveFeedbackPercent").equals("")) {
                            positivefeedback.setText("N/A");
                        }
                        else
                            positivefeedback.setText(resultJSON.getJSONObject("sellerInfo").getString("positiveFeedbackPercent"));
                    } catch (JSONException e) {
                        Log.e("JSONparseError", "unexpected JSON exception", e);
                        // Do something to recover ... or kill the app.
                    }

                    try {
                        if(resultJSON.getJSONObject("sellerInfo").getString("positiveFeedbackPercent").equals("")) {
                            feedbackrating.setText("N/A");
                        }
                        else
                            feedbackrating.setText(resultJSON.getJSONObject("sellerInfo").getString("positiveFeedbackPercent"));
                    } catch (JSONException e) {
                        Log.e("JSONparseError", "unexpected JSON exception", e);
                        // Do something to recover ... or kill the app.
                    }

                    try {
                        if(resultJSON.getJSONObject("sellerInfo").getString("topRatedSeller").equals("true")) {
                            Typeface fontfamily = Typeface.createFromAsset(getAssets(),"font/cross_tick.ttf");
                            TextView toprated = (TextView) v.findViewById(R.id.textView15);
                            TextView toprated2 = (TextView) v.findViewById(R.id.textView15b);
                            toprated2.setVisibility(v.INVISIBLE);
                            toprated.setVisibility(v.VISIBLE);
                            toprated.setTypeface(fontfamily);
                            toprated.setText("\uf00c");
                        }
                        else {
                            Typeface fontfamily = Typeface.createFromAsset(getAssets(),"font/cross_tick.ttf");
                            TextView toprated2 = (TextView) v.findViewById(R.id.textView15b);
                            TextView toprated = (TextView) v.findViewById(R.id.textView15b);
                            toprated.setVisibility(v.INVISIBLE);
                            toprated2.setVisibility(v.VISIBLE);
                            toprated2.setTypeface(fontfamily);
                            toprated2.setText("\uf00d");
                        }

                    } catch (JSONException e) {
                        Log.e("JSONparseError", "unexpected JSON exception", e);
                        // Do something to recover ... or kill the app.
                    }

                    try {
                        if(resultJSON.getJSONObject("sellerInfo").getString("positiveFeedbackPercent").equals("")) {
                            store.setText("N/A");
                        }
                        else
                            store.setText(resultJSON.getJSONObject("sellerInfo").getString("positiveFeedbackPercent"));
                    } catch (JSONException e) {
                        Log.e("JSONparseError", "unexpected JSON exception", e);
                        // Do something to recover ... or kill the app.
                    }




                    tl.addView(v, 0, new ViewGroup.LayoutParams(ViewGroup.LayoutParams.FILL_PARENT, ViewGroup.LayoutParams.FILL_PARENT));
                }

            });

            btnShippingInfo.setOnClickListener(new View.OnClickListener() {

                @Override
                public void onClick(View v) {
                    btnBasicInfo.setBackgroundResource(R.drawable.inactive);
                    btnSellerInfo.setBackgroundResource(R.drawable.inactive);
                    btnShippingInfo.setBackgroundResource(R.drawable.active);
                    tl.removeAllViews();
                    v = getLayoutInflater().inflate(R.layout.shipping_info, null);
                    TextView shippingtype = (TextView) v.findViewById(R.id.textView8);
                    TextView handlingtime = (TextView) v.findViewById(R.id.textView9);
                    TextView shippinglocations = (TextView) v.findViewById(R.id.textView10);

                    try {
                        if(resultJSON.getJSONObject("shippingInfo").getString("shippingType").equals("")) {
                            shippingtype.setText("N/A");
                        }
                        else
                            shippingtype.setText(resultJSON.getJSONObject("shippingInfo").getString("shippingType"));
                    } catch (JSONException e) {
                        Log.e("JSONparseError", "unexpected JSON exception", e);
                        // Do something to recover ... or kill the app.
                    }

                    try {
                        if(resultJSON.getJSONObject("shippingInfo").getString("handlingTime").equals("")) {
                            handlingtime.setText("N/A");
                        }
                        else
                            handlingtime.setText(resultJSON.getJSONObject("shippingInfo").getString("handlingTime")+"day");
                    } catch (JSONException e) {
                        Log.e("JSONparseError", "unexpected JSON exception", e);
                        // Do something to recover ... or kill the app.
                    }


                    try {
                        if(resultJSON.getJSONObject("shippingInfo").getString("shipToLocations").equals("")) {
                            shippinglocations.setText("N/A");
                        }
                        else
                            shippinglocations.setText(resultJSON.getJSONObject("shippingInfo").getString("shipToLocations"));
                    } catch (JSONException e) {
                        Log.e("JSONparseError", "unexpected JSON exception", e);
                        // Do something to recover ... or kill the app.
                    }

                    try {
                        if(resultJSON.getJSONObject("shippingInfo").getString("expeditedShipping").equals("true")) {
                            Typeface fontfamily = Typeface.createFromAsset(getAssets(),"font/cross_tick.ttf");
                            TextView toprated = (TextView) v.findViewById(R.id.textView12b);
                            TextView toprated2 = (TextView) v.findViewById(R.id.textView12);
                            toprated2.setVisibility(v.INVISIBLE);
                            toprated.setVisibility(v.VISIBLE);
                            toprated.setTypeface(fontfamily);
                            toprated.setText("\uf00c");
                        }
                        else {
                            Typeface fontfamily = Typeface.createFromAsset(getAssets(),"font/cross_tick.ttf");
                            TextView toprated2 = (TextView) v.findViewById(R.id.textView12);
                            TextView toprated = (TextView) v.findViewById(R.id.textView12b);
                            toprated.setVisibility(v.INVISIBLE);
                            toprated2.setVisibility(v.VISIBLE);
                            toprated2.setTypeface(fontfamily);
                            toprated2.setText("\uf00d");
                        }

                    } catch (JSONException e) {
                        Log.e("JSONparseError", "unexpected JSON exception", e);
                        // Do something to recover ... or kill the app.
                    }

                    try {
                        if(resultJSON.getJSONObject("shippingInfo").getString("oneDayShippingAvailable").equals("true")) {
                            Typeface fontfamily = Typeface.createFromAsset(getAssets(),"font/cross_tick.ttf");
                            TextView toprated = (TextView) v.findViewById(R.id.textView15);
                            TextView toprated2 = (TextView) v.findViewById(R.id.textView15b);
                            toprated2.setVisibility(v.INVISIBLE);
                            toprated.setVisibility(v.VISIBLE);
                            toprated.setTypeface(fontfamily);
                            toprated.setText("\uf00c");
                        }
                        else {
                            Typeface fontfamily = Typeface.createFromAsset(getAssets(),"font/cross_tick.ttf");
                            TextView toprated2 = (TextView) v.findViewById(R.id.textView15b);
                            TextView toprated = (TextView) v.findViewById(R.id.textView15);
                            toprated.setVisibility(v.INVISIBLE);
                            toprated2.setVisibility(v.VISIBLE);
                            toprated2.setTypeface(fontfamily);
                            toprated2.setText("\uf00d");
                        }

                    } catch (JSONException e) {
                        Log.e("JSONparseError", "unexpected JSON exception", e);
                        // Do something to recover ... or kill the app.
                    }

                    try {
                        if(resultJSON.getJSONObject("shippingInfo").getString("returnsAccepted").equals("true")) {
                            Typeface fontfamily = Typeface.createFromAsset(getAssets(),"font/cross_tick.ttf");
                            TextView toprated = (TextView) v.findViewById(R.id.textView16);
                            TextView toprated2 = (TextView) v.findViewById(R.id.textView16b);
                            toprated2.setVisibility(v.INVISIBLE);
                            toprated.setVisibility(v.VISIBLE);
                            toprated.setTypeface(fontfamily);
                            toprated.setText("\uf00c");
                        }
                        else {
                            Typeface fontfamily = Typeface.createFromAsset(getAssets(),"font/cross_tick.ttf");
                            TextView toprated2 = (TextView) v.findViewById(R.id.textView16b);
                            TextView toprated = (TextView) v.findViewById(R.id.textView16);
                            toprated.setVisibility(v.INVISIBLE);
                            toprated2.setVisibility(v.VISIBLE);
                            toprated2.setTypeface(fontfamily);
                            toprated2.setText("\uf00d");
                        }

                    } catch (JSONException e) {
                        Log.e("JSONparseError", "unexpected JSON exception", e);
                        // Do something to recover ... or kill the app.
                    }

                    tl.addView(v, 0, new ViewGroup.LayoutParams(ViewGroup.LayoutParams.FILL_PARENT, ViewGroup.LayoutParams.FILL_PARENT));
                }
            });






            //ImageButton Fbpost = (ImageButton)findViewById(R.id.fbbutton);
            ImageView Fbpost = (ImageView) findViewById(R.id.imageView4);

            Fbpost.setOnClickListener(new View.OnClickListener(){
                @Override
                public void onClick(View v){

                    try {
                        String price;
                        String a,b;
                        String location = resultJSON.getJSONObject("basicInfo").getString("location");
                        String title = resultJSON.getJSONObject("basicInfo").getString("title");
                        String URL = resultJSON.getJSONObject("basicInfo").getString("viewItemURL");
                        if (resultJSON.getJSONObject("basicInfo").getString("shippingServiceCost").equals("0.0")) {
                            a=resultJSON.getJSONObject("basicInfo").getString("convertedCurrentPrice");
                            price="Price: $"+a+"(FREE Shipping)";
                        } else if (resultJSON.getJSONObject("basicInfo").getString("shippingServiceCost").equals("")) {
                            a=resultJSON.getJSONObject("basicInfo").getString("convertedCurrentPrice");
                            price="Price: $"+a+"(N/A)";
                        } else
                            a=resultJSON.getJSONObject("basicInfo").getString("convertedCurrentPrice");
                        //  b=resultJSON.getJSONObject("basicInfo").getString("shippingServiceCost");
                        price="Price: $"+a+"(FREE Shipping)";

                        String p=price+",Location:"+location;



                        if (ShareDialog.canShow(ShareLinkContent.class)) {
                            ShareLinkContent linkContent = new ShareLinkContent.Builder().setContentTitle(title).setContentDescription(p).setContentUrl(Uri.parse(URL)).build();
                            shareDialog.show(linkContent);


                        }
                    }
                    catch (JSONException e) {
                        e.printStackTrace();
                    }


                }


            });
        }

        catch (Exception e) {

        }
    }
}
