# SourceKnowledge Shopping Ads for Magento 2
Automatically add SourceKnowledge pixels to your store and generate a shopping feed from your catalog to increase sales on 
your website.

In just a few steps, you can install the SourceKnowledge App and turn your Magento 2 catalog into shopping ads. Once you 
connect SourceKnowledge to your Magento 2 store, you instantly run your product catalog on comparison shopping engines, 
deal sites, native advertising and on social media.

## Compatibility
```
Magento Open Source (CE) 2.3
```

## Installation
### Install via composer (recommended)
Run the following command in Magento 2 root folder:
```
composer require sourceknowledge/shopping-ads
```

### Using GIT clone
git clone https://github.com/sourceknowledge/shopping-ads.git app/code/Sourceknowledge/ShoppingAds

## Activation
Run the following commands in Magento 2 root folder:
```
bin/magento module:enable Sourceknowledge_ShoppingAds
bin/magento setup:upgrade
bin/magento setup:static-content:deploy
```
Recompile your Magento project
```
bin/magento setup:di:compile
```
## Clear Cache
```
bin/magento cache:clean
```

## Configuration
1. **System** > **Integrations** > **Sourceknowledge_ShoppingAds**
2. Click **Activate** to grant the required permissions.
3. When asked about Sourceknowledge account, choose **EXISTING CLIENT** or **NEW CLIENT** otherwise, and complete the activation process.
4. If you already have an account with Sourceknowledge, log in with your credentials or sign-up to complete the activation process. 
