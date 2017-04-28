#!/bin/bash

# 
# Ez a script egy halom holdfenykepet atkonvertal png-be,
# webre szant kisebb meretet, es meg kisebb thumbnail-t gyart,
# vegul legyartja a <table> reszt a html oldalhoz
#
# Bovebben: http://sala.melda.info/hold_2010/

# Kisbetu
#for i in imgp*.DNG; do mv $i `echo $i | tr A-Z a-z`; done

# Alap expozicio
baseiso=3200
baseshutter=250

# Expozicio korrekcio kiszamitasa
function expo_korrekcio() {
  evmult=`echo ${1}/${baseiso}*${baseshutter}/${2} | bc -l`;
  evdiff=`echo -l\(${evmult}\)/l\(2\) | bc -l`;
  toobig=`echo ${evdiff}\<-2 | bc -l`;
  if [ ${toobig} == 1 ]; then
      evdiff=-2;
  fi    
}

# Ufrawbol sajat verzio, a kepszeli zaj levagasa miatt
UFRAW_HOME=/usr/local/cvs/ufraw

for i in imgp*.dng; do
  # Átnevezés hogy konnyebb legyen a sok file megkulonboztetese
  iso=`exiftool -iso $i | cut -f 2 -d ':' | sed -e 's/ //g' | sed -e :a -e 's/^.\{1,3\}$/0&/;ta';`;
#  echo $iso;
  shutter=`exiftool -shutterspeed $i | cut -f 2 -d ':' | cut -f 2 -d '/' | sed -e 's/ //g' | sed -e :a -e 's/^.\{1,3\}$/0&/;ta';`;
#  echo $shutter;
  expo_korrekcio $iso $shutter;
  #echo ${evdiff}  
  name=hold_original_$iso\_$shutter;
  # megsem atnevezes lesz csak symlink
  #cp $i ${name}.dng;
  ln -s ${i} ${name}.dng
  # dng->png
  $UFRAW_HOME/ufraw-batch --rotate=no --overwrite --temperature=5500 --out-type=png --out-depth=16 --exposure=0 ${name}.dng
  $UFRAW_HOME/ufraw-batch  --rotate=no --overwrite --temperature=5500 --out-type=png --out-depth=16 --exposure=${evdiff} --output=${name}_exp.png ${name}.dng;
  convert ${name}.png -scale 200x200 ${name}_thumb.jpg
  convert ${name}.png -scale 1000x1000 ${name}_web.jpg;
  convert ${name}_exp.png -scale 200x200 ${name}_exp_thumb.jpg
  convert ${name}_exp.png -scale 1000x1000 ${name}_exp_web.jpg;
done;

echo "<table><tr><td class='header'>&nbsp;</td>" > table.html
for j in `ls hold_original*.png | cut -f 3 -d '_' | sort -u`; do
  echo "<td class='header'>ISO ${j}</td>" >> table.html
done
echo '</tr>' >> table.html
for i in `ls hold_original*.png | cut -f 4 -d '_' | cut -f 1 -d '.' | sort -u`; do
  echo '<tr>' >> table.html
  echo "<td class='header'>1/${i}</td>" >> table.html
  for j in `ls hold_original*.png | cut -f 3 -d '_' | sort -u`; do
    echo -e "<td class='thumb6'><a href='hold_original_${j}_${i}_web.jpg'><img src='hold_original_${j}_${i}_thumb.jpg' alt='hold $j $i'/></a></td>" >> table.html
  done;
  echo "</tr>" >> table.html
done
echo '</table>' >> table.html

echo "<table><tr><td class='header'>&nbsp;</td>" >> table.html
for j in `ls hold_original*.png | cut -f 3 -d '_' | sort -u`; do
  echo "<td class='header'>ISO ${j}</td>" >> table.html
done
echo '</tr>' >> table.html
for i in `ls hold_original*.png | cut -f 4 -d '_' | cut -f 1 -d '.' | sort -u`; do
  echo '<tr>' >> table.html
  echo "<td class='header'>1/${i}</td>" >> table.html
  for j in `ls hold_original*.png | cut -f 3 -d '_' | sort -u`; do
    expo_korrekcio ${j} ${i};
    echo -e "<td class='thumb6'><a href='hold_original_${j}_${i}_exp_web.jpg'><img src='hold_original_${j}_${i}_exp_thumb.jpg' alt='hold $j $i' title='expozíció korrekció: ${evdiff}' /></a></td>" >> table.html
  done;
  echo "</tr>" >> table.html
done
echo '</table>' >> table.html
