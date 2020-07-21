# SourceKnowledge Shopping Ads for Magento 2
Our Magento 2 plugin will automatically add SourceKnowledge pixels to your store and generate a shopping feed from your catalog.

In just a few steps, you can install the SourceKnowledge App and turn your Magento 2 catalog into shopping ads. Once you 
connect SourceKnowledge to Magento 2 store, we instantly create prospecting and retargeting shopping ads based on your 
existing catalog and help you run them on all comparison shopping and influencer websites. We create audience segments 
like shopping cart abandoners and past purchasers so you don't have to. 

## How to install SourceKnowledge Shopping Ads Plugin extension?
1. In Magento 2 `app/code/` directory of your instance, create the folder structure `Sourceknowledge/ShoppingAds`
2. Extract the package and copy the folder to `app/code/Sourceknowledge/ShoppingAds`
3. Run the following command in Magento 2 root folder:
    ```
    php bin/magento setup:upgrade
    ```
4. Recompile your Magento project & clear Cache
    ```
    bin/magento setup:di:compile
    bin/magento cache:clean
    ```
5. Open admin panel of your Magento 2 store and goto **System** > **Integrations**
6. Find the integration **Sourceknowledge_ShoppingAds** and click **Activate** to grant the required permissions.
7. When asked about Sourceknowledge account, choose "Existing" or "New Client" to complete the activation process.
8. If you already have an account with Sourceknowledge, log in with your credentials or signup to complete the activation process. 
