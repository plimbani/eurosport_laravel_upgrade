//
//  AppDelegate.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//

import UIKit
import Fabric
import Crashlytics
import GoogleMaps
import UserNotifications
import Firebase
import FirebaseMessaging
import AudioToolbox
import IQKeyboardManagerSwift

@UIApplicationMain
class AppDelegate: UIResponder, UIApplicationDelegate {

    var window: UIWindow?
    let reachability = Reachability()!
    let cellOwner = TableCellOwner()
    var deviceOrientation = UIInterfaceOrientationMask.portrait
    var mainTabVC: MainTabViewController?
    
    func application(_ application: UIApplication, didFinishLaunchingWithOptions launchOptions: [UIApplicationLaunchOptionsKey: Any]?) -> Bool {
        _ = ApplicationData.sharedInstance()
        // Keyboard manager
        IQKeyboardManager.shared.enable = true
        
        // Firebase
        FirebaseApp.configure()
        
        Messaging.messaging().delegate = self
        // APNS
        registerForPushNotifications()
        // Add observer for InstanceID token refresh callback.
        NotificationCenter.default.addObserver(self,
                                                         selector: #selector(self.tokenRefreshNotification),
                                                         name: NSNotification.Name.InstanceIDTokenRefresh,
                                                         object: nil)
        
        updateToken()
        if let statusBar = UIApplication.shared.value(forKey: "statusBar") as? UIView {
            statusBar.backgroundColor = UIColor.AppColor()
        }
        
        GMSServices.provideAPIKey(Environment().configuration(PlistKey.GoogleMapKey))
        // Fabric
        Fabric.with([Crashlytics.self])
        
        if let userData = ApplicationData.sharedInstance().getUserData() {
           // Notifies app to change language
            Bundle.set(languageCode: userData.locale)
        }
        return true
    }
    
    func application(_ application: UIApplication, supportedInterfaceOrientationsFor window: UIWindow?) -> UIInterfaceOrientationMask {
        return deviceOrientation
    }
    
    func registerForPushNotifications() {
        UNUserNotificationCenter.current()
            .requestAuthorization(options: [.alert, .sound, .badge]) {
                [weak self] granted, error in
                
                print("Permission granted: \(granted)")
                guard granted else { return }
                self?.getNotificationSettings()
        }
        
        UNUserNotificationCenter.current().delegate = self
    }
    
    func getNotificationSettings() {
        UNUserNotificationCenter.current().getNotificationSettings { settings in
            print("Notification settings: \(settings)")
            guard settings.authorizationStatus == .authorized else { return }
            DispatchQueue.main.async {
                UIApplication.shared.registerForRemoteNotifications()
            }
        }
    }
    
    @objc func tokenRefreshNotification(notification: NSNotification) {
        InstanceID.instanceID().instanceID { (result, error) in
            if let error = error {
                print("Error fetching remote instange ID: \(error)")
            } else if let result = result {
                print("Remote instance ID token: \(result.token)")
                USERDEFAULTS.set(result.token, forKey: kUserDefaults.fcmToken)
                self.updateFCMToken(result.token)
            }
        }
        
        // Connect to FCM since connection may have failed when attempted before having a token.
        connectToFcm()
    }
    // [END refresh_token]
    
    // [START connect_to_fcm]
    func connectToFcm() {
        Messaging.messaging().connect { (error) in
            if (error != nil) {
                print("Unable to connect with FCM. \(error)")
            } else {
                print("Connected to FCM.")
            }
        }
    }
    
    func application(_ application: UIApplication, continue userActivity: NSUserActivity, restorationHandler: @escaping ([Any]?) -> Void) -> Bool {
        
        guard userActivity.activityType == NSUserActivityTypeBrowsingWeb,
            let incomingURL = userActivity.webpageURL,
            let components = NSURLComponents(url: incomingURL, resolvingAgainstBaseURL: true),
            let path = components.path else {
                return false
        }
        
        print("path = \(path)")
        
        let arrayString = path.split(separator: "/")
        let delay = UIApplication.shared.applicationState == .inactive ? 1 : 0.5
        
        if arrayString.count > 0 {
            /*if arrayString[0] == "traders" {
                APPDELEGATE.tabBarController?.selectedIndex = TabIndex.tabMarket.rawValue
                DispatchQueue.main.asyncAfter(deadline: .now() + delay) {
                    NotificationCenter.default.post(name: .universalLinkNavTradersDetails, object: nil, userInfo: ["slug" : arrayString[1]])
                }
                
            } else if arrayString[0] == "trader" {
                
            } else if arrayString[0] == "events" {
                APPDELEGATE.tabBarController?.selectedIndex = TabIndex.tabOffer.rawValue
                DispatchQueue.main.asyncAfter(deadline: .now() + delay) {
                    NotificationCenter.default.post(name: .universalLinkNavEventsDetails, object: nil, userInfo: ["slug" : arrayString[1]])
                }
            } else if arrayString[0] == "products" {
                APPDELEGATE.tabBarController?.selectedIndex = TabIndex.tabOffer.rawValue
                DispatchQueue.main.asyncAfter(deadline: .now() + delay) {
                    NotificationCenter.default.post(name: .universalLinkNavProductsDetails, object: nil, userInfo: ["slug" : arrayString[1]])
                }
            }*/
        }
        
        return false
    }
    
    func application(_ application: UIApplication, didRegisterForRemoteNotificationsWithDeviceToken deviceToken: Data) {
        let deviceTokenString = deviceToken.reduce("", {$0 + String(format: "%02X", $1)})
        print(deviceTokenString)
        Messaging.messaging().apnsToken = deviceToken
    }
    
    func applicationWillResignActive(_ application: UIApplication) {
        // Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
        // Use this method to pause ongoing tasks, disable timers, and invalidate graphics rendering callbacks. Games should use this method to pause the game.
    }

    func applicationDidEnterBackground(_ application: UIApplication) {
        // Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later.
        // If your application supports background execution, this method is called instead of applicationWillTerminate: when the user quits.
    }

    func applicationWillEnterForeground(_ application: UIApplication) {
        // Called as part of the transition from the background to the active state; here you can undo many of the changes made on entering the background.
        updateToken()
    }

    func applicationDidBecomeActive(_ application: UIApplication) {
        // Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
    }

    func applicationWillTerminate(_ application: UIApplication) {
        // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
    }
    
    func updateFCMToken(_ token: String) {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        var parameters: [String: Any] = [:]
        
        if let email = USERDEFAULTS.value(forKey: kUserDefaults.email) as? String {
            parameters["email"] = email
            parameters["fcm_id"] = token
            
            ApiManager().updateFCMTokem(parameters, success: { result in
                DispatchQueue.main.async {
                    
                }
            }, failure: { result in
                DispatchQueue.main.async {
                    if result.allKeys.count == 0 {
                        return
                    }
                }
            })
        }
    }
    
    func updateToken() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        var parameters: [String: Any] = [:]
        
        if let email = USERDEFAULTS.value(forKey: kUserDefaults.email) as? String,  let password = USERDEFAULTS.value(forKey: kUserDefaults.password) as? String {
            parameters["email"] = email
            parameters["password"] = password
            
            ApiManager().login(parameters, success: { result in
                DispatchQueue.main.async {
                    if let token = result.value(forKey: "token") as? String {
                        USERDEFAULTS.set(token, forKey: kUserDefaults.token)
                    }
                }
            }, failure: { result in
                DispatchQueue.main.async {
                    if result.allKeys.count == 0 {
                        return
                    }
                }
            })
        }
    }
}

extension AppDelegate: MessagingDelegate {
    func messaging(_ messaging: Messaging, didReceiveRegistrationToken fcmToken: String) {
        print("Firebase registration token: \(fcmToken)")
        USERDEFAULTS.set(fcmToken, forKey: kUserDefaults.fcmToken)
        let dataDict:[String: String] = ["token": fcmToken]
        NotificationCenter.default.post(name: Notification.Name("FCMToken"), object: nil, userInfo: dataDict)
        // TODO: If necessary send token to application server.
        // Note: This callback is fired at each app startup and whenever a new token is generated.
    }
    
    /*func messaging(_ messaging: Messaging, didReceive remoteMessage: MessagingRemoteMessage) {
        print(remoteMessage)
    }*/
}

extension AppDelegate: UNUserNotificationCenterDelegate {
//    func userNotificationCenter(_ center: UNUserNotificationCenter, willPresent notification: UNNotification, withCompletionHandler completionHandler: @escaping (UNNotificationPresentationOptions) -> Void) {
//        
//        print("Voila got first notification...!")
//        completionHandler([.alert,.sound, .badge])
//    }
    
    func application(_ application: UIApplication, didReceiveRemoteNotification userInfo: [AnyHashable: Any]) {
        // If you are receiving a notification message while your app is in the background,
        // this callback will not be fired till the user taps on the notification launching the application.
        // TODO: Handle data of notification
    }
    
    func userNotificationCenter(_ center: UNUserNotificationCenter, didReceive response: UNNotificationResponse, withCompletionHandler completionHandler: @escaping () -> Void) {
        if let userInfo = response.notification.request.content.userInfo as? [String : Any] {
            if let dic = userInfo["aps"] as? NSDictionary {
                if let alertMessage = dic.value(forKey: "alert") as? NSDictionary {
                    
                        
                        if USERDEFAULTS.bool(forKey: kUserDefaults.isSound) {
                            AudioServicesPlaySystemSound(1315)
                        }
                        
                        let alert = UIAlertController(title: alertMessage.value(forKey:"title") as? String, message: alertMessage.value(forKey: "body") as? String, preferredStyle: .alert)
                        alert.addAction(UIAlertAction(title: "OK", style: .default, handler: { action in
                            switch action.style{
                            case .default:
                                print("default")
                            case .cancel:
                                print("cancel")
                            case .destructive:
                                print("destructive")
                            }}))
                        
                        self.window?.rootViewController?.present(alert, animated: true, completion: nil)
                    
                }
            }
        }
        completionHandler()
    }
    
   func application(_ application: UIApplication, didReceiveRemoteNotification userInfo: [AnyHashable : Any], fetchCompletionHandler completionHandler: @escaping (UIBackgroundFetchResult) -> Void) {
    
        if let dic = userInfo["aps"] as? NSDictionary {
            if let alertMessage = dic.value(forKey: "alert") as? NSDictionary {
                if application.applicationState == .active {
                    
                    if USERDEFAULTS.bool(forKey: kUserDefaults.isSound) {
                       AudioServicesPlaySystemSound(1315)
                    }
                    
                    let alert = UIAlertController(title: alertMessage.value(forKey:"title") as? String, message: alertMessage.value(forKey: "body") as? String, preferredStyle: .alert)
                    alert.addAction(UIAlertAction(title: "OK", style: .default, handler: { action in
                        switch action.style{
                            case .default:
                                print("default")
                            case .cancel:
                                print("cancel")
                            case .destructive:
                                print("destructive")
                        }}))
                    
                    self.window?.rootViewController?.present(alert, animated: true, completion: nil)
                }
            }
        }
    }
}


