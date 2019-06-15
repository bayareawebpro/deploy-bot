# DbTool
Laravel Zero Database Tool

```
#Environment Settings
SNAPSHOTS_PATH=/home/forge/snapshots
SLACK_ENDPOINT=https://hooks.slack.com/services/XXX
SLACK_CHANNEL=#logger
SLACK_USERNAME=DbTool
SLACK_EMOJI=:robot_face:
```

### Update Package Command
```
php dbtool update:package
```

### Snapshot Action Command
```
php /home/forge/dbtool/dbtool snapshots:run staging {{ sha }};
```
```
php /home/forge/dbtool/dbtool snapshots:run production {{ sha }};
```

### Pre Deployment Notification Action
```
BTN="Envoyer.io";
URL="https://envoyer.io/projects/46981";
TEXT="*Staging Deployment In-Progress*";
php /home/forge/dbtool/dbtool notify:slack "$TEXT" "$BTN" "$URL";
```


### Post Deployment Notification Action
```
BTN="View Release";
URL="http://cool.app";
TEXT="*Staging Deployment Completed Successfully!*";
php /home/forge/dbtool/dbtool notify:slack "$TEXT" "$BTN" "$URL";
```
