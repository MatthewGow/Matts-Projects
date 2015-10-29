package com.mattgow.android.AndroidPaint;
/********************************************************
 *    PROGRAM: Android Paint
 * PROGRAMMER: Matthew Gow
 *   ACTIVITY: DrawView.java
 *    PURPOSE: Contains the functions needed to perform
 *             operations such as Create New, Erase,
 *             Change Brush Size and allow Drawing.
 *******************************************************/

/*******************************************************
 * Preprocessor Directives
 *******************************************************/
//Android Libraries
import android.graphics.Color;
import android.view.View;
import android.content.Context;
import android.util.AttributeSet;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.Paint;
import android.graphics.Path;
import android.view.MotionEvent;
import android.graphics.PorterDuff;
import android.graphics.PorterDuffXfermode;
import android.util.TypedValue;

/*******************************************************
 * DrawView Class
 *     extends: View
 *******************************************************/
public class DrawView extends View{
    //initialize variables
    //drawing path
    private Path path;
    //drawing and canvas paint
    private Paint drawPaint, canvasPaint;
    //set initial color
    private int paintColor = 0xFFFF0000;
    //the canvas
    private Canvas canvas;
    private Bitmap canvasBitmap;
    //brush size variables
    private float brushSize, lastBrushSize;
    //erase flag
    private boolean clear=false;

    //Default Constructor
    public DrawView(Context context, AttributeSet attrs)
    {
        super(context, attrs);
        setUp();
    }

    //setUp method to initialize the brush and canvas background
    private void setUp()
    {
        path = new Path();
        drawPaint = new Paint();
        brushSize = getResources().getInteger(R.integer.medium_size);
        lastBrushSize = brushSize;
        //initialize paint color
        drawPaint.setColor(paintColor);
        drawPaint.setAntiAlias(true);
        drawPaint.setStrokeWidth(brushSize);
        drawPaint.setStyle(Paint.Style.STROKE);
        drawPaint.setStrokeJoin(Paint.Join.ROUND);
        drawPaint.setStrokeCap(Paint.Cap.ROUND);
        //initialize canvas background
        canvasPaint = new Paint(Paint.DITHER_FLAG);
    }

    //setColor method to change the color of the brush
    public void setColor(String newColor)
    {
        invalidate();
        paintColor = Color.parseColor(newColor);
        drawPaint.setColor(paintColor);
    }

    //setBrushSize method to change the size of the brush (or eraser)
    public void setBrushSize(float newSize)
    {
        float pixelSize = TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP,
                newSize, getResources().getDisplayMetrics());
        brushSize=pixelSize;
        drawPaint.setStrokeWidth(brushSize);
    }

    //setLastBrushSize method to store the last brush sized used
    public void setLastBrushSize(float lastSize)
    {
        lastBrushSize=lastSize;
    }


    //setErase to preform erase
    public void setErase(boolean isClear){
        clear=isClear;
        if(clear)
        {
            drawPaint.setXfermode(new PorterDuffXfermode(PorterDuff.Mode.CLEAR));
        }
        else
        {
            drawPaint.setXfermode(null);
        }
    }

    //setLastBrushSize method to return the last brush sized used
    //this returns the value as a float
    public float getLastBrushSize()
    {
        return lastBrushSize;
    }

    //onSizeChanged method to change canvas properties when the size changes
    @Override
    protected void onSizeChanged(int w, int h, int oldw, int oldh)
    {
        super.onSizeChanged(w, h, oldw, oldh);
        //initialize canvas background
        canvasBitmap = Bitmap.createBitmap(w, h, Bitmap.Config.ARGB_8888);
        canvas = new Canvas(canvasBitmap);
    }


    //onDraw method to perform the draw on the canvas using the path
    @Override
    protected void onDraw(Canvas c) {
        c.drawBitmap(canvasBitmap, 0, 0, canvasPaint);
        c.drawPath(path, drawPaint);
    }

    //newDrawing method to perform the clear the canvas if a new drawing is created
    public void newDrawing()
    {
        //clear the current canvas
        canvas.drawColor(0, PorterDuff.Mode.CLEAR);
        //reprint the screen
        invalidate();
    }

    @Override
    //onTouchEvent method to store user touch input to the path
    public boolean onTouchEvent(MotionEvent event) {
        float touchX = event.getX();
        float touchY = event.getY();
        switch (event.getAction()) {
            case MotionEvent.ACTION_DOWN:
                path.moveTo(touchX, touchY);
                break;
            case MotionEvent.ACTION_MOVE:
                path.lineTo(touchX, touchY);
                break;
            case MotionEvent.ACTION_UP:
                canvas.drawPath(path, drawPaint);
                path.reset();
                break;
            default:
                return false;
        }
        invalidate();
        return true;
    }

}
