//
//  TabFavouritesViewController.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//

import UIKit

class TabFavouritesVC: SuperViewController {
    
    override func viewDidLoad() {
        super.viewDidLoad()
        self.navigationController?.isNavigationBarHidden = true
        
        if let userData = ApplicationData.sharedInstance().getUserData() as? UserData {
            print(userData.isNotification)
            print(userData.isVibration)
            print(userData.isSound)
        }
    }
}
