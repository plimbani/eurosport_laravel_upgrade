//
//  GroupListVC.swift
//  ESR
//
//  Created by Pratik Patel on 07/12/18.
//

import UIKit

class GroupListVC: SuperViewController {

    @IBOutlet var txtSearch: UITextField!
    @IBOutlet var table: UITableView!
    
    var ageCategoriesGroupsList = NSMutableArray()
    var ageCategoriesGroupsFilterList = NSMutableArray()
    var heightAgeCategoryCell: CGFloat = 0
    
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
        sendAgeCategoriesGroupRequest()
    }
    
    func initialize() {
        txtSearch.placeholder = String.localize(key: "placeholder_search_tab_group")
        txtSearch.setLeftPaddingPoints(35)
        txtSearch.delegate = self
        txtSearch.returnKeyType = .done
        txtSearch.layer.cornerRadius = (txtSearch.frame.size.height / 2)
        txtSearch.clipsToBounds = true
        txtSearch.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonTextFieldTxt)
        txtSearch.addTarget(self, action: #selector(textFieldDidChange(textField:)), for: .editingChanged)
        
        // Height for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.AgeCategoryCell)
        heightAgeCategoryCell = (cellOwner.cell as! AgeCategoryCell).getCellHeight()
        
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
            
            ageCategoriesGroupsFilterList = NSMutableArray.init(array: ageCategoriesGroupsList.filter({
                (($0 as! NSDictionary).value(forKey: "display_name") as! String).contains(text) ||
                (($0 as! NSDictionary).value(forKey: "display_name") as! String).lowercased().contains(text)
            }))
            
            table.reloadData()
        }
    }
    
    func sendAgeCategoriesGroupRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            parameters["tournamentId"] = selectedTournament.id
        }
        
        ApiManager().getAgeCategoriesGroups(parameters, success: { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let dataObj = result.value(forKey: "data") as? NSDictionary {
                    if let data = dataObj.value(forKey: "mainData") as? NSArray {
                        
                        let descriptor: NSSortDescriptor = NSSortDescriptor(key: "display_name", ascending: true)
                        self.ageCategoriesGroupsList = NSMutableArray.init(array: data.sortedArray(using: [descriptor]))
                        ApplicationData.groupsList = self.ageCategoriesGroupsList
                    }
                }
                
                self.table.reloadData()
                self.isDataAvailable = (self.ageCategoriesGroupsList.count != 0)
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
                            UIApplication.shared.keyWindow?.rootViewController = UINavigationController(rootViewController: Storyboards.Main.instantiateLandingVC())
                        }
                    }
                }
            }
        }
    }
}

extension GroupListVC: CustomAlertVCDelegate {
    func customAlertVCOkBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.tournamentExpire.rawValue {
            NotificationCenter.default.post(name: .goToTabFollow, object: nil)
        }
    }
}

extension GroupListVC: UITextFieldDelegate {
    func textFieldDidBeginEditing(_ textField: UITextField) {}
}

extension GroupListVC: UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        
        if isSearch {
            return ageCategoriesGroupsFilterList.count
        }
        
        return ageCategoriesGroupsList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return heightAgeCategoryCell
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell = tableView.dequeueReusableCell(withIdentifier: "AgeCategoryCell") as? AgeCategoryCell
        if cell == nil {
            _ = cellOwner.loadMyNibFile(nibName: "AgeCategoryCell")
            cell = cellOwner.cell as? AgeCategoryCell
            cell?.isAgeGroup = true
        }
        
        if isSearch {
            cell?.record = ageCategoriesGroupsFilterList[indexPath.row] as! NSDictionary
        } else {
            cell?.record = ageCategoriesGroupsList[indexPath.row] as! NSDictionary
        }
        
        cell?.reloadCell()
        return cell!
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        TestFairy.log(String(describing: self) + " didSelectRowAt")
        self.view.endEditing(true)
        txtSearch.resignFirstResponder()
        let viewController = Storyboards.Teams.instantiateTeamListingVC()
        viewController.isClubsGroupTeam = true
        
        if isSearch {
            viewController.dic = (ageCategoriesGroupsFilterList[indexPath.row] as! NSDictionary)
        } else {
            viewController.dic = (ageCategoriesGroupsList[indexPath.row] as! NSDictionary)
        }
        
        self.navigationController?.pushViewController(viewController, animated: true)
    }
}
