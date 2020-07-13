//
//  AgeCategoriesGroupsVC.swift
//  ESR
//
//  Created by Pratik Patel on 28/08/18.
//

import UIKit

class AgeCategoriesGroupsVC: SuperViewController {

    @IBOutlet var table: UITableView!
    
    var groupList = NSMutableArray()
    
    var groupsDropDownList = NSMutableArray()
    var divisionList = [NSMutableDictionary]()
    
    var heightAgeCategoryCell: CGFloat = 0
    var ageCategoryId: Int = NULL_ID
    
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
    
    func initialize() {
        table.estimatedRowHeight = 200
        table.rowHeight = UITableViewAutomaticDimension
        
        titleNavigationBar.lblTitle.text = String.localize(key: "title_age_categories_groups")
        titleNavigationBar.delegate = self
        titleNavigationBar.isFinalPlacings = true
        titleNavigationBar.setBackgroundColor()
        
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        // Height for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.AgeCategoryCell)
        heightAgeCategoryCell = (cellOwner.cell as! AgeCategoryCell).getCellHeight()
        
        sendAgeCategoriesGroupRequest()
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
    
    func sendAgeCategoriesGroupRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]

        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            parameters["tournamentId"] = selectedTournament.id
            parameters["competationFormatId"] = ageCategoryId
        }
        
        groupsDropDownList.removeAllObjects()
        
        ApiManager().getAgeCategoriesGroups(parameters, success: { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSDictionary {
                    
                    if let mainList = data.value(forKey: "round_robin_groups") as? NSArray {
                        
                        for roundRobinObj in mainList {
                            let mutableDic = NSMutableDictionary.init(dictionary: roundRobinObj as! NSDictionary)
                            mutableDic[ApplicationData.dicKeyDivision] = false
                            mutableDic[ApplicationData.dicKeyDivisionRow] = false
                            
                            self.groupList.add(mutableDic)
                            self.groupsDropDownList.add(mutableDic)
                        }
                    }
                    
                    if let mainList = data.value(forKey: "division_groups") as? NSArray {
                        for divisionObj in mainList {
                            
                            let mutableDic = NSMutableDictionary.init(dictionary: divisionObj as! NSDictionary)
                            mutableDic[ApplicationData.dicKeyDivision] = true
                            mutableDic[ApplicationData.dicKeyDivisionRow] = false
                            
                            self.groupsDropDownList.add(mutableDic)
                            
                            if let divisionDataList = (divisionObj as! NSDictionary).value(forKey: "data") as? NSArray {
                                
                                self.divisionList.removeAll()
                                
                                for divisionObj in divisionDataList {
                                    let mutableDivisionDic = NSMutableDictionary.init(dictionary: divisionObj as! NSDictionary)
                                    mutableDivisionDic[ApplicationData.dicKeyDivision] = false
                                    mutableDivisionDic[ApplicationData.dicKeyDivisionRow] = true
                                    mutableDivisionDic[ApplicationData.dicKeyDivisionName] = (divisionObj as! NSDictionary).value(forKey: "display_name") as! String
                                    self.groupsDropDownList.add(mutableDivisionDic)
                                    
                                    self.divisionList.append(mutableDivisionDic)
                                }
                                
                                mutableDic["divisionArray"] = self.divisionList
                            }
                            
                            self.groupList.add(mutableDic)
                        }
                    }
                }
                
                self.table.reloadData()
                self.isDataAvailable = (self.groupList.count != 0)
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

extension AgeCategoriesGroupsVC: CustomAlertVCDelegate {
    func customAlertVCOkBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.tournamentExpire.rawValue {
            NotificationCenter.default.post(name: .goToTabFollow, object: nil)
        }
    }
}

extension AgeCategoriesGroupsVC: TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        TestFairy.log(String(describing: self) + " titleNavBarBackBtnPressed")
        self.navigationController?.popViewController(animated: true)
    }
    
    func titleNavBarBtnFinalPlacingsPressed() {
        TestFairy.log(String(describing: self) + " titleNavBarBtnFinalPlacingsPressed")
        let viewController = Storyboards.Tournament.instantiateFinalPlacingsVC()
        viewController.ageCategoryId = ageCategoryId
        self.navigationController?.pushViewController(viewController, animated: true)
    }
}

extension AgeCategoriesGroupsVC : UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return groupList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return UITableViewAutomaticDimension
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let dic = groupList[indexPath.row] as! NSDictionary
        
        if (dic[ApplicationData.dicKeyDivision] as! Bool) {
            var cell = tableView.dequeueReusableCell(withIdentifier: "AgeCategoryDivisionCell") as? AgeCategoryDivisionCell
            if cell == nil {
                _ = cellOwner.loadMyNibFile(nibName: "AgeCategoryDivisionCell")
                cell = cellOwner.cell as? AgeCategoryDivisionCell
            }
            cell?.record = dic
            cell?.divisionObjList = dic["divisionArray"] as! NSArray
            cell?.cellIndexPath = indexPath
            cell?.didTapView = { [weak self] dic in
                guard let selfValue = self else { return }
                selfValue.goToAgeCategoriesGroupsList(dic)
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
        let dic = groupList[indexPath.row] as! NSDictionary
        if (dic[ApplicationData.dicKeyDivision] as! Bool) {
            if let cell = tableView.cellForRow(at: indexPath) as? AgeCategoryDivisionCell {
                cell.showHideDivisions()
            }
            return
        }
        
        goToAgeCategoriesGroupsList(dic)
    }
    
    func goToAgeCategoriesGroupsList(_ dic: NSDictionary) {
        let viewController = Storyboards.AgeCategories.instantiateAgeCategoriesGroupsSummaryVC()
        viewController.dicGroup = dic
        // viewController.selectedPickerPosition = indexPath.row
        viewController.ageCategoriesGroupsDropDownList = groupsDropDownList
        self.navigationController?.pushViewController(viewController, animated: true)
    }
}
