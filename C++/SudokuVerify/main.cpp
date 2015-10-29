/*********************************************************************
   PROGRAM:    SudokuVerify
   FILE:       main.cpp
   BUILD DATE: 9/28/2012
 
   FUNCTION:   This program will check if a Sudoku Puzzle is indeed
               a valid, sovable puzzle
*********************************************************************/  
#include <iostream>
#include <string>
#include "Verifier.h"
 
using std::cout;
using std::endl;
using std::string;
 
#define NUM_FILES 4
 
int main()
   {
   Verifier v;
   string fileName;
   
   cout << "Sudoku Verifier\n";
   
   for (int i = 1; i <= NUM_FILES; i++)
      {
      cout << endl;
 
      // Construct file pathname
      fileName = string("testpuzzle")
        + (char)('0' + i) + ".txt";
     
      // Read the solution file as input
      v.readGrid(fileName.c_str());
   
      // Print the Sudoku grid
      v.printGrid();
 
      // Verify whether or not the solution is correct
        if (v.verifySolution())
            cout << "\nThis is a valid Sudoku solution\n";
        else
            cout << "\nThis is not a valid Sudoku solution\n";
      }
      system("pause");
   return 0;
   }
  
