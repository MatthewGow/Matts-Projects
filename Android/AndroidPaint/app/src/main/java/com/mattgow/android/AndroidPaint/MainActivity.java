package com.mattgow.android.AndroidPaint;
/********************************************************
 *    PROGRAM: Android Paint
 * PROGRAMMER: Matthew Gow
 *   ACTIVITY: MainActivity.java
 *    PURPOSE: On Launch the user is presented with a
 *             blank canvas and a default (red) paint
 *             brush. They can select a new paint color,
 *             select a new brush size, save their
 *             canvas, erase, and create a new canvas.
 *******************************************************/

/*******************************************************
 * Preprocessor Directives
 *******************************************************/
//Android Libraries
import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ImageButton;
import android.widget.LinearLayout;
import android.provider.MediaStore;
import android.app.AlertDialog;
import android.app.Dialog;
import android.content.DialogInterface;
import android.view.View.OnClickListener;
import android.widget.Toast;
//Java Libraries
import java.util.UUID;

/*******************************************************
 * Main Activity Class
 *     extends: Activity
 *  implements: View.OnClickListener
 *******************************************************/
public class MainActivity extends Activity implements View.OnClickListener
{

    //declare variables for DrawView class, buttons, and brush sizes.
    private DrawView drawView;
    private ImageButton currPaint, drawBtn, eraseBtn, newBtn, saveBtn;
    private float smallBrush, mediumBrush, largeBrush;

    /********************************************
     * OnCreate Meathod                         *
     * used for initializing the application    *
     ********************************************/
    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        //Initialize the DrawView Class instance
        drawView = (DrawView)findViewById(R.id.drawing);
        LinearLayout paintLayout = (LinearLayout)findViewById(R.id.paint_colors);
        //Set default paint color to the first one on the menu
        currPaint = (ImageButton)paintLayout.getChildAt(0);
        currPaint.setImageDrawable(getResources().getDrawable(R.drawable.paint_press));
        //Store set brush sizes from dimens as Integers into separate Java variables
        smallBrush = getResources().getInteger(R.integer.small_size);
        mediumBrush = getResources().getInteger(R.integer.medium_size);
        largeBrush = getResources().getInteger(R.integer.large_size);
        //Initiate OnClick for Brush Button
        drawBtn = (ImageButton)findViewById(R.id.draw_btn);
        drawBtn.setOnClickListener(this);
        //set default brush size to medium
        drawView.setBrushSize(mediumBrush);
        //Initiate OnClick for Erase Button
        eraseBtn = (ImageButton)findViewById(R.id.erase_btn);
        eraseBtn.setOnClickListener(this);
        //Initiate OnClick for New Button
        newBtn = (ImageButton)findViewById(R.id.new_btn);
        newBtn.setOnClickListener(this);
        //Initiate OnClick for Save Button
        saveBtn = (ImageButton)findViewById(R.id.save_btn);
        saveBtn.setOnClickListener(this);
    }

    @Override
    public void onClick(View view)
    {
        /**************************
         * Draw Button Is Clicked *
         **************************/
        if(view.getId()==R.id.draw_btn)
        {
            final Dialog brushDialog = new Dialog(this);
            brushDialog.setTitle("Brush Sizes");
            brushDialog.setContentView(R.layout.brush_select);
            Button btnSm = (Button)brushDialog.findViewById(R.id.buttonSmBrush);
            btnSm.setOnClickListener(new OnClickListener()
            {
                @Override
                public void onClick(View v)
                {
                    drawView.setBrushSize(smallBrush);
                    drawView.setLastBrushSize(smallBrush);
                    drawView.setErase(false);
                    brushDialog.dismiss();
                }
            });
            btnSm.setOnClickListener(new OnClickListener()
            {
                @Override
                public void onClick(View v)
                {
                    drawView.setBrushSize(smallBrush);
                    drawView.setLastBrushSize(smallBrush);
                    drawView.setErase(false);
                    brushDialog.dismiss();
                }
            });
            Button btnMed = (Button)brushDialog.findViewById(R.id.buttonMdBrush);
            btnMed.setOnClickListener(new OnClickListener()
            {
                @Override
                public void onClick(View v)
                {
                    drawView.setBrushSize(mediumBrush);
                    drawView.setLastBrushSize(mediumBrush);
                    drawView.setErase(false);
                    brushDialog.dismiss();
                }
            });
            Button btnLg = (Button)brushDialog.findViewById(R.id.buttonLgBrush);
            btnLg.setOnClickListener(new OnClickListener()
            {
                @Override
                public void onClick(View v)
                {
                    drawView.setBrushSize(largeBrush);
                    drawView.setLastBrushSize(largeBrush);
                    drawView.setErase(false);
                    brushDialog.dismiss();
                }
            });
            brushDialog.show();
        }

        /**************************
         * Erase Button Is Clicked*
         **************************/
        else if(view.getId()==R.id.erase_btn)
        {
            final Dialog brushDialog = new Dialog(this);
            brushDialog.setTitle("Eraser Sizes");
            brushDialog.setContentView(R.layout.brush_select);
            Button btnSm = (Button)brushDialog.findViewById(R.id.buttonSmBrush);
            //On Click Listener for SMALL brush
            btnSm.setOnClickListener(new OnClickListener()
            {
                @Override
                public void onClick(View v)
                {
                    drawView.setErase(true);
                    drawView.setBrushSize(smallBrush);
                    brushDialog.dismiss();
                }
            });
            Button btnMed = (Button)brushDialog.findViewById(R.id.buttonMdBrush);
            //On Click Listener for MEDIUM brush
            btnMed.setOnClickListener(new OnClickListener()
            {
                @Override
                public void onClick(View v)
                {
                    drawView.setErase(true);
                    drawView.setBrushSize(mediumBrush);
                    brushDialog.dismiss();
                }
            });
            Button btnLg = (Button)brushDialog.findViewById(R.id.buttonLgBrush);
            //On Click Listener for LARGE brush
            btnLg.setOnClickListener(new OnClickListener()
            {
                @Override
                public void onClick(View v)
                {
                    drawView.setErase(true);
                    drawView.setBrushSize(largeBrush);
                    brushDialog.dismiss();
                }
            });
            brushDialog.show();
        }

        /**************************
         * New Button Is Clicked  *
         **************************/
        else if(view.getId()==R.id.new_btn)
        {
            AlertDialog.Builder newDialog = new AlertDialog.Builder(this);
            //Prompt user to confirm they want a New Canvas
            newDialog.setTitle("New Drawing?");
            newDialog.setMessage("Are you sure you want to start a new drawing? Current drawing will be lost!");
            //if user wants to create a new canvas
            newDialog.setPositiveButton("Yes", new DialogInterface.OnClickListener()
            {
                public void onClick(DialogInterface dialog, int which)
                {
                    drawView.newDrawing();
                    dialog.dismiss();
                }
            });
            //if the user decides to cancel and remain on the current canvas
            newDialog.setNegativeButton("Cancel", new DialogInterface.OnClickListener()
            {
                public void onClick(DialogInterface dialog, int which)
                {
                    dialog.cancel();
                }
            });
            newDialog.show();
        }
        /**************************
         * Save Button Is Clicked *
         **************************/
        else if(view.getId()==R.id.save_btn)
        {
            AlertDialog.Builder saveDialog = new AlertDialog.Builder(this);
            saveDialog.setTitle("Save drawing");
            saveDialog.setMessage("Save drawing to device Gallery?");
            saveDialog.setPositiveButton("Yes", new DialogInterface.OnClickListener()
            {
                public void onClick(DialogInterface dialog, int which)
                {
                    drawView.setDrawingCacheEnabled(true);
                    String imgSaved = MediaStore.Images.Media.insertImage(
                            MainActivity.this.getContentResolver(), drawView.getDrawingCache(),
                            UUID.randomUUID().toString()+".png", "drawing");
                    //if the image saves successfully
                    if(imgSaved!=null)
                    {
                        Toast savedToast = Toast.makeText(getApplicationContext(),
                                "Drawing saved to Gallery!", Toast.LENGTH_LONG);
                        savedToast.show();
                    }
                    //if the image can't save to device
                    else
                    {
                        Toast unsavedToast = Toast.makeText(getApplicationContext(),
                                "Sorry, Your drawing could not be saved to Gallery!", Toast.LENGTH_LONG);
                        unsavedToast.show();
                    }
                    //clear the cache for the new drawView call
                    drawView.destroyDrawingCache();
                }
            });
            //if the user decides to cancel the save
            saveDialog.setNegativeButton("Cancel", new DialogInterface.OnClickListener()
            {
                public void onClick(DialogInterface dialog, int which)
                {
                    dialog.cancel();
                }
            });
            saveDialog.show();
        }
    }

    /**************************
     * New Paint is Selected  *
     **************************/
    public void paintClicked(View view){
        if(view!=currPaint)
        {
            drawView.setErase(false);
            drawView.setBrushSize(drawView.getLastBrushSize());
            ImageButton imgView = (ImageButton)view;
            String color = view.getTag().toString();
            drawView.setColor(color);
            imgView.setImageDrawable(getResources().getDrawable(R.drawable.paint_press));
            currPaint.setImageDrawable(getResources().getDrawable(R.drawable.paint));
            currPaint=(ImageButton)view;
        }
    }
}
