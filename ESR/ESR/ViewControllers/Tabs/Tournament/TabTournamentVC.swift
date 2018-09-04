//
//  TabTournamentVC.swift
//  ESR
//
//  Created by Pratik Patel on 14/08/18.
//

import UIKit

class TabTournamentVC: SuperViewController {

    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize() {
        self.navigationController?.isNavigationBarHidden = true
        
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        sendGetFavTournamentsRequest()
    }
    
    deinit {
        NotificationCenter.default.removeObserver(self, name: .internetConnectivity, object: nil)
    }
    
    @objc func showHideNoInternetView(_ notification: NSNotification) {
        if notification.userInfo != nil {
            if let isShow = notification.userInfo![kNotification.isShow] as? Bool {
                setConstraintLblNoInternet(isShow)
            }
        }
    }
    
    func sendGetFavTournamentsRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        if let userData = ApplicationData.sharedInstance().getUserData() {
            parameters["user_id"] = userData.id
        }
        
        ApiManager().getFavTournaments(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                if let favTournamentList = result.value(forKey: "data") as? NSArray {
                    for favTournament in favTournamentList {
                        if let isDefaultValue = (favTournament as! NSDictionary).value(forKey: "is_default") as? Int {
                            if isDefaultValue == 1 {
                                if let userData = ApplicationData.sharedInstance().getUserData() {
                                    userData.tournamentId = (favTournament as! NSDictionary).value(forKey: "tournament_id") as! Int
                                    ApplicationData.sharedInstance().saveUserData(userData)
                                    break
                                }
                            }
                        }
                    }
                }
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        })
    }
}
