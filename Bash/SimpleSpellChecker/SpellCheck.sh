#!/bin/bash
#############################################  
# Script:     Basic Spell Check															#
# Purpose:  Performs a basic Spell Check on given file using ispell.   #
#############################################
 
#initialize required temp files and variables
touch tempSpell
touch tempInput
fixedSpell=()
let cnt=1
 
#read in the mispelled words of given file.
#then store in the temp file 'tempInput'
tempInput=(`ispell -l -p ~/.fix < $1`)
 
for i in ${tempInput[@]};
do
read -p "$i is mispelled. Press \"Enter\" to keep this spelling, or type a correction here: " fxdInput
 
#storage loop
   if [[ "$fxdInput" = "" ]];
      then
         echo $i >> .tempSpell
   else
      fixedSpell[$cnt]=$fxdInput
 
   fi
#increase the counter variable
cnt+=1
echo ""
done
 
#print table column headers
printf "MISPELLED:     "
printf "CORRECTIONS: "
echo ""
 
#simple print loop
#formats and prints both
#the mispelled and correct words
let cnt=1
for i in ${tempInput[*]}
do
   printf "%-15s%s\n" "$i" "${fixedSpell[$cnt]}"
   cnt+=1
done