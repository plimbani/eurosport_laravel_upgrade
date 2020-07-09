//
//  ClubListVC.swift
//  ESR
//
//  Created by Pratik Patel on 07/12/18.
//

import UIKit

class ClubListVC: SuperViewController {

    @IBOutlet var txtSearch: UITextField!
    @IBOutlet var table: UITableView!
    
    var tournamentClubList = NSMutableArray()
    var tournamentClubFilterList = NSMutableArray()
    var heightTournamentClubCell: CGFloat = 0
    
    var isSearch = false
    
    @IBOutlet var lblNoData: UILabel!
    
    var isDataAvailable: Bool = false {
        didSet {
            table.isHidden = !isDataAvailable
            lblNoData.isHidden = isDataAvailable
        }
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        initialize()
    }
    
    override func viewWillAppear(_ animated: Bool) {
        sendGetTournamentClubsRequest()
    }
    
    func initialize() {
        txtSearch.placeholder = String.localize(key: "placeholder_search_tab_club")
        txtSearch.setLeftPaddingPoints(35)
        txtSearch.returnKeyType = .done
        txtSearch.layer.cornerRadius = (txtSearch.frame.size.height / 2)
        txtSearch.clipsToBounds = true
        txtSearch.clearButtonMode = .whileEditing
        txtSearch.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonTextFieldTxt)
        txtSearch.addTarget(self, action: #selector(textFieldDidChange(textField:)), for: .editingChanged)
        
        // Height for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.TournamentClubCell)
        heightTournamentClubCell = (cellOwner.cell as! TournamentClubCell).getCellHeight()
        
        hideKeyboardWhenTappedAround()
    }
    
    @objc func textFieldDidChange(textField: UITextField) {
        if let text = txtSearch.text {
            if text.isEmpty {
                isSearch = false
                table.reloadData()
                return
            }
            
            isSearch = true
            
            tournamentClubFilterList = NSMutableArray.init(array: tournamentClubList.filter({
                (($0 as! NSDictionary).value(forKey: "clubName") as! String).contains(text) ||
                (($0 as! NSDictionary).value(forKey: "clubName") as! String).lowercased().contains(text)
            }))
            
            table.reloadData()
        }
    }
    
    func sendGetTournamentClubsRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            parameters["tournament_id"] = selectedTournament.id
        }
        
        ApiManager().getTournamentClub(parameters, success: { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    self.tournamentClubList = NSMutableArray.init(array: data)
                }
                
                let descriptor: NSSortDescriptor = NSSortDescriptor(key: "clubName", ascending: true)
                self.tournamentClubList = NSMutableArray.init(array: self.tournamentClubList.sortedArray(using: [descriptor]))
                self.table.reloadData()
                
                self.isDataAvailable = (self.tournamentClubList.count != 0)
            }
        }) { (result) in
            
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                self.isDataAvailable = false
                
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
                                keyWindow.rootViewController = Storyboards.Main.instantiateLandingVC()
                            }
                        }
                    }
                }
            }
        }
    }
}

extension ClubListVC: CustomAlertVCDelegate {
    func customAlertVCOkBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.tournamentExpire.rawValue {
            NotificationCenter.default.post(name: .goToTabFollow, object: nil)
        }
    }
}

extension ClubListVC: UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        if isSearch {
            return tournamentClubFilterList.count
        }
        
        return tournamentClubList.count
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
        
        if isSearch {
            cell?.record = tournamentClubFilterList[indexPath.row] as! NSDictionary
        } else {
            cell?.record = tournamentClubList[indexPath.row] as! NSDictionary
        }
        cell?.reloadCell()
        return cell!
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        TestFairy.log(String(describing: self) + " didSelectRowAt")
        self.view.endEditing(true)
        txtSearch.resignFirstResponder()
        let viewController = Storyboards.Teams.instantiateTeamListingVC()
        
        if isSearch {
            viewController.dic = (tournamentClubFilterList[indexPath.row] as! NSDictionary)
        } else {
            viewController.dic = (tournamentClubList[indexPath.row] as! NSDictionary)
        }
        
        viewController.isClubTeam = true
        self.view.endEditing(true)
        self.navigationController?.pushViewController(viewController, animated: true)
    }
}
