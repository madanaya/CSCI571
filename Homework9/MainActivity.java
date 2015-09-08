package com.example.mrsagarwal.hw_9;

import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import java.util.regex.Pattern;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;

import android.text.Editable;import android.widget.Toast;
import android.text.TextWatcher;
import android.widget.EditText;
import android.view.Menu;

import android.view.MenuItem;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import java.util.*;
import android.util.Log;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;
import java.net.URI;
import java.net.URLEncoder;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.content.Intent;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.content.pm.PackageManager.NameNotFoundException;
import android.content.pm.Signature;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v7.app.ActionBarActivity;
import android.util.Base64;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;
import android.graphics.Typeface;

public class MainActivity extends ActionBarActivity {
    EditText key_word,price_from_text,price_to_text;
    TextView msg,ErrorMessage;
    Spinner spinner;
    String noResults="";
    int flag_noResults=0;
    private Activity thisActivity;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);


        thisActivity = this;
        key_word=(EditText)findViewById(R.id.keyword_text);
        price_from_text=(EditText)findViewById(R.id.price_from_text);
        price_to_text=(EditText)findViewById(R.id.price_to_text);
        msg = (TextView) findViewById(R.id.txtviewOut) ;
        ErrorMessage = (TextView) findViewById(R.id.textView22);
        spinner=(Spinner) findViewById(R.id.spinner) ;
        // msg.setGravity(Gravity.TOP|Gravity.START);
        // Log.d("MainActivity","OnCreate");
        Button btnSearch =(Button) findViewById(R.id.search_button);
        Button btnClear =(Button) findViewById(R.id.clear_button);

        btnSearch.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                int flag =0;
                Double priceFromNum = 0.0;
                Double priceToNum = 0.0;
                msg.setText("");

                if (key_word.length()== 0)
                {
                    msg.setText("Please enter a keyword");
//                   / msg.setVisibility(View.VISIBLE);
                    flag = 1;
                }

                if (price_from_text.getText().toString().length() > 0 && flag == 0){

                    try {
                        priceFromNum = new Double(price_from_text.getText().toString());
                        if (priceFromNum < 0) {
                            msg.setText("Price From must be positive or decimal number");
                            flag =1;
                        }
                    } catch (NumberFormatException nfe) {
                        msg.setText("Price cannot be a string");
                        flag =1;
                    }

                }


                if (price_to_text.getText().toString().length() > 0 && flag == 0){

                    try {
                        priceToNum = Double.parseDouble(price_to_text.getText().toString());
                        if (priceToNum < 0) {
                            msg.setText("Price To must be positive or decimal number");
                            flag=1;

                        }
                    } catch (NumberFormatException nfe) {
                        msg.setText("Price cannot be a string");
                        flag=1;
                    }

                    if(price_from_text.getText().toString().length() > 0) {
                        if(priceFromNum>priceToNum) {
                            msg.setText("Price From must be lesser than Price to");
                            flag=1;
                        }
                    }

                }
                if(flag==0) {
                    // msg.setText("immedi after flag");
                    //Fetch the SortBy value
                    String spinner1 = spinner.getSelectedItem().toString().trim();

                    if(spinner1.equals("Best Match")){
                        spinner1 = "BestMatch";
                    }
                    else if(spinner1.equals("Price:highest first")){
                        spinner1 = "CurrentPriceHighest";
                    }
                    else if(spinner1.equals("Price + Shipping:highest first")){
                        spinner1 = "PricePlusShippingHighest";
                    }
                    else if(spinner1.equals("Price + Shipping:lowest first")){
                        spinner1 = "PricePlusShippingLowest";
                        msg.setText(spinner1);
                    }

                    final String ebayURL = "http://salonihw9.elasticbeanstalk.com/?keyword="
                            +key_word.getText().toString() + "&priceFrom=" +
                            price_from_text.getText().toString()
                            + "&priceTo=" +  price_to_text.getText().toString()
                            + "&sortby=" +spinner1;


                    new AsyncTask<Void, Void, Void>() {

                        JSONObject resultObject = null;
                        String flag;

                        @Override
                        protected Void doInBackground(Void... params) {

                            try {
                                HttpClient client = new DefaultHttpClient();

                                URI website = new URI(ebayURL);
                                HttpGet request = new HttpGet();
                                request.setURI(website);
                                HttpResponse response;
                                response = client.execute(request);


                                String jsonResult = EntityUtils.toString(response.getEntity(), "UTF-8");

                                // Log.i("eBay results", "Received search result : " + jsonResult);





                                    if (jsonResult.contains("No Results Found")) {
                                        Log.d("result","no results found");
                                        noResults = "No Results Found";
                                        flag_noResults=1;
                                        //return null;
                                    }
                                    else
                                        resultObject = new JSONObject( jsonResult );


                            } catch (Exception e) {

                                Log.e("exception", e.getClass() + " : " + e.getLocalizedMessage());
                            }

                            return null;
                        }

                        @Override
                        protected void onPostExecute(Void v) {
                                    if(flag_noResults!=1) {
                                        Intent i = new Intent(thisActivity, ResultActivity.class);
                                        String key = key_word.getText().toString();
                                        i.putExtra("KeyWord", key);
                                        //    Intent j=  new Intent(thisActivity, ResultActivity.class);
                                        i.putExtra("resultJSON", resultObject.toString());

                                        //  j.putExtra("keyword",key_word.getText().toString());
                                        startActivity(i);
                                    }


                        }
                    }.execute();

                }
            }
        });

        btnClear.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                key_word.setText("");
                price_from_text.setText("");
                price_to_text.setText("");
                msg.setText("");
                spinner.setSelection(0);
            }
        });

    }



    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.action_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }
}
