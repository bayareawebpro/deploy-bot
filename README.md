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

### ~/.profile Shortcut
```
# DeployBot
function deploybot(){
    php ~/deploy-bot/deploybot "$@"
}
```

### Update Package Command
```
deploybot update
```

```
DeployBot-PreClone
deploybot pre:clone staging {{ release }} {{ sha }};

DeployBot-PostClone
deploybot pre:clone staging {{ release }} {{ sha }};

DeployBot-PreInstall
deploybot pre:install staging {{ release }} {{ sha }};

DeployBot-PostInstall
deploybot post:install staging {{ release }} {{ sha }};

DeployBot-PreActivate
deploybot pre:activate staging {{ release }} {{ sha }};

DeployBot-PostActivate
deploybot post:activate staging {{ release }} {{ sha }};

DeployBot-PrePurge
deploybot pre:purge staging {{ release }} {{ sha }};

DeployBot-PostPurge
deploybot post:purge staging {{ release }} {{ sha }};


deploybot post:clone "staging" "/home/forge/default/current" "XXX"

```
