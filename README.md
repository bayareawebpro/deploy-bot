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
SLACK_USERNAME=DeployBot
SLACK_EMOJI=:robot_face:

SNAPSHOTS_PATH=/home/forge/snapshots
ENVOYER_PRODUCTION_ID=XXX
ENVOYER_PRODUCTION_URL=https://app.com
ENVOYER_STAGING_ID=XXX
ENVOYER_STAGING_URL=https://staging.app.com
```

### Built-In Updater 
Install it un-compiled on your server and run `deploybot update` to pull in your latest changes and update dependencies.
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
Copy these hooks to your envoyer.io project and customize the commands for your needs.
```
#DeployBot-PreClone
php ~/deploy-bot/deploybot pre:clone staging {{ release }} {{ sha }};

#DeployBot-PostClone
php ~/deploy-bot/deploybot post:clone staging {{ release }} {{ sha }};

#DeployBot-PreInstall
php ~/deploy-bot/deploybot pre:install staging {{ release }} {{ sha }};

#DeployBot-PostInstall
php ~/deploy-bot/deploybot post:install staging {{ release }} {{ sha }};

#DeployBot-PreActivate
php ~/deploy-bot/deploybot pre:activate staging {{ release }} {{ sha }};

#DeployBot-PostActivate
php ~/deploy-bot/deploybot post:activate staging {{ release }} {{ sha }};

#DeployBot-PrePurge
php ~/deploy-bot/deploybot pre:purge staging {{ release }} {{ sha }};

#DeployBot-PostPurge
php ~/deploy-bot/deploybot post:purge staging {{ release }} {{ sha }};
```
