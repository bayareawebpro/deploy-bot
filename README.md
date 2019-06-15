# DeployBot
Laravel Zero Deployment Bot for Envoyer.io

```
#Environment Settings
SLACK_ENDPOINT=https://hooks.slack.com/services/XXX
SLACK_CHANNEL=#logger
SLACK_USERNAME=DbTool
SLACK_EMOJI=:robot_face:

SNAPSHOTS_PATH=/home/forge/snapshots
ENVOYER_PRODUCTION_ID=XXX
ENVOYER_PRODUCTION_URL=https://app.com
ENVOYER_STAGING_ID=XXX
ENVOYER_STAGING_URL=https://staging.app.com
```

### Update Package Command
```
php deploybot update
```

### ~/.profile Shortcut
```
# DeployBot
function deploybot(){
    php ~/deploy-bot/deploybot "$@"
}
```

deploybot pre:clone staging {{ release }} {{ sha }};
deploybot post:clone staging {{ release }} {{ sha }};


deploybot pre:clone "staging" "/home/forge/default/current" "XXX"
deploybot post:clone "staging" "/home/forge/default/current" "XXX"
