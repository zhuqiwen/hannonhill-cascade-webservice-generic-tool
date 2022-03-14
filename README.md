# Generic WCMS WebService Tool
This tool simplifies user's work on using WCMS WebService to manipulate assets.

## Asset types supported

Before operating any asset, a WCMS SOAP client should initiated with url to WSDL, site name, and api key:
```PHP
$wcms = new WCMSClient($wsdl, $sitename);
$wcms->setAuthByKey($apikey);
```

### Block: Data Definition
#### Read
```PHP
$pathToBlock = "/Settings";
$block = new BlockXHTML($wcms, $pathToBlock);
var_dump($block->oldAsset);
```
#### Update
#### Create
```PHP
$assetPayload = (object)[];
$block = new BlockXHTML($wcms);
$block->setNewAsset($assetPayload);
$block->createAsset();
```
#### Delete
### Block: Index
### Block: Feed
### Block: Text
### File
### Page
### Folder
### Content Type
### Asset Factory
### Data Definition
### Format: Velocity
### Symlink
### Template
