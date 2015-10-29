/*****************************************************************
   FILE:       Verifier.h
   BUILD DATE: 9/28/2012
 
   PURPOSE:    Contains the declaration for the Verifier class.
*****************************************************************/
 
    #ifndef VERIFIER_H
    #define VERIFIER_H
 
class Verifier
    {
      //Adding the access modifiers: private and public
        private:
                int MyGrid [9][9];
        public:
                Verifier();
                void readGrid(const char* inputFile);
                void printGrid();
                bool verifySolution();
    };
 
    #endif
