for file in * ; do            # or *.jpg, or x*.jpg, or whatever
   basename=$(tr '[:lower:]' '[:upper:]' <<< "${file%.*}")
   newname="$basename.${file#*.}"
   mv "$file" "$newname"
done
