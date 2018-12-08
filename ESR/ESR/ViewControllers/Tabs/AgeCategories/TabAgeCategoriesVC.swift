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
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize() {
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
        
        initInfoAlertView(self.view)
        
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
            }
        }
    }
}

extension TabAgeCategoriesVC: TabAgeCategoriesCellDelegate {
    func tabAgeCategoriesCellBtnInfoPressed(_ indexPath: IndexPath) {
        if let comment = (ageCategoriesList[indexPath.row] as! NSDictionary).value(forKey: "comments") as? String {
            showInfoAlertView(title: String.localize(key: "alert_title_comment"), message: comment, buttonTitle: String.localize(key: "btn_close"))
        }
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
