#!/bin/bash
#for i in IMGP*.DNG; do mv $i `echo $i | tr A-Z a-z`; done

baseiso=3200
baseshutter=250

UFRAW_HOME=/usr/local/cvs/ufraw

# Konvertalas
for i in imgp*.dng; do
  exiftool -exif:FocalLength=1300 $i
  exiftool -exif:FocalLengthIn35mmFormat=1950 $i
  iso=`exiftool -iso $i | cut -f 2 -d ':' | sed -e 's/ //g' | sed -e :a -e 's/^.\{1,3\}$/0&/;ta';`;
#  echo $iso;
  shutter=`exiftool -shutterspeed $i | cut -f 2 -d ':' | cut -f 2 -d '/' | sed -e 's/ //g' | sed -e :a -e 's/^.\{1,3\}$/0&/;ta';`;
#  echo $shutter;
  dirname=hold_$iso\_$shutter;
  bname=`basename $i .dng`
  mkdir $dirname 2> /dev/null;
  ln -s ../${i} ${dirname}/.
  $UFRAW_HOME/ufraw-batch --overwrite --rotate=no --temperature=5500 --out-type=png --out-depth=16 --output=$dirname/${bname}.png ${dirname}/${bname}.dng;
done;

echo "Staring alignment"; 

OLD_CURRDIR=`pwd`

CROPSIZE=250x250

for i in `find -type d -name "hold_*"`; do
cd $i;
currdir=`pwd`
echo Processing `basename $currdir`;
resultname=result_`basename $currdir`
# sample
convert `find . -name "imgp*.png" | head -1` ${resultname}_sample.jpg
convert `find . -name "imgp*.png" | head -1` -scale 1500x1500 ${resultname}_sample_web.jpg
convert `find . -name "imgp*.png" | head -1` -scale 200x200 ${resultname}_sample_thumb.jpg
align_image_stack -v -a `basename $currdir`_ *.png
enfuse -o ${resultname}.tif `basename $currdir`*.tif
convert ${resultname}.tif -scale 200x200 ${resultname}_thumb.jpg
convert ${resultname}.tif -scale 1500x1500 ${resultname}_web.jpg;
rm -f `basename $currdir`*.tif
cd ${OLD_CURRDIR}
done;

#Kis reszlet kinagyitasa
convert hold_0200_0250/result_hold_0200_0250.tif -crop ${CROPSIZE}+2000+1440 hold_0200_0250/result_hold_0200_0250_crop.jpg
convert hold_1600_0500/result_hold_1600_0500.tif -crop ${CROPSIZE}+1600+1440 hold_1600_0500/result_hold_1600_0500_crop.jpg
convert hold_1600_2000/result_hold_1600_2000.tif -crop ${CROPSIZE}+2000+1440 hold_1600_2000/result_hold_1600_2000_crop.jpg
convert hold_6400_2000/result_hold_6400_2000.tif -crop ${CROPSIZE}+1800+1360 hold_6400_2000/result_hold_6400_2000_crop.jpg
convert hold_6400_6000/result_hold_6400_6000.tif -crop ${CROPSIZE}+2400+1460 hold_6400_6000/result_hold_6400_6000_crop.jpg
convert hold_0200_0250/result_hold_0200_0250_sample.jpg -crop ${CROPSIZE}+2150+1440 hold_0200_0250/result_hold_0200_0250_sample_crop.jpg
convert hold_1600_0500/result_hold_1600_0500_sample.jpg -crop ${CROPSIZE}+1740+1440 hold_1600_0500/result_hold_1600_0500_sample_crop.jpg
convert hold_1600_2000/result_hold_1600_2000_sample.jpg -crop ${CROPSIZE}+2150+1440 hold_1600_2000/result_hold_1600_2000_sample_crop.jpg
convert hold_6400_2000/result_hold_6400_2000_sample.jpg -crop ${CROPSIZE}+2100+1440 hold_6400_2000/result_hold_6400_2000_sample_crop.jpg
convert hold_6400_6000/result_hold_6400_6000_sample.jpg -crop ${CROPSIZE}+2550+1510 hold_6400_6000/result_hold_6400_6000_sample_crop.jpg
