//
//  AppDelegate.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//

import UIKit
import GoogleMaps
import UserNotifications
import Firebase
import FirebaseMessaging
import AudioToolbox
import IQKeyboardManagerSwift
import FacebookCore
import FirebaseInstanceID

@UIApplicationMain
class AppDelegate: UIResponder, UIApplicationDelegate {

    var window: UIWindow?
    let reachability = Reachability()!
    let cellOwner = TableCellOwner()
    var deviceOrientation = UIInterfaceOrientationMask.portrait
    var mainTabVC: MainTabViewController?
    
    func application(_ application: UIApplication, didFinishLaunchingWithOptions launchOptions: [UIApplicationLaunchOptionsKey: Any]?) -> Bool {
        _ = ApplicationData.sharedInstance()
        
        if #available(iOS 13.0, *) {} else {
            if let statusBar = UIApplication.shared.value(forKey: "statusBar") as? UIView {
                statusBar.backgroundColor = UIColor.AppColor()
            }
        }
        
        TestFairy.disableVideo()
        TestFairy.didLastSessionCrash()
        
        // Keyboard manager
        IQKeyboardManager.shared.enable = true
        
        // Firebase
        var filePath = NULL_STRING
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            filePath = Bundle.main.path(forResource: "GoogleService-Info-Easymm", ofType: "plist")!
        } else {
            filePath = Bundle.main.path(forResource: "GoogleService-Info", ofType: "plist")!
        }
        
        let options = FirebaseOptions(contentsOfFile: filePath)
        FirebaseApp.configure(options: options!)
        
        Messaging.messaging().delegate = self
        // APNS
        registerForPushNotifications()
        // Add observer for InstanceID token refresh callback.
        NotificationCenter.default.addObserver(self,
                                                         selector: #selector(self.tokenRefreshNotification),
                                                         name: NSNotification.Name.InstanceIDTokenRefresh,
                                                         object: nil)
        
        // Facebook
        ApplicationDelegate.shared.application(application, didFinishLaunchingWithOptions: launchOptions)
        
        GMSServices.provideAPIKey(Environment().configuration(PlistKey.GoogleMapKey))

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
    
    func application(_ app: UIApplication, open url: URL, options: [UIApplicationOpenURLOptionsKey : Any] = [:]) -> Bool {
        print("absoluteString: \(url.absoluteString)")
        if let dicURL = URL(string: url.absoluteString) {
            print("code: \(dicURL["code"])")
            if let code = dicURL["code"] as? String {
                ApplicationData.accessCodeFromURL = code
                NotificationCenter.default.post(name: .accessCodeAPI, object: nil, userInfo: nil)
            }
        }
        
        let facebookHandler = ApplicationDelegate.shared.application(app, open: url, options: options)
        return facebookHandler
    }
    
    func application(_ application: UIApplication, didRegisterForRemoteNotificationsWithDeviceToken deviceToken: Data) {
        let deviceTokenString = deviceToken.reduce("", {$0 + String(format: "%02X", $1)})
        print(deviceTokenString)
        Messaging.messaging().apnsToken = deviceToken
    }
    
    func applicationWillResignActive(_ application: UIApplication) {}

    func applicationDidEnterBackground(_ application: UIApplication) {}

    func applicationWillEnterForeground(_ application: UIApplication) {}

    func applicationDidBecomeActive(_ application: UIApplication) {}

    func applicationWillTerminate(_ application: UIApplication) {}
    
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
}

extension AppDelegate: UNUserNotificationCenterDelegate {

    func application(_ application: UIApplication, didReceiveRemoteNotification userInfo: [AnyHashable: Any]) {
        // If you are receiving a notification message while your app is in the background,
        // this callback will not be fired till the user taps on the notification launching the application.
        // TODO: Handle data of notification
    }
    
    func userNotificationCenter(_ center: UNUserNotificationCenter, didReceive response: UNNotificationResponse, withCompletionHandler completionHandler: @escaping () -> Void) {
        
        if let userInfo = response.notification.request.content.userInfo as? [String : Any] {
            
            // print("PUSH NOTI \(userInfo)")
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
            // print("PUSH NOTI \(dic)")
            
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


