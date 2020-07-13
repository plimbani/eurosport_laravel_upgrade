//
//  TeamListingVC.swift
//  ESR
//
//  Created by Pratik Patel on 18/10/18.
//

import UIKit

class TeamListingVC: SuperViewController {

    @IBOutlet var table: UITableView!
    var teamList = NSMutableArray()
    
    var heightTournamentClubCell: CGFloat = 0
    
    var dic: NSDictionary!
    var isClubTeam = false
    var isClubsCategoryTeam = false
    var isClubsGroupTeam = false
    
    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        initialize()
    }
    
    func initialize() {
        titleNavigationBar.lblTitle.text = String.localize(key: "title_team")
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
        
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        _ = cellOwner.loadMyNibFile(nibName: "TournamentClubCell")
        heightTournamentClubCell = (cellOwner.cell as! TournamentClubCell).getCellHeight()
        
        sendGetTeamListRequest()
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
    
    func sendGetTeamListRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        
        if isClubTeam {
            if let id = dic.value(forKey: "ClubId") as? Int {
                parameters["club_id"] = id
            }
        } else if isClubsCategoryTeam {
            if let id = dic.value(forKey: "id") as? Int {
                parameters["age_group_id"] = id
            }
        } else if isClubsGroupTeam {
            if let id = dic.value(forKey: "id") as? Int {
                parameters["group_id"] = id
            }
        }
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            parameters["tournament_id"] = selectedTournament.id
        }
        
        ApiManager().getTeamList(parameters, success: { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    self.teamList = NSMutableArray.init(array: data)
                }
                
                let descriptor: NSSortDescriptor = NSSortDescriptor(key: "name", ascending: true)
                self.teamList = NSMutableArray.init(array: self.teamList.sortedArray(using: [descriptor]))
                self.table.reloadData()
            }
        }) { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if result.allKeys.count > 0 {
                    if let status_code = result.value(forKey: "status_code") as? Int {
                        if status_code == 500 {
                            if let msg = result.value(forKey: "tournament_expired") as? String {
                                self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: msg, requestCode: AlertRequestCode.tournamentExpire.rawValue, delegate: self)
                            }
                        }
                    }
                    
                    if let error = result.value(forKey: "error") as? String {
                        if error == "token_expired"{
                            USERDEFAULTS.set(nil, forKey: kUserDefaults.token)
                            
                            if let keyWindow = UIApplication.shared.windows.first(where: { $0.isKeyWindow }) {
                                keyWindow.rootViewController = UINavigationController(rootViewController:  Storyboards.Main.instantiateLandingVC())
                            }
                        }
                    }
                }
            }
        }
    }
}



extension TeamListingVC: CustomAlertVCDelegate {
    func customAlertVCOkBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.tournamentExpire.rawValue {
            NotificationCenter.default.post(name: .goToTabFollow, object: nil)
        }
    }
}

extension TeamListingVC: TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        TestFairy.log(String(describing: self) + " titleNavBarBackBtnPressed")
        self.navigationController?.popViewController(animated: true)
    }
}

extension TeamListingVC: UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return teamList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return heightTournamentClubCell
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell = tableView.dequeueReusableCell(withIdentifier: "TournamentClubCell") as? TournamentClubCell
        if cell == nil {
            _ = cellOwner.loadMyNibFile(nibName: "TournamentClubCell")
            cell = cellOwner.cell as? TournamentClubCell
        }
        
        cell?.record = teamList[indexPath.row] as! NSDictionary
        cell?.isClubsTeam = isClubTeam
        cell?.isClubsCategoryTeam = isClubsCategoryTeam
        cell?.isClubsGroupTeam = isClubsGroupTeam
        cell?.reloadCell()
        return cell!
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        let viewController = Storyboards.Teams.instantiateTeamVC()
        viewController.dicTeam = teamList[indexPath.row] as! NSDictionary
        
        if isClubsCategoryTeam {
            viewController.teamList = teamList
            viewController.selectedPickerPosition = indexPath.row
        }
        
        self.navigationController?.pushViewController(viewController, animated: true)
    }
}


