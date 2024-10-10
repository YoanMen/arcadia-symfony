 #!/bin/bash

watch_deploy() {
  
  watch -n10 "watch -t -g ls --full-time ./src >/dev/null && make deploy >> ./var/log/deploy.log" &
  watch -n10 "watch -t -g ls --full-time ./assets >/dev/null && make deploy >> ./var/log/deploy.log"
}

watch_deploy

# set permision chmod +x watch_deploy.sh
# to run => setsid ./watch_deploy.sh >/dev/null 2>&1 </dev/null &