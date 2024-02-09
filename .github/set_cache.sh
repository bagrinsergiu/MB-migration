#!/usr/bin/env bash
#!/usr/bin/grep bash

NAME=$1

NODE_PATHS=$(find . -type d -regex '.*node_modules')
FILTERED_PATHS=$(
  for npath in $NODE_PATHS; do
    NESTED_NODE_FOLDER_COUNT=$(echo $npath | grep -o "node_modules" | wc -l)
    if [ $NESTED_NODE_FOLDER_COUNT -eq 1 ];
    then
      echo $npath
    fi
  done
)

tar -czf $NAME $FILTERED_PATHS
