//
//  AgeCategoriesGroupsVC.swift
//  ESR
//
//  Created by Pratik Patel on 28/08/18.
//

import UIKit

class AgeCategoriesGroupsVC: SuperViewController {

    @IBOutlet var table: UITableView!
    
    var ageCategoriesGroupsList = NSArray()
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
        
        ApiManager().getAgeCategoriesGroups(parameters, success: { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    self.ageCategoriesGroupsList = data
                    ApplicationData.groupsList = data
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
        cell?.record = ageCategoriesGroupsList[indexPath.row] as! NSDictionary
        cell?.reloadCell()
        return cell!
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        TestFairy.log(String(describing: self) + " didSelectRowAt")
        let viewController = Storyboards.AgeCategories.instantiateAgeCategoriesGroupsSummaryVC()
        viewController.dicGroup = (ageCategoriesGroupsList[indexPath.row] as! NSDictionary)
        viewController.selectedPickerPosition = indexPath.row
        self.navigationController?.pushViewController(viewController, animated: true)
    }
}
