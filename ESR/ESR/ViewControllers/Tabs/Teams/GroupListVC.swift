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
    var ageCategoriesGroupsMainFilterList = NSMutableArray()
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
        table.estimatedRowHeight = 200
        table.rowHeight = UITableViewAutomaticDimension
        table.tableHeaderView = UIView()
        
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
            
            ageCategoriesGroupsFilterList = NSMutableArray.init(array: ageCategoriesGroupsMainFilterList.filter({
                let searchDic = ($0 as! NSDictionary)
                
                return (searchDic.value(forKey: "display_name") as! String).contains(text) ||
                (searchDic.value(forKey: "display_name") as! String).lowercased().contains(text)
            }))
            
            table.reloadData()
        }
    }
    
    func sortByDisplayName(dataArray: NSArray) -> NSMutableArray {
        let descriptor: NSSortDescriptor = NSSortDescriptor(key: "display_name", ascending: true)
        return NSMutableArray.init(array: dataArray.sortedArray(using: [descriptor]))
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
        
        self.ageCategoriesGroupsList.removeAllObjects()
        self.ageCategoriesGroupsMainFilterList.removeAllObjects()
        self.table.reloadData()
        
        ApiManager().getAgeCategoriesGroups(parameters, success: { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSDictionary {
                   
                if let mainList = data.value(forKey: "round_robin_groups") as? NSArray {
                    
                    for roundRobinObj in mainList {
                        let mutableDic = NSMutableDictionary.init(dictionary: roundRobinObj as! NSDictionary)
                        mutableDic[ApplicationData.dicKeyDivision] = false
                        self.ageCategoriesGroupsList.add(mutableDic)
                        self.ageCategoriesGroupsMainFilterList.add(mutableDic)
                    }
                    
                    self.ageCategoriesGroupsList = self.sortByDisplayName(dataArray: self.ageCategoriesGroupsList)
                }
                
                if let mainList = data.value(forKey: "division_groups") as? NSArray {
                    for divisionObj in mainList {
                        
                        let mutableDic = NSMutableDictionary.init(dictionary: divisionObj as! NSDictionary)
                        mutableDic[ApplicationData.dicKeyDivision] = true
                        
                        if let divisionDataList = (divisionObj as! NSDictionary).value(forKey: "data") as? NSArray {
                            mutableDic["divisionArray"] = divisionDataList
                            
                            for obj in divisionDataList {
                                let mutableDic = NSMutableDictionary.init(dictionary: obj as! NSDictionary)
                                mutableDic[ApplicationData.dicKeyDivision] = false
                                self.ageCategoriesGroupsMainFilterList.add(mutableDic)
                            }
                        }
                        
                        self.ageCategoriesGroupsList.add(mutableDic)
                    }
                }
                
                
                self.table.reloadData()
                self.isDataAvailable = (self.ageCategoriesGroupsList.count != 0)
            }
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
                                keyWindow.rootViewController = UINavigationController(rootViewController:  Storyboards.Main.instantiateLandingVC())
                            }
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
        return UITableViewAutomaticDimension
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var dic: NSDictionary!
        if isSearch {
            dic = ageCategoriesGroupsFilterList[indexPath.row] as! NSDictionary
        } else {
            dic = ageCategoriesGroupsList[indexPath.row] as! NSDictionary
        }
        
        if (dic[ApplicationData.dicKeyDivision] as! Bool) {
            var cell = tableView.dequeueReusableCell(withIdentifier: "AgeCategoryDivisionCell") as? AgeCategoryDivisionCell
            if cell == nil {
                _ = cellOwner.loadMyNibFile(nibName: "AgeCategoryDivisionCell")
                cell = cellOwner.cell as? AgeCategoryDivisionCell
            }
            cell?.cellIndexPath = indexPath
            cell?.record = dic
            cell?.divisionObjList = dic["divisionArray"] as! NSArray
            cell?.didTapView = { [weak self] dic in
                guard let selfValue = self else { return }
                selfValue.goToTeamListingVCList(dic)
            }
            cell?.didUpdateTableView = { [weak self] cellIndexPath in
                guard let selfValue = self else { return }
                selfValue.table.beginUpdates()
                selfValue.table.endUpdates()
                selfValue.table.scrollToRow(at: cellIndexPath, at: .middle, animated: true)
            }
            cell?.reloadCell()
            return cell!
        }
        
        var cell = tableView.dequeueReusableCell(withIdentifier: "AgeCategoryCell") as? AgeCategoryCell
        if cell == nil {
            _ = cellOwner.loadMyNibFile(nibName: "AgeCategoryCell")
            cell = cellOwner.cell as? AgeCategoryCell
            cell?.isAgeGroup = true
        }
        cell?.record = dic
        cell?.reloadCell()
        return cell!
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        TestFairy.log(String(describing: self) + " didSelectRowAt")
        self.view.endEditing(true)
        txtSearch.resignFirstResponder()
        
        let dic = isSearch ? (ageCategoriesGroupsFilterList[indexPath.row] as! NSDictionary) :
        (ageCategoriesGroupsList[indexPath.row] as! NSDictionary)
        
        if (dic[ApplicationData.dicKeyDivision] as! Bool) {
            if let cell = tableView.cellForRow(at: indexPath) as? AgeCategoryDivisionCell {
                cell.showHideDivisions()
            }
            return
        }
        
        goToTeamListingVCList(dic)
    }
    
    func goToTeamListingVCList(_ dic: NSDictionary) {
        let viewController = Storyboards.Teams.instantiateTeamListingVC()
        viewController.isClubsGroupTeam = true
        viewController.dic = dic
        self.navigationController?.pushViewController(viewController, animated: true)
    }
}
