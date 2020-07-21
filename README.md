# SourceKnowledge Shopping Ads for Magento 2
Our Magento 2 plugin will automatically add SourceKnowledge pixels to your store and generate a shopping feed from your catalog.

In just a few steps, you can install the SourceKnowledge App and turn your Magento 2 catalog into shopping ads. Once you 
connect SourceKnowledge to Magento 2 store, we instantly create prospecting and retargeting shopping ads based on your 
existing catalog and help you run them on all comparison shopping and influencer websites. We create audience segments 
like shopping cart abandoners and past purchasers so you don't have to. 

## Compatibility
```
>=2.3.1
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
php bin/magento module:enable Sourceknowledge_ShoppingAds
php bin/magento setup:upgrade
```

Clear Cache
```
bin/magento cache:clean
```

## Configuration
1. **System** > **Integrations** > **Sourceknowledge_ShoppingAds**
2. Click **Activate** to grant the required permissions.
3. When asked about Sourceknowledge account, choose **EXISTING CLIENT** or **NEW CLIENT** otherwise, and complete the activation process.
4. If you already have an account with Sourceknowledge, log in with your credentials or sign-up to complete the activation process. 
