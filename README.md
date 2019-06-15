# DeployBot
Laravel Zero Deployment Bot for Envoyer.io.

This package gives you a starting point for separating your deployment hooks 
into an easily update-able cli that notifies you on slack for every step.
Example commands for working with various tasks are included.
Create a fork to customize this code for your deployment workflow.

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

#### Update Command BuiltIn
Install it un-compiled on your server and run `deploybot update` to pull in your latest changes.

```
deploybot update
```

### Envoyer Hooks
```
DeployBot-PreClone
deploybot pre:clone staging {{ release }} {{ sha }};

DeployBot-PostClone
deploybot post:clone staging {{ release }} {{ sha }};

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
```
