//
//  TabFavouritesViewController.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//

import UIKit

class TabFavouritesVC: SuperViewController {
    
    @IBOutlet var table: UITableView!
    @IBOutlet var btnFav: UIButton!
    
    var tournamentList = [Tournament]()
    var heightFavTournamentCell: CGFloat = 0
    
    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        self.navigationController?.isNavigationBarHidden = true
        initialize()
    }
    
    func initialize(){
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        // Height for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.FavouriteTournamentCell)
        heightFavTournamentCell = (cellOwner.cell as! FavouriteTournamentCell).getCellHeight()
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            if let modifiedImage = UIImage(named: "fav_small")?.withRenderingMode(.alwaysTemplate) {
                btnFav.setImageColor(color: UIColor.AppColor(), image: modifiedImage, state: .normal)
            }
        }
        
        // Get tournaments API request
        sendGetTournamentsRequest(true)
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
    
    //MARK:- Request Methods
    func sendGetTournamentsRequest(_ flag: Bool = false) {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        if flag {
            self.view.showProgressHUD()
        }
        
        ApiManager().getTournaments(success: { result in
            DispatchQueue.main.async {
                if let tournamentList = result.value(forKey: "data") as? NSArray {
                    for tournament in tournamentList {
                        self.tournamentList.append(ParseManager.parseTournament(tournament as! NSDictionary))
                    }
                    
                    // Sort array by start date
                    self.tournamentList.sort(by: { (t1, t2) -> Bool in
                        return (t1.startDateObj.timeIntervalSinceNow > t2.startDateObj.timeIntervalSinceNow)
                    })
                    
                    self.sendGetFavTournamentsRequest()
                }
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if result.allKeys.count == 0 {
                    return
                }
            }
        })
    }
    
    func sendSetDefaultFavTournamentRequest(_ tournament: Tournament) {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        if let userData = ApplicationData.sharedInstance().getUserData() {
            parameters["user_id"] = userData.id
        }
        parameters["tournament_id"] = tournament.id
        
        ApiManager().setDefaultFavTournament(parameters, success: { (result) in
            DispatchQueue.main.async {
                
                if let userData = ApplicationData.sharedInstance().getUserData() {
                    userData.tournamentId = tournament.id
                    ApplicationData.sharedInstance().saveUserData(userData)
                    ApplicationData.sharedInstance().saveSelectedTournament(tournament)
                }
                
                if !tournament.isFavourite {
                    self.sendAddFavTournamentRequest(tournament)
                } else {
                    self.sendGetFavTournamentsRequest()
                }
            }
        }) { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        }
    }
    
    func sendAddFavTournamentRequest(_ tournament: Tournament, flag: Bool = false) {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        if flag {
            self.view.showProgressHUD()
        }
        
        var parameters: [String: Any] = [:]
        if let userData = ApplicationData.sharedInstance().getUserData() {
            parameters["user_id"] = userData.id
        }
        parameters["tournament_id"] = tournament.id
        
        ApiManager().setFavTournament(parameters, success: { (result) in
            DispatchQueue.main.async {
                tournament.isFavourite = true
                self.sendGetFavTournamentsRequest()
            }
        }) { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        }
    }
    
    func sendRemoveFavTournamentRequest(_ tournament: Tournament) {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        if let userData = ApplicationData.sharedInstance().getUserData() {
            parameters["user_id"] = userData.id
        }
        parameters["tournament_id"] = tournament.id
        
        ApiManager().removeFavTournament(parameters, success: { (result) in
            DispatchQueue.main.async {
                tournament.isFavourite = false
                self.sendGetFavTournamentsRequest()
            }
        }) { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        }
    }
    
    func sendGetFavTournamentsRequest() {
        if APPDELEGATE.reachability.connection == .none {
            self.view.hideProgressHUD()
            return
        }
        
        var parameters: [String: Any] = [:]
        if let userData = ApplicationData.sharedInstance().getUserData() {
             parameters["user_id"] = userData.id
        }
        
        ApiManager().getFavTournaments(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                for i in 0..<self.tournamentList.count {
                    let tournament = self.tournamentList[i]
                    
                    if let favTournamentList = result.value(forKey: "data") as? NSArray {
                        for favTournament in favTournamentList {
                            var isDefault = 0
                            let favTournamentId = (favTournament as! NSDictionary).value(forKey: "tournament_id") as! Int
                            if let isDefaultValue = (favTournament as! NSDictionary).value(forKey: "is_default") as? Int {
                                isDefault = isDefaultValue
                            }
                            
                            if favTournamentId == tournament.id {
                                tournament.isFavourite = true
                                tournament.isDefault = isDefault
                                break
                            }
                        }
                    }
                }
                
                self.table.reloadData()
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if result.allKeys.count == 0 {
                    return
                }
                
                if let error = result.value(forKey: "error") as? String {
                    if error == "token_expired"{
                        USERDEFAULTS.set(nil, forKey: kUserDefaults.token)

                        if let keyWindow = UIApplication.shared.windows.first(where: { $0.isKeyWindow }) {
                            keyWindow.rootViewController = UINavigationController(rootViewController: Storyboards.Main.instantiateLandingVC())
                        }
                    }
                }
            }
        })
    }
}

extension TabFavouritesVC: FavouriteTournamentCellDelegate {
    func favTournamentCellFavBtnPressed(_ indexPath: IndexPath) {
        TestFairy.log(String(describing: self) + " favTournamentCellFavBtnPressed")
        
        let tournament = tournamentList[indexPath.row]
        
        if tournament.isDefault == 1 {
            self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: String.localize(key: "alert_default_tournament_remove"))
        } else {
            if tournament.isFavourite {
                sendRemoveFavTournamentRequest(tournament)
            } else {
                sendAddFavTournamentRequest(tournament, flag: true)
            }
        }
    }
    
    func favTournamentCellSelectDefaultBtnPressed(_ indexPath: IndexPath) {
        let tournament = tournamentList[indexPath.row]
        
        if tournament.isDefault == 0 {
            sendSetDefaultFavTournamentRequest(tournament)
        }
    }
}

extension TabFavouritesVC : UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return tournamentList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return heightFavTournamentCell
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell = tableView.dequeueReusableCell(withIdentifier: "FavouriteTournamentCell") as? FavouriteTournamentCell
        if cell == nil {
            _ = cellOwner.loadMyNibFile(nibName: "FavouriteTournamentCell")
            cell = cellOwner.cell as? FavouriteTournamentCell
            cell?.delegate = self
        }
        cell?.indexPath = indexPath
        cell?.record = tournamentList[indexPath.row]
        cell?.reloadCell()
        return cell!
    }
}
