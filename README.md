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
$assetPayload = [];
//required
$assetPayload['structuredData'] = [
    'definitionPath' => 'path-to-data-definition',
    // OR
    //'definitionId' => 'data definition id'
];
//OR required
//$assetPayload['xhtml'] = '';
//required
$assetPayload['parentFolderPath'] = "path-to-parent-folder";
//OR required
//$assetPayload['parentFolderId'] = "parent-folder-id";
$assetPayload['name'] = "asset-name";
$assetPayload = (object) $assetPayload;
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


## How-To
### When creating new asset, how to check if new asset is created or the asset already exists?
By checking if $asset->oldAsset attribute is set and $asset->newAsset has a $id key set
1. if $asset->getOldAsset() returns `null`, or $asset->getNewAsset()->id is set, a new asset has been created by createAsset()
2. if $asset->getOldAsset() returns asset data or $asset->getNewAsset()->id is Not set, the asset meant to be created has already existed.