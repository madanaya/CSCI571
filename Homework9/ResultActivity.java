package com.example.mrsagarwal.hw_9;


import java.io.IOException;
import java.io.InputStream;
import java.lang.String;
import java.net.MalformedURLException;
import java.net.URL;

//FACEBOOK
//import com.facebook.*;
//import com.facebook.model.*;
//import com.facebook.widget.WebDialog;
//import com.facebook.widget.WebDialog.OnCompleteListener;
//END



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
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;
import android.graphics.drawable.BitmapDrawable;

import org.json.JSONObject;

import android.support.v7.app.ActionBarActivity;
import android.util.Log;
import android.widget.ArrayAdapter;
import android.widget.Toast;
import android.widget.Button;
import android.content.Context;

public class ResultActivity extends ActionBarActivity {

    private ListView listView;
    private ArrayAdapter adaptor;
    TextView listHeader;
    EditText key_word;
    @Override

    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_result);
        String keyWord = null;
        try {
            Bundle b = getIntent().getExtras();
            String resultJSONString = null;


            //Toast.makeText(this,str,Toast.LENGTH_LONG).show();
            // Log.i("Result",resultJSONString);
            if (b != null) {
                resultJSONString = b.getString("resultJSON");
                keyWord =getIntent().getStringExtra("KeyWord");

                Log.i("ResultActivity", resultJSONString);
            }

            JSONObject resultJSON = resultJSONString == null ? null : new JSONObject(resultJSONString);

            ListView l1;
            String[] title1 = new String[5];
            String[] price = new String[5];
            String[] ShipppingInfo = new String[5];
            String[] imsrc = new String[5];
            String[] jsonarray = new String[5];

            for (int i = 0; i < 5; i++) {
                title1[i] = resultJSON.getJSONObject("item" + i).getJSONObject("basicInfo").getString("title");
                //Display price
                price[i] = resultJSON.getJSONObject("item" + i).getJSONObject("basicInfo").getString("convertedCurrentPrice");
                //Display Image
                //Checking Code for Picture URL
                if(resultJSON.getJSONObject("item" + i).getJSONObject("basicInfo").getString("galleryURL").equals("")){
                    imsrc[i] = resultJSON.getJSONObject("item" + i).getJSONObject("basicInfo").getString("galleryURL");
                }
                else
                imsrc[i] = resultJSON.getJSONObject("item" + i).getJSONObject("basicInfo").getString("pictureURLSuperSize");
                ShipppingInfo[i] = resultJSON.getJSONObject("item" + i).getJSONObject("basicInfo").getString("shippingServiceCost");
                jsonarray[i] = resultJSON.getJSONObject("item"+i).toString();
            }

            // public static int[]
            listHeader=(TextView)findViewById(R.id.resultsHeading);
            listHeader.setText("Results for '"+ keyWord+ "'");
            l1 = (ListView) findViewById(R.id.listView);
            l1.setAdapter(new dataListAdapter(this,title1, price, imsrc, ShipppingInfo,jsonarray));
        } catch (Exception e) {

        }

    }

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


    class dataListAdapter extends BaseAdapter {
        String[] Title, Price, Image, ShippingInfo,resultJSON;
        private Activity activity;

        dataListAdapter() {
            Title = null;
        }

        public dataListAdapter(Activity a,String[] text, String[] price, String[] source, String[] shipInfo,String[] json) {
            activity = a;
            Title = text;
            Price = price;
            ShippingInfo = shipInfo;
            Image = source;
            resultJSON = json;
        }

        public int getCount() {
            // TODO Auto-generated method stub
            return Title.length;
        }

        public Object getItem(int arg0) {
            // TODO Auto-generated method stub
            return null;
        }

        public long getItemId(int position) {
            // TODO Auto-generated method stub
            return position;
        }

        public View getView(final int position, View convertView, ViewGroup parent) {

            LayoutInflater inflater = getLayoutInflater();
            View row;
            row = inflater.inflate(R.layout.list_xml, parent, false);
            TextView title, price;

           ImageView i1 = null;
            title = (TextView) row.findViewById(R.id.title);
            title.setText(Title[position]);

            title.setOnClickListener(new View.OnClickListener() {

                @Override
                public void onClick(View v) {
                    Intent i = new Intent(activity, DetailsActivity.class);
                    i.putExtra("resultJSON",resultJSON[position].toString());
                    startActivity(i);
                }
            });
            price = (TextView) row.findViewById(R.id.price);
            if (ShippingInfo[position].equals("0.0")) {
                price.setText("Price:$" + Price[position] + "(FREE Shipping)");
            } else if (ShippingInfo[position].equals("")) {
                price.setText("Price:$" + Price[position] + "(N/A)");
            } else
                price.setText("Price:$" + Price[position] + "(+ $" + ShippingInfo[position] + " Shipping)");
                 i1 = (ImageView)row.findViewById(R.id.imageView);
                 DownloadImageTask task = new DownloadImageTask(i1);
                 task.execute(Image[position]);






//            new DownloadImageTask((ImageView) findViewById(R.id.imageView))
//                    .execute(Image[position]);

//            new DownloadImageTask((ImageView) findViewById(R.id.imageView)).execute(Image[position]);
            //i1 = (ImageView)row.findViewById(R.id.imageView);
            // i1.setImageResource(img[position]);



//           Bitmap bmp = null;
//          InputStream is = null;
//          try {
//               is = (InputStream) new URL("http://www.joomlaworks.net/images/demos/galleries/abstract/7.jpg").getContent();
//           } catch (IOException e) {
//               e.printStackTrace();
//           }
            // Bitmap d = BitmapFactory.decodeStream(is);
            // i1.setImageBitmap(d);

//            new AsyncTask<Void, Void, Void>() {
//                @Override
//                protected Void doInBackground(Void... params) {
//                    try {
//                        InputStream in = new URL("").openStream();
//                        bmp = BitmapFactory.decodeStream(in);
//                    } catch (Exception e) {
//                        // log error
//                    }
//                    return null;
//                }
//
//                @Override
//                protected void onPostExecute(Void result) {
//                    if (bmp != null)
//                        i.setImageBitmap(bmp);
//                }
//
//            }.execute();




//            try {
//                InputStream is = (InputStream) new URL(Image[position]).getContent();
//                Drawable d = Drawable.createFromStream(is, "src name");
//                i.setImageDrawable(d);
//            } catch (Exception e) {
//                return null;
//            }
            //i1.setImageDrawable();
            //i1.setImageResource(img[position]);
            return (row);
        }
    }


    //FACEBOOK

    //END




}




// JSONObject resultListJSON = resultJSON.getJSONObject("item0").getJSONObject("basicInfo");
//JSONObject eBayResultData = null;

//JSONObject resultListJSON = resultJSON.getJSONObject("item0").getJSONObject("basicInfo");
// listViewTemplate dataList = new listViewTemplate(resultJSONString);
// ListView listview = (ListView)findViewById(R.id.listView);
// listview.setAdapter(dataList);

// Toast.makeText(this,resultListJSON.getString("title"),Toast.LENGTH_LONG).show();


//title.setText(resultListJSON.getString("title"));
