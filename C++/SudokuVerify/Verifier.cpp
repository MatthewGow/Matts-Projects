/*****************************************************************
   FILE:       Verifier.cpp
   BUILD DATE: 9/28/2012
 
   PURPOSE:    Reads from file containing suduko grid, stores
               value into a two dimentional array. Then checks
               if the grid is a valid suduko puzzle
*****************************************************************/
 
/***********************************************************
                Preprocessor Directives
***********************************************************/
#include  "Verifier.h"
#include <iostream>
#include <stdio.h>
#include <string.h>
#include <iomanip>
#include <fstream>
using namespace std;
 
/***********************************************************
                Default Construct
***********************************************************/
Verifier::Verifier()
{
    MyGrid[0][0] = '\0';
}
/***********************************************************
            readGrid() meathod:
            This method takes an argument of type const char*,
            which holds the file name of the suduko tables
            being imported to the program.
            This meathod returns nothing.
***********************************************************/
void Verifier::readGrid(const char* inputFile)
{
    ifstream myReadFile(inputFile);
   
    while (!myReadFile.eof())
    {
        for (int c=0; c < 9; c++)//<-----No pun intended.
        {
            for (int r = 0; r < 9; r++)
            {
                myReadFile >> MyGrid[r][c];
            }
        }
        myReadFile.close();
    }
}
/***********************************************************
            printGrid() meathod:
            This method takes no arguments and it returns
            nothing. It simply prints the two dimentional
            array of int's back to the user so they may
            see the suduko grid. Also adds spacing for
            clarity to the viewer.
***********************************************************/
void Verifier::printGrid()
{
        for (int c=0; c < 9; c++)
        {
            if (c==0||c==3||c==6)
                cout << "-------------------------" << endl;            
            for (int r = 0; r < 9; r++)
            {
                if (r==0||r==3||r==6)
                    cout << "| ";  
                cout << MyGrid[r][c] << " ";
                if (r==8)
                    cout << "|";  
            }
                if (c==8)
                    cout << endl << "-------------------------";
            cout << endl;
        }
}
/***********************************************************
            verifyGrid() meathod:
            This method takes no arguments and it returns
            a bool (true/false). This meathod is called to
            check if the grid is valid. It checks rows,
            columns, blocks for numbers 1-9 and also checks
            for duplicate numbers.
***********************************************************/
bool Verifier::verifySolution()
{
    int columnCheck=0;
    int rowCheck=0;
    int blockCheck=0;
    int ctr=0;
    bool status;
   
/***********************************************************
                Verify Columns
***********************************************************/
    for (int b=0; b < 9; b=b+3)
    {
        for (int r = 0; r < 9; r++)
        {
 
            for (int c=0; c < 9; c++)
            {
               columnCheck=columnCheck+MyGrid[r][c];
               if (c==8&&columnCheck==45)
                   status=true;
               else if (c==8&&columnCheck != 45)
                   return false;
            }
           
            if (columnCheck==45)
               columnCheck=0;
        }
       
    }  
   
/***********************************************************
                Verify Rows
***********************************************************/
    for (int b=0; b < 9; b=b+3)
    {
        for (int c=0; c < 9; c++)
        {
 
            for (int r = 0; r < 9; r++)
            {
               rowCheck=rowCheck+MyGrid[r][c];
 
               if (r==8 && rowCheck==45)
                   status=true;
               else if (r==8&&rowCheck != 45)
                   return false;
               if(MyGrid[r][c]==MyGrid[r+1][c])
                   return false;
            }
           
            if (rowCheck==45)
               rowCheck=0;
        }
    }    
/***********************************************************
                Verify 3x3 Blocks
***********************************************************/
int row=3;
int col=9;
int rs = 0;
int cs = 0;
       for (int b=0; b < 9; b=b+3)
       {
           
           
        for (int c=cs; c < col; c++)
        {
 
            for (int r=rs;r < row; r++)
            {
               if (ctr>27&&ctr<55)
               {
                row=6;
                rs=3;
               }
               if (ctr>58)
               {
                row=9;
                rs=6;
               }
 
               ctr=ctr+1;
               
 
            if (ctr<28||(ctr<58&&ctr>30)||ctr>60)
            {
               blockCheck=blockCheck+MyGrid[r][c];
               
                     if ((ctr==9||ctr==18||ctr==27||ctr==39||ctr==48||ctr==57||ctr==69||ctr==78||ctr==87) && blockCheck==45)
                        status=true;
                       
                     if ((ctr==9||ctr==18||ctr==27||ctr==39||ctr==48||ctr==57||ctr==69||ctr==78||ctr==87) && blockCheck!=45)
                        return false;      
            }
               
           
                if (blockCheck==45)
                     blockCheck=0;
            }
        }  
    }  
    return status;
           
}
