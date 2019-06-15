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

#### Update Command BuiltIn
Install it un-compiled on your server and run `deploybot update` to pull in your latest changes.
```
deploybot update
```

### ~/.profile Shortcut
```
# DeployBot
function deploybot(){
    php ~/deploy-bot/deploybot "$@"
}
```

### Custom Scripts (resources/scripts)
```
$bash = Bash::script("local", 'my/script', "my arguments");

$bash->isSuccessful();

$collection = $bash->output();

if($error = $collection->where('type', 'error')->first()){
    dd($error->buffer);
}
```

### Envoyer Hooks
Copy these hooks to your envoyer.io project.
```
#DeployBot-PreClone
source ~/.profile;
deploybot pre:clone staging {{ release }} {{ sha }};

#DeployBot-PostClone
source ~/.profile;
deploybot post:clone staging {{ release }} {{ sha }};

#DeployBot-PreInstall
source ~/.profile;
deploybot pre:install staging {{ release }} {{ sha }};

#DeployBot-PostInstall
source ~/.profile;
deploybot post:install staging {{ release }} {{ sha }};

#DeployBot-PreActivate
source ~/.profile;
deploybot pre:activate staging {{ release }} {{ sha }};

#DeployBot-PostActivate
source ~/.profile;
deploybot post:activate staging {{ release }} {{ sha }};

#DeployBot-PrePurge
source ~/.profile;
deploybot pre:purge staging {{ release }} {{ sha }};

#DeployBot-PostPurge
source ~/.profile;
deploybot post:purge staging {{ release }} {{ sha }};
```
