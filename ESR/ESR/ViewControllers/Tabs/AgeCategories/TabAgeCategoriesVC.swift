//
//  TabAgeCategoriesVC.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//

import UIKit

class TabAgeCategoriesVC: SuperViewController {

    @IBOutlet var table: UITableView!
    
    var ageCategoriesList = NSArray()
    var heightAgeCategoriesCell: CGFloat = 0
    
    var rotateToPortrait = false
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    override func viewWillAppear(_ animated: Bool) {
        if rotateToPortrait {
            APPDELEGATE.deviceOrientation = .portrait
            let valueOrientation = UIInterfaceOrientation.portrait.rawValue
            UIDevice.current.setValue(valueOrientation, forKey: "orientation")
            UIViewController.attemptRotationToDeviceOrientation()
            self.tabBarController?.tabBar.isHidden = false
            rotateToPortrait = false
            
            if let mainTabViewController = self.parent!.parent as? MainTabViewController {
                mainTabViewController.hideTabbar(flag: false)
            }
        }
    }
    
    func initialize() {
        let adjustForTabbarInsets: UIEdgeInsets = UIEdgeInsetsMake(0, 0, 60, 0)
        table.contentInset = adjustForTabbarInsets
        table.scrollIndicatorInsets = adjustForTabbarInsets
        
        self.navigationController?.isNavigationBarHidden = true
        titleNavigationBar.lblTitle.text = String.localize(key: "title_age_categories")
        titleNavigationBar.hideBackButton()
        titleNavigationBar.setBackgroundColor()
        
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        // Height for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.TabAgeCategoriesCell)
        heightAgeCategoriesCell = (cellOwner.cell as! TabAgeCategoriesCell).getCellHeight()
        
        // initInfoAlertView(self.view)
        
        sendAgeCategoriesRequest()
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
    
    func sendAgeCategoriesRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            parameters["tournament_id"] = selectedTournament.id
        }
        
        ApiManager().getAgeCategories(parameters, success: { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    self.ageCategoriesList = data
                }
                
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
                }
            }
        }
    }
}

extension TabAgeCategoriesVC: CustomAlertVCDelegate {
    func customAlertVCOkBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.tournamentExpire.rawValue {
            NotificationCenter.default.post(name: .goToTabFollow, object: nil)
        }
    }
}

extension TabAgeCategoriesVC: TabAgeCategoriesCellDelegate {
    func tabAgeCategoriesCellBtnInfoPressed(_ indexPath: IndexPath) {
        if let comment = (ageCategoriesList[indexPath.row] as! NSDictionary).value(forKey: "comments") as? String {
           self.showCustomAlertVC(title: String.localize(key: "alert_title_success"), message: comment)
        }
    }
    
    func tabAgeCategoriesCellBtnViewSchedulePressed(_ indexPath: IndexPath) {
        let viewController = Storyboards.Main.instantiateViewScheduleImageVC()
        
        if let imgURLValue = (ageCategoriesList[indexPath.row] as! NSDictionary).value(forKey: "graphic_image") as? String {
            viewController.imgURL = imgURLValue
        }
        
        self.navigationController?.pushViewController(viewController, animated: true)
    }
}

extension TabAgeCategoriesVC: UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return ageCategoriesList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return heightAgeCategoriesCell
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell = tableView.dequeueReusableCell(withIdentifier: "TabAgeCategoriesCell") as? TabAgeCategoriesCell
        if cell == nil {
            _ = cellOwner.loadMyNibFile(nibName: "TabAgeCategoriesCell")
            cell = cellOwner.cell as? TabAgeCategoriesCell
        }
        cell?.record = ageCategoriesList[indexPath.row] as! NSDictionary
        cell?.indexPath = indexPath
        cell?.delegate = self
        cell?.reloadCell()
        return cell!
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        let viewController = Storyboards.AgeCategories.instantiateAgeCategoriesGroupsVC()
        viewController.ageCategoryId = (ageCategoriesList[indexPath.row] as! NSDictionary).value(forKey: "id") as! Int
        self.navigationController?.pushViewController(viewController, animated: true)
    }
}
