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

@UIApplicationMain
class AppDelegate: UIResponder, UIApplicationDelegate {

    var window: UIWindow?
    // Tab controller global instance
    let reachability = Reachability()!
    let cellOwner = TableCellOwner()
    var infoAlertViewTwoButton: CustomAlertViewTwoButton!
    
    func application(_ application: UIApplication, didFinishLaunchingWithOptions launchOptions: [UIApplicationLaunchOptionsKey: Any]?) -> Bool {
        
        if let statusBar = UIApplication.shared.value(forKey: "statusBar") as? UIView {
            statusBar.backgroundColor = UIColor.AppColor()
        }
        
        _ = ApplicationData.sharedInstance()
        GMSServices.provideAPIKey(Environment().configuration(PlistKey.GoogleMapKey))
        // Fabric
        Fabric.with([Crashlytics.self])
        return true
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
                    
                    // if let error = result.value(forKey: "error") as? String {
                        // self.showInfoAlertView(title: String.localize(key: "alert_title_error"), message: error)
                    //}
                }
            })
        }
    }
}
