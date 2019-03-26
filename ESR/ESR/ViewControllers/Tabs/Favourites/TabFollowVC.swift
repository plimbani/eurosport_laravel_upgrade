//
//  TabFollowVC.swift
//  ESR
//
//  Created by Pratik Patel on 18/03/19.
//

import UIKit

class TabFollowVC: SuperViewController {
    
    @IBOutlet var table: UITableView!
    @IBOutlet var footerView: UIView!
    @IBOutlet var txtTournamentCode: UITextField!
    @IBOutlet var btnUpdate: UIButton!
    
    var tournamentList = [Tournament]()
    
    var heightFollowTournamentCell: CGFloat = 0
    
    var delegate: MainTabViewControllerDelegate?
    
    override func viewDidLoad() {
        super.viewDidLoad()
        self.navigationController?.isNavigationBarHidden = true
        intialize()
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
    
    func intialize(){
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        txtTournamentCode.setLeftPaddingPoints(5)
        txtTournamentCode.addTarget(self, action: #selector(textFieldDidChange(textField:)), for: .editingChanged)
        
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.FollowTournamentCell)
        heightFollowTournamentCell = (cellOwner.cell as! FollowTournamentCell).getCellHeight()
        
        btnUpdate.isEnabled = false
        btnUpdate.backgroundColor = UIColor.btnDisable
        
        table.tableHeaderView = UIView()
        table.tableFooterView = footerView
        
        ApplicationData.setBorder(view: txtTournamentCode, Color: .gray, CornerRadius: 1.0, Thickness: 1.0)
        
        getFavTournamentsAPI()
        
        hideKeyboardWhenTappedAround()
    }
    
    @objc func textFieldDidChange(textField: UITextField){
        updateUpdateBtn()
    }
    
    func updateUpdateBtn(){
        btnUpdate.isEnabled = false
        btnUpdate.backgroundColor = UIColor.btnDisable
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnUpdate.setBackgroundImage(nil, for: .normal)
        }
        
        if let text = txtTournamentCode.text {
            if text.trimmingCharacters(in: .whitespacesAndNewlines).isEmpty {
                return
            }
        }
        
        btnUpdate.isEnabled = true
        btnUpdate.backgroundColor = UIColor.btnYellow
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnUpdate.setBackgroundImage(UIImage.init(named: "btn_yellow"), for: .normal)
        }
    }
    
    @IBAction func updateTournamentBtnPressed(_ sender: UIButton) {
        accessCodeAPI()
    }
    
    func getFavTournamentsAPI() {
        if APPDELEGATE.reachability.connection == .none {
            self.view.hideProgressHUD()
            return
        }
        
        var parameters: [String: Any] = [:]
        if let userData = ApplicationData.sharedInstance().getUserData() {
            parameters["user_id"] = userData.id
        }
        
        self.view.showProgressHUD()
        ApiManager().getFavTournaments(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let favTournamentList = result.value(forKey: "data") as? NSArray {
                    
                    for dicTournament in favTournamentList {
                        let tournament = ParseManager.parseFollowTournament(dicTournament as! NSDictionary)
                        
                        if let userData = ApplicationData.sharedInstance().getUserData() {
                            if tournament.id == userData.tournamentId {
                                tournament.isFavourite = true
                            }
                        }
                        
                        self.tournamentList.append(tournament)
                    }
                } else {
                    UIApplication.shared.keyWindow?.rootViewController = Storyboards.Favourites.instantiateGetStartedTournamentVC()
                    return
                }
                
                self.table.reloadData()
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        })
    }
    
    func accessCodeAPI() {
        if APPDELEGATE.reachability.connection == .none {
            self.view.hideProgressHUD()
            return
        }
        
        var parameters: [String: Any] = [:]
        parameters["accessCode"] = txtTournamentCode.text!
        
        ApiManager().accessCode(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let dicTournament = result.value(forKey: "data") as? NSDictionary {
                    let tournament = ParseManager.parseTournament(dicTournament)
                    ApplicationData.sharedInstance().saveSelectedTournament(tournament)
                    self.delegate!.mainTabViewControllerSelectTab(TabIndex.tabTournament.rawValue)
                }
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let message = result.value(forKey: "message") as? String {
                    self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: message)
                }
            }
        })
    }
}

extension TabFollowVC: UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return tournamentList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return heightFollowTournamentCell
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell = tableView.dequeueReusableCell(withIdentifier: kNiB.Cell.FollowTournamentCell) as? FollowTournamentCell
        if cell == nil {
            _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.FollowTournamentCell)
            cell = cellOwner.cell as? FollowTournamentCell
        }
        cell!.dic = tournamentList[indexPath.row]
        cell!.reloadCell()
        return cell!
    }
}
